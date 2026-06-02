<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenantOrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan masuk untuk dapur tenant.
     */
    public function index()
    {
        if (!Auth::user()->tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }
        $tenantId = Auth::user()->tenant->id_tenant;

        // Ambil pesanan yang relevan untuk dapur
        // MANPROSI_TELYUCAN_IMPROVEMENT - Hanya tampilkan pesanan yang sudah dibayar (paid)
        $orders = Order::with(['user', 'orderDetails.menu'])
            ->where('id_tenant', $tenantId)
            ->whereIn('status', ['pending', 'cooking', 'ready'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc')
            ->get();

        $tenant = Auth::user()->tenant;

        return view('tenant.kitchen.index', compact('orders', 'tenant'));
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant tidak ditemukan.'], 403);
        }
        $tenantId = Auth::user()->tenant->id_tenant;
        $order = Order::where('id_tenant', $tenantId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,cooking,ready,completed,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        // Kirim notifikasi ke pelanggan
        $order->user->notify(new \App\Notifications\OrderStatusUpdated($order));

        return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
    }

    /**
     * Menampilkan riwayat penjualan tenant dengan filter tanggal.
     */
    public function history(Request $request)
    {
        if (!Auth::user()->tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }
        $tenantId = Auth::user()->tenant->id_tenant;
        
        $query = Order::where('id_tenant', $tenantId)
                      ->where('status', 'completed');

        // Filter berdasarkan tanggal mulai
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $totalRevenue = $orders->sum('total_price');

        // Ambil menu untuk dropdown tambah manual
        $menus = Menu::where('id_tenant', $tenantId)->where('is_available', true)->get();

        return view('tenant.kitchen.history', compact('orders', 'totalRevenue', 'menus'));
    }

    /**
     * Export laporan penjualan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        if (!Auth::user()->tenant) {
            return redirect()->route('home')->with('error', 'Akun Anda tidak terhubung dengan Tenant manapun.');
        }
        $tenantId = Auth::user()->tenant->id_tenant;
        
        $query = Order::where('id_tenant', $tenantId)
                      ->where('status', 'completed');

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();
        $totalRevenue = $orders->sum('total_price');
        $tenant = Auth::user()->tenant;

        // Ambil Data Hari Libur (Sisi Server) untuk PDF
        $holidays = [];
        try {
            $year = date('Y'); // Gunakan tahun saat ini
            // Gunakan timeout 3 detik agar tidak loading lama jika API bermasalah
            $response = Http::timeout(3)->get("https://api-harilibur.vercel.app/api?year={$year}");
            
            if ($response->successful()) {
                foreach ($response->json() as $h) {
                    // Logika normalisasi tanggal (sama seperti JS: YYYY-MM-DD)
                    // Kita gunakan fungsi date() untuk memastikan padding (contoh: 2026-1-1 menjadi 2026-01-01)
                    $dateKey = date('Y-m-d', strtotime($h['holiday_date']));
                    $holidays[$dateKey] = $h['holiday_name'];
                }
            }
        } catch (\Exception $e) {
            // Abaikan error API saat generate PDF agar fungsi utama tetap jalan
        }
        
        return view('tenant.kitchen.pdf', compact('orders', 'totalRevenue', 'tenant', 'holidays'));
    }

    /**
     * Toggle status buka/tutup toko tenant.
     */
    public function updateTenantStatus(Request $request)
    {
        if (!Auth::user()->tenant) {
            return response()->json(['success' => false, 'message' => 'Tenant tidak ditemukan.'], 403);
        }

        $tenant = Auth::user()->tenant;
        $tenant->is_open = $request->is_open;
        $tenant->save();

        return response()->json(['success' => true, 'message' => 'Status toko berhasil diperbarui.']);
    }

    /**
     * Menyimpan pesanan manual (Walk-in) ke riwayat.
     */
    public function storeManualHistory(Request $request)
    {
        if (!Auth::user()->tenant) {
            return redirect()->back()->with('error', 'Tenant tidak ditemukan.');
        }

        $request->validate([
            'transaction_date' => 'required|date',
            'items' => 'required|array',
            'items.*.id_menu' => 'required|exists:menus,id_menu',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $tenantId = Auth::user()->tenant->id_tenant;
        
        DB::beginTransaction();
        try {
            // Hitung total harga
            $totalPrice = 0;
            $orderStart = Order::where('id_tenant', $tenantId)->count() + 1;
            $transactionGroup = 'TRX-' . $tenantId . '-' . time() . '-' . $orderStart;

            // Buat Order (Tanpa User / Walk-in)
            $order = new Order();
            $order->id_user = null; // Walk-in
            $order->id_tenant = $tenantId;
            $order->transaction_group = $transactionGroup;
            $order->status = 'completed'; // Langsung selesai
            $order->payment_status = 'paid'; // MANPROSI_TELYUCAN_IMPROVEMENT - Walk-in sudah dibayar
            $order->total_price = 0; // Update nanti
            $order->created_at = $request->transaction_date . ' ' . date('H:i:s'); // Pakai jam sekarang
            $order->updated_at = now();
            $order->save();

            foreach ($request->items as $item) {
                $menu = Menu::find($item['id_menu']);
                $subtotal = $menu->price * $item['qty'];
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'id_order' => $order->id_order,
                    'id_menu' => $menu->id_menu,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->total_price = $totalPrice;
            $order->save();

            DB::commit();
            return redirect()->back()->with('success', 'Riwayat pesanan berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus riwayat pesanan.
     */
    public function destroy($id)
    {
        if (!Auth::user()->tenant) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }
        
        $tenantId = Auth::user()->tenant->id_tenant;
        $order = Order::where('id_tenant', $tenantId)->findOrFail($id);
        
        $order->delete();

        return redirect()->back()->with('success', 'Riwayat pesanan berhasil dihapus.');
    }
}
