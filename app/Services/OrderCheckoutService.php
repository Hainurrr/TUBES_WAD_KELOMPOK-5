<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class OrderCheckoutService
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    public function checkout(User $user, array $cart): string
    {
        if (empty($cart)) {
            throw new RuntimeException('Keranjang belanja kosong.');
        }

        return DB::transaction(function () use ($user, $cart) {
            $transactionGroup = 'TRX-' . strtoupper(Str::random(10));
            $grandTotal = 0;
            $orders = [];
            $midtransItems = [];

            foreach ($cart as $tenantId => $group) {
                $items = $group['items'] ?? [];
                if (empty($items)) {
                    continue;
                }

                $lockedItems = [];
                $tenantTotal = 0;

                foreach ($items as $menuId => $details) {
                    $qty = (int) ($details['qty'] ?? 0);
                    if ($qty < 1) {
                        throw new RuntimeException('Jumlah pesanan tidak valid.');
                    }

                    $menu = Menu::with('tenant')->lockForUpdate()->find($menuId);
                    if (!$menu || !$menu->is_available) {
                        throw new RuntimeException('Salah satu menu di keranjang sudah tidak tersedia.');
                    }

                    if ((int) $menu->id_tenant !== (int) $tenantId) {
                        throw new RuntimeException('Data gerai untuk menu tidak valid.');
                    }

                    if (!$menu->tenant || !$menu->tenant->is_open) {
                        throw new RuntimeException("Gerai {$menu->tenant?->name} sedang tutup.");
                    }

                    if ($menu->stock < $qty) {
                        throw new RuntimeException("Stok untuk {$menu->name} tidak mencukupi (tersisa {$menu->stock}).");
                    }

                    // Harga selalu dihitung dari DB agar tidak bergantung pada session
                    $unitPrice = (int) $menu->price;
                    $subtotal = $unitPrice * $qty;
                    $tenantTotal += $subtotal;

                    $lockedItems[] = [
                        'menu' => $menu,
                        'qty' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ];

                    $midtransItems[] = [
                        'id' => (string) $menu->id_menu,
                        'price' => $unitPrice,
                        'quantity' => $qty,
                        'name' => mb_substr($menu->name, 0, 50),
                    ];
                }

                if (empty($lockedItems)) {
                    continue;
                }

                $grandTotal += $tenantTotal;

                $order = Order::create([
                    'id_user' => $user->id_user,
                    'id_tenant' => (int) $tenantId,
                    'transaction_group' => $transactionGroup,
                    'total_price' => $tenantTotal,
                    'status' => Order::STATUS_PENDING,
                    'payment_status' => Order::PAYMENT_UNPAID,
                ]);

                foreach ($lockedItems as $entry) {
                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_menu' => $entry['menu']->id_menu,
                        'qty' => $entry['qty'],
                        'subtotal' => $entry['subtotal'],
                    ]);

                    $entry['menu']->decrement('stock', $entry['qty']);
                }

                $orders[] = $order;
            }

            if (empty($orders) || $grandTotal <= 0) {
                throw new RuntimeException('Tidak ada item valid untuk diproses.');
            }

            $midtransOrderId = $transactionGroup . '-' . time();
            $snapToken = $this->requestSnapToken([
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => $midtransItems,
            ]);

            foreach ($orders as $order) {
                $order->update([
                    'snap_token' => $snapToken,
                    'midtrans_transaction_id' => $midtransOrderId,
                ]);
            }

            return $transactionGroup;
        });
    }

    public function handleMidtransNotification(array $payload): array
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = $payload['fraud_status'] ?? null;

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signatureKey === '' || $transactionStatus === '') {
            return ['status' => 400, 'message' => 'Invalid notification payload'];
        }

        $serverKey = (string) config('midtrans.server_key', '');
        if ($serverKey === '') {
            Log::error('Midtrans Notification: server key is not configured.');
            return ['status' => 500, 'message' => 'Payment gateway misconfigured'];
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans Notification: Signature key mismatch', ['order_id' => $orderId]);
            return ['status' => 403, 'message' => 'Invalid signature'];
        }

        Log::info('Midtrans Notification Verified', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'status_code' => $statusCode,
        ]);

        return DB::transaction(function () use ($orderId, $grossAmount, $transactionStatus, $fraudStatus) {
            $orders = Order::with('orderDetails')
                ->where('midtrans_transaction_id', $orderId)
                ->lockForUpdate()
                ->get();

            if ($orders->isEmpty()) {
                Log::warning('Midtrans Notification: Order not found', ['order_id' => $orderId]);
                return ['status' => 404, 'message' => 'Order not found'];
            }

            $expectedTotal = (float) $orders->sum('total_price');
            if (abs($expectedTotal - (float) $grossAmount) > 0.01) {
                Log::warning('Midtrans Notification: Gross amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $expectedTotal,
                    'received' => $grossAmount,
                ]);
                return ['status' => 400, 'message' => 'Amount mismatch'];
            }

            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus === 'accept') {
                        $this->markOrdersPaid($orders);
                    }
                    break;

                case 'settlement':
                    $this->markOrdersPaid($orders);
                    break;

                case 'expire':
                    $this->expireOrCancelOrders($orders, Order::PAYMENT_EXPIRED);
                    break;

                case 'cancel':
                case 'deny':
                    $this->expireOrCancelOrders($orders, Order::PAYMENT_CANCELLED);
                    break;

                case 'pending':
                default:
                    break;
            }

            return ['status' => 200, 'message' => 'Notification handled successfully'];
        });
    }

    public function cancelPendingOrderForUser(int $orderId, int $userId): array
    {
        return DB::transaction(function () use ($orderId, $userId) {
            $order = Order::with('orderDetails')
                ->ownedBy($userId)
                ->lockForUpdate()
                ->findOrFail($orderId);

            if ($order->status !== Order::STATUS_PENDING) {
                return ['ok' => false, 'message' => 'Pesanan tidak dapat dibatalkan karena sudah diproses.'];
            }

            $this->restoreStockForOrder($order);

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'payment_status' => Order::PAYMENT_CANCELLED,
            ]);

            return ['ok' => true, 'message' => 'Pesanan berhasil dibatalkan.'];
        });
    }

    public function updateKitchenOrderStatus(int $tenantId, int $orderId, string $newStatus): array
    {
        return DB::transaction(function () use ($tenantId, $orderId, $newStatus) {
            $order = Order::with(['orderDetails', 'user', 'tenant'])
                ->where('id_tenant', $tenantId)
                ->lockForUpdate()
                ->findOrFail($orderId);

            if (!$order->isPaid()) {
                return ['ok' => false, 'status' => 422, 'message' => 'Pesanan belum dibayar dan tidak dapat diproses.'];
            }

            if ($order->isTerminal()) {
                return ['ok' => false, 'status' => 422, 'message' => 'Status pesanan final tidak dapat diubah kembali.'];
            }

            if ($newStatus === Order::STATUS_CANCELLED) {
                $this->restoreStockForOrder($order);
                $order->payment_status = Order::PAYMENT_CANCELLED;
            }

            $order->status = $newStatus;
            $order->save();

            if ($order->user) {
                $order->user->notify(new OrderStatusUpdated($order));
            }

            return ['ok' => true, 'status' => 200, 'message' => 'Status berhasil diperbarui.'];
        });
    }

    protected function requestSnapToken(array $params): string
    {
        return \Midtrans\Snap::getSnapToken($params);
    }

    private function configureMidtrans(): void
    {
        if (!class_exists('\Midtrans\Config')) {
            $sdkPath = base_path('vendor/midtrans/midtrans-php/Midtrans.php');
            if (file_exists($sdkPath)) {
                require_once $sdkPath;
            }
        }

        if (class_exists('\Midtrans\Config')) {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds');
        }
    }

    private function markOrdersPaid(Collection $orders): void
    {
        foreach ($orders as $order) {
            if ($order->isPaid() || $order->isTerminal()) {
                continue;
            }
            $order->update(['payment_status' => Order::PAYMENT_PAID]);
        }
    }

    private function expireOrCancelOrders(Collection $orders, string $paymentStatus): void
    {
        foreach ($orders as $order) {
            // Idempoten: cegah pengembalian stok ganda pada webhook berulang
            if ($order->isPaid() || $order->isTerminal()) {
                continue;
            }

            $this->restoreStockForOrder($order);
            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'payment_status' => $paymentStatus,
            ]);
        }
    }

    private function restoreStockForOrder(Order $order): void
    {
        foreach ($order->orderDetails as $detail) {
            Menu::where('id_menu', $detail->id_menu)->increment('stock', (int) $detail->qty);
        }
    }
}
