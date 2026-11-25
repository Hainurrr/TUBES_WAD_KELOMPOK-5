<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreManualOrderRequest;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\HolidayService;
use App\Services\OrderCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TenantOrderController extends Controller
{
    public function __construct(
        private readonly OrderCheckoutService $checkoutService,
        private readonly HolidayService $holidayService
    ) {}

    public function index(): View|RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }

        $orders = Order::forKitchen($tenant->id_tenant)->get();

        return view('tenant.kitchen.index', compact('orders', 'tenant'));
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant tidak ditemukan.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,cooking,ready,completed,cancelled'],
        ]);

        $result = $this->checkoutService->updateKitchenOrderStatus(
            $tenant->id_tenant,
            $id,
            $validated['status']
        );

        return response()->json([
            'success' => $result['ok'],
            'message' => $result['message'],
        ], $result['status']);
    }

    public function history(Request $request): View|RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }

        $orders = Order::completedForTenant(
            $tenant->id_tenant,
            $request->input('start_date'),
            $request->input('end_date')
        )->get();

        $totalRevenue = $orders->sum('total_price');
        $menus = Menu::where('id_tenant', $tenant->id_tenant)
            ->where('is_available', true)
            ->get();

        return view('tenant.kitchen.history', compact('orders', 'totalRevenue', 'menus'));
    }

    public function exportPdf(Request $request): View|RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }

        $orders = Order::completedForTenant(
            $tenant->id_tenant,
            $request->input('start_date'),
            $request->input('end_date')
        )->get();

        $totalRevenue = $orders->sum('total_price');
        $holidays = $this->holidayService->getNationalHolidays();

        return view('tenant.kitchen.pdf', compact('orders', 'totalRevenue', 'tenant', 'holidays'));
    }

    public function updateTenantStatus(Request $request): JsonResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant tidak ditemukan.'], 403);
        }

        $request->validate([
            'is_open' => ['required', 'boolean'],
        ]);

        $tenant->is_open = $request->boolean('is_open');
        $tenant->save();

        return response()->json(['success' => true, 'message' => 'Status toko berhasil diperbarui.']);
    }

    public function storeManualHistory(StoreManualOrderRequest $request): RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->back()->with('error', 'Tenant tidak ditemukan.');
        }

        $validated = $request->validated();
        $tenantId = $tenant->id_tenant;

        try {
            DB::transaction(function () use ($validated, $tenantId) {
                $totalPrice = 0;
                $orderSequence = Order::where('id_tenant', $tenantId)->count() + 1;
                $transactionGroup = 'TRX-' . $tenantId . '-' . time() . '-' . $orderSequence;

                $order = new Order();
                $order->id_user = null;
                $order->id_tenant = $tenantId;
                $order->transaction_group = $transactionGroup;
                $order->status = Order::STATUS_COMPLETED;
                $order->payment_status = Order::PAYMENT_PAID;
                $order->total_price = 0;
                $order->created_at = $validated['transaction_date'] . ' ' . date('H:i:s');
                $order->updated_at = now();
                $order->save();

                foreach ($validated['items'] as $item) {
                    // Cegah cross-tenant IDOR: pastikan menu milik tenant yang sedang login
                    $menu = Menu::where('id_tenant', $tenantId)->findOrFail($item['id_menu']);
                    $qty = (int) $item['qty'];
                    $subtotal = $menu->price * $qty;
                    $totalPrice += $subtotal;

                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_menu' => $menu->id_menu,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->total_price = $totalPrice;
                $order->save();
            });

            return redirect()->back()->with('success', 'Riwayat pesanan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan. Pastikan semua menu milik gerai Anda.');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $order = Order::where('id_tenant', $tenant->id_tenant)->findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}
