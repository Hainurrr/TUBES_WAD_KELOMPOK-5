<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BadWordHelper;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan baru dari pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_order' => 'required|exists:orders,id_order',
            'id_menu' => 'required|exists:menus,id_menu',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Cek kata tidak pantas
        if (BadWordHelper::checkOnline($request->comment)) {
            return redirect()->back()->with('error', 'Komentar mengandung kata tidak pantas!');
        }

        $user = Auth::user();

        // Validasi keamanan: pastikan user benar-benar membeli menu ini
        $order = Order::where('id_order', $request->id_order)
                      ->where('id_user', $user->id_user) 
                      ->where('status', 'completed')
                      ->first();

        if (!$order) {
            \Illuminate\Support\Facades\Log::error("Review Store: Pesanan tidak valid. ID: {$request->id_order}, User: {$user->id_user}");
            return redirect()->back()->with('error', 'Pesanan tidak valid.');
        }

        // Cek apakah menu ada di dalam pesanan
        $hasItem = OrderDetail::where('id_order', $order->id_order)
                              ->where('id_menu', $request->id_menu)
                              ->exists();
        
        if (!$hasItem) {
            \Illuminate\Support\Facades\Log::error("Review Store: Menu tidak ada di pesanan. Order: {$order->id_order}, Menu: {$request->id_menu}");
            return redirect()->back()->with('error', 'Anda tidak membeli menu ini.');
        }

        // Cek apakah sudah pernah mengulas
        $existing = Review::where('id_order', $request->id_order)
                          ->where('id_menu', $request->id_menu)
                          ->first();
        
        if ($existing) {
             return redirect()->back()->with('error', 'Anda sudah mengulas menu ini.');
        }

        Review::create([
            'id_user' => $user->id_user,
            'id_menu' => $request->id_menu,
            'id_order' => $request->id_order,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * Memperbarui ulasan pelanggan.
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('id_user', Auth::user()->id_user)->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        if (BadWordHelper::checkOnline($request->comment)) {
            return redirect()->back()->with('error', 'Komentar mengandung kata tidak pantas!');
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Menghapus ulasan pelanggan.
     */
    public function destroy($id)
    {
        $review = Review::where('id_user', Auth::user()->id_user)->findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }

    /**
     * Menampilkan daftar ulasan untuk tenant.
     */
    public function indexTenant(Request $request)
    {
        if (!Auth::user()->tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }
        $tenantId = Auth::user()->tenant->id_tenant;

        $reviews = Review::whereHas('menu', function($q) use ($tenantId) {
            $q->where('id_tenant', $tenantId);
        })
        ->with(['user', 'menu'])
        ->when($request->menu_name, function($q) use ($request) {
            $q->whereHas('menu', function($sq) use ($request) {
                $sq->where('name', 'like', '%'.$request->menu_name.'%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('tenant.reviews.index', compact('reviews'));
    }

    /**
     * Menampilkan semua ulasan untuk admin.
     */
    public function indexAdmin(Request $request)
    {
        $reviews = Review::with(['user', 'menu.tenant'])
            ->when($request->search, function($q) use ($request) {
                 $q->whereHas('menu', function($sq) use ($request) {
                     $sq->where('name', 'like', '%'.$request->search.'%');
                 })
                 ->orWhereHas('user', function($uq) use ($request) {
                     $uq->where('name', 'like', '%'.$request->search.'%');
                 });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Menghapus ulasan oleh admin.
     */
    public function destroyAdmin($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus oleh Admin.');
    }

    /**
     * Export semua ulasan ke CSV untuk admin.
     */
    public function exportAdmin()
    {
        $reviews = Review::with(['user', 'menu.tenant'])->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan_ulasan-" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Tanggal', 'Pelanggan', 'Kantin', 'Menu', 'Rating', 'Komentar']);
            
            foreach($reviews as $review) {
                fputcsv($file, [
                    $review->id_review,
                    $review->created_at->format('Y-m-d H:i'),
                    $review->user->name ?? 'User Dihapus',
                    $review->menu->tenant->name ?? 'Tenant Dihapus',
                    $review->menu->name ?? 'Menu Dihapus',
                    $review->rating,
                    $review->comment
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
