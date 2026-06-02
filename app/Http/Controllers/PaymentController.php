<?php

namespace App\Http\Controllers;

// MANPROSI_TELYUCAN_IMPROVEMENT - PaymentController untuk integrasi Midtrans Snap API

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;

class PaymentController extends Controller
{
    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Inisialisasi konfigurasi Midtrans dari config/midtrans.php.
     * Dipanggil otomatis saat controller diinstansiasi.
     */
    public function __construct()
    {
        // MANPROSI_TELYUCAN_IMPROVEMENT - Require Midtrans SDK jika belum ter-autoload
        if (!class_exists('\Midtrans\Config')) {
            require_once base_path('vendor/midtrans/midtrans-php/Midtrans.php');
        }

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Memproses checkout: membuat record pesanan di database, lalu generate
     * Snap Token dari Midtrans untuk pembayaran online.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');

        if (!$cart || count($cart) == 0) {
            return redirect()->route('home')->with('error', 'Keranjang kosong!');
        }



        DB::beginTransaction();

        try {
            $transactionGroup = "TRX-" . strtoupper(Str::random(10));
            $grandTotal = 0;
            $orders = [];

            // MANPROSI_TELYUCAN_IMPROVEMENT - Buat order per tenant
            foreach ($cart as $tenantId => $group) {
                // Hitung total per tenant
                $tenantTotal = 0;
                foreach ($group['items'] as $menuId => $details) {
                    $tenantTotal += $details['price'] * $details['qty'];
                }
                $grandTotal += $tenantTotal;

                // Buat header pesanan (per tenant)
                $order = Order::create([
                    'id_user'        => auth()->id(),
                    'id_tenant'      => $tenantId,
                    'transaction_group' => $transactionGroup,
                    'total_price'    => $tenantTotal,
                    'status'         => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                $orders[] = $order;

                // Buat detail pesanan & kurangi stok
                foreach ($group['items'] as $menuId => $details) {
                    $menuDB = Menu::lockForUpdate()->find($menuId);

                    if ($menuDB->stock < $details['qty']) {
                        throw new \Exception("Stok untuk {$menuDB->name} tidak mencukupi!");
                    }

                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_menu'  => $menuId,
                        'qty'      => $details['qty'],
                        'subtotal' => $details['price'] * $details['qty'],
                    ]);

                    // Kurangi stok
                    $menuDB->stock -= $details['qty'];
                    $menuDB->save();
                }
            }

            // MANPROSI_TELYUCAN_IMPROVEMENT - Generate Snap Token dari Midtrans
            $user = auth()->user();

            // Parameter untuk Midtrans Snap API
            $midtransOrderId = $transactionGroup . '-' . time();
            $params = [
                'transaction_details' => [
                    'order_id'     => $midtransOrderId,
                    'gross_amount' => (int) $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                ],
                'item_details' => $this->buildItemDetails($cart),
            ];

            // Request Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan snap_token dan midtrans_transaction_id ke semua order dalam group
            foreach ($orders as $order) {
                $order->update([
                    'snap_token'              => $snapToken,
                    'midtrans_transaction_id' => $midtransOrderId,
                ]);
            }

            DB::commit();
            session()->forget('cart');

            // Redirect ke halaman pembayaran dengan Snap popup
            return redirect()->route('payment.page', ['transactionGroup' => $transactionGroup]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Menampilkan halaman pembayaran dengan Snap Token.
     * User bisa klik "Bayar Sekarang" untuk membuka popup Midtrans.
     *
     * @param  string  $transactionGroup
     * @return \Illuminate\Contracts\View\View
     */
    public function paymentPage($transactionGroup)
    {
        // Ambil order pertama dari group untuk mendapatkan snap_token
        $order = Order::where('transaction_group', $transactionGroup)
                      ->where('id_user', auth()->id())
                      ->first();

        if (!$order || !$order->snap_token) {
            return redirect()->route('orders.history')->with('error', 'Token pembayaran tidak ditemukan.');
        }

        $orders = Order::where('transaction_group', $transactionGroup)
                       ->where('id_user', auth()->id())
                       ->with(['tenant', 'orderDetails.menu'])
                       ->get();

        $grandTotal = $orders->sum('total_price');
        $snapToken  = $order->snap_token;

        return view('orders.payment-pending', compact('orders', 'grandTotal', 'snapToken', 'transactionGroup'));
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Menerima HTTP POST Notification dari server Midtrans (Webhook).
     * Endpoint ini harus didaftarkan di Dashboard Midtrans dan di-exclude dari CSRF.
     * Untuk testing lokal, gunakan Ngrok agar Midtrans bisa mengirim notifikasi.
     *
     * Alur:
     * 1. Terima payload JSON dari Midtrans
     * 2. Verifikasi signature key SHA512
     * 3. Update payment_status berdasarkan transaction_status
     * 4. Jika expired/cancel, kembalikan stok menu
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleNotification(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Midtrans Notification Received:', $payload);

            // MANPROSI_TELYUCAN_IMPROVEMENT - Verifikasi SHA512 Signature Key
            $orderId     = $payload['order_id'];
            $statusCode  = $payload['status_code'];
            $grossAmount = $payload['gross_amount'];
            $serverKey   = config('midtrans.server_key');

            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($expectedSignature !== $payload['signature_key']) {
                Log::warning('Midtrans Notification: Signature key mismatch!', [
                    'order_id' => $orderId,
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $transactionStatus = $payload['transaction_status'];
            $fraudStatus       = $payload['fraud_status'] ?? null;

            // Cari semua order dalam transaction group berdasarkan midtrans_transaction_id
            $orders = Order::where('midtrans_transaction_id', $orderId)->get();

            if ($orders->isEmpty()) {
                Log::warning('Midtrans Notification: Order not found', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // MANPROSI_TELYUCAN_IMPROVEMENT - Conditional logic berdasarkan transaction_status
            switch ($transactionStatus) {
                case 'capture':
                    // Untuk pembayaran kartu kredit
                    if ($fraudStatus == 'accept') {
                        $this->updatePaymentStatus($orders, 'paid');
                    } elseif ($fraudStatus == 'challenge') {
                        // Menunggu review manual di dashboard Midtrans
                        Log::info('Midtrans: Payment challenge for order ' . $orderId);
                    }
                    break;

                case 'settlement':
                    // Pembayaran berhasil (bank transfer, e-wallet, QRIS, dll)
                    $this->updatePaymentStatus($orders, 'paid');
                    break;

                case 'pending':
                    // Menunggu pembayaran — tidak perlu update, sudah 'unpaid'
                    Log::info('Midtrans: Payment pending for order ' . $orderId);
                    break;

                case 'expire':
                    // Pembayaran kedaluwarsa
                    $this->updatePaymentStatus($orders, 'expired');
                    $this->restoreStock($orders);
                    $this->cancelOrders($orders);
                    break;

                case 'cancel':
                case 'deny':
                    // Pembayaran dibatalkan atau ditolak
                    $this->updatePaymentStatus($orders, 'cancelled');
                    $this->restoreStock($orders);
                    $this->cancelOrders($orders);
                    break;

                default:
                    Log::info('Midtrans: Unhandled status ' . $transactionStatus . ' for order ' . $orderId);
                    break;
            }

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Helper: Update payment_status untuk semua order dalam transaction group.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $orders
     * @param  string  $status
     * @return void
     */
    private function updatePaymentStatus($orders, $status)
    {
        foreach ($orders as $order) {
            $order->update(['payment_status' => $status]);
        }
        Log::info('Payment status updated to: ' . $status . ' for ' . $orders->count() . ' orders');
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Helper: Kembalikan stok menu saat pembayaran expired atau dibatalkan.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $orders
     * @return void
     */
    private function restoreStock($orders)
    {
        foreach ($orders as $order) {
            $details = OrderDetail::where('id_order', $order->id_order)->get();
            foreach ($details as $detail) {
                $menu = Menu::find($detail->id_menu);
                if ($menu) {
                    $menu->increment('stock', $detail->qty);
                }
            }
        }
        Log::info('Stock restored for expired/cancelled orders');
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Helper: Set status order menjadi 'cancelled' saat pembayaran gagal.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $orders
     * @return void
     */
    private function cancelOrders($orders)
    {
        foreach ($orders as $order) {
            $order->update(['status' => 'cancelled']);
        }
    }

    /**
     * MANPROSI_TELYUCAN_IMPROVEMENT
     * Helper: Menyusun item_details array untuk parameter Midtrans.
     * Format sesuai spesifikasi Midtrans Snap API.
     *
     * @param  array  $cart
     * @return array
     */
    private function buildItemDetails($cart)
    {
        $items = [];
        foreach ($cart as $tenantId => $group) {
            foreach ($group['items'] as $menuId => $details) {
                $items[] = [
                    'id'       => (string) $menuId,
                    'price'    => (int) $details['price'],
                    'quantity' => (int) $details['qty'],
                    'name'     => substr($details['name'], 0, 50), // Midtrans max 50 chars
                ];
            }
        }
        return $items;
    }
}
