<?php

namespace App\Http\Controllers;

use App\Helpers\BadWordHelper;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (BadWordHelper::checkOnline($validated['comment'])) {
            return redirect()->back()->with('error', 'Komentar mengandung kata tidak pantas!');
        }

        $userId = (int) Auth::id();

        $order = Order::where('id_order', $validated['id_order'])
            ->ownedBy($userId)
            ->where('status', Order::STATUS_COMPLETED)
            ->first();

        if (!$order) {
            Log::warning("Review Store: Pesanan tidak valid. Order: {$validated['id_order']}, User: {$userId}");
            return redirect()->back()->with('error', 'Pesanan tidak valid.');
        }

        $hasItem = OrderDetail::where('id_order', $order->id_order)
            ->where('id_menu', $validated['id_menu'])
            ->exists();

        if (!$hasItem) {
            return redirect()->back()->with('error', 'Anda tidak membeli menu ini.');
        }

        $alreadyReviewed = Review::where('id_order', $order->id_order)
            ->where('id_menu', $validated['id_menu'])
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()->with('error', 'Anda sudah mengulas menu ini.');
        }

        Review::create([
            'id_user' => $userId,
            'id_menu' => $validated['id_menu'],
            'id_order' => $order->id_order,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    public function update(UpdateReviewRequest $request, int $id): RedirectResponse
    {
        $review = Review::where('id_user', Auth::id())->findOrFail($id);
        $validated = $request->validated();

        if (BadWordHelper::checkOnline($validated['comment'])) {
            return redirect()->back()->with('error', 'Komentar mengandung kata tidak pantas!');
        }

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $review = Review::where('id_user', Auth::id())->findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }

    public function indexTenant(Request $request): View|RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }

        $reviews = Review::forTenant($tenant->id_tenant, $request->input('menu_name'))
            ->paginate(10);

        return view('tenant.reviews.index', compact('reviews'));
    }

    public function indexAdmin(Request $request): View
    {
        $reviews = Review::searchAdmin($request->input('search'))
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroyAdmin(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus oleh Admin.');
    }

    public function exportAdmin(): StreamedResponse
    {
        $reviews = Review::with(['user', 'menu.tenant'])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=laporan_ulasan-' . date('Y-m-d') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Tanggal', 'Pelanggan', 'Kantin', 'Menu', 'Rating', 'Komentar']);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id_review,
                    $review->created_at->format('Y-m-d H:i'),
                    $this->sanitizeCsvCell($review->user->name ?? 'User Dihapus'),
                    $this->sanitizeCsvCell($review->menu->tenant->name ?? 'Tenant Dihapus'),
                    $this->sanitizeCsvCell($review->menu->name ?? 'Menu Dihapus'),
                    $review->rating,
                    $this->sanitizeCsvCell($review->comment),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mencegah CSV Formula Injection saat berkas dibuka di spreadsheet (Excel/Sheets).
     */
    private function sanitizeCsvCell(?string $value): string
    {
        $text = (string) ($value ?? '');
        if ($text !== '' && preg_match('/^[=+\-@\t\r]/', $text)) {
            return "'" . $text;
        }

        return $text;
    }
}
