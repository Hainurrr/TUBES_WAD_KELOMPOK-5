<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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
        $orders = Order::with(['user', 'orderDetails.menu'])
            ->where('id_tenant', $tenantId)
            ->whereIn('status', ['pending', 'cooking', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        $tenant = Auth::user()->tenant;

        return view('tenant.orders.index', compact('orders', 'tenant'));
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

        return view('tenant.orders.history', compact('orders', 'totalRevenue'));
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
        
        return view('tenant.orders.pdf', compact('orders', 'totalRevenue', 'tenant', 'holidays'));
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
}
