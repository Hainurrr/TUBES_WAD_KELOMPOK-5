<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderCheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderCheckoutService $checkoutService
    ) {}

    public function checkout(Request $request): RedirectResponse
    {
        $cart = $this->cartService->all();

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang kosong!');
        }

        try {
            $transactionGroup = $this->checkoutService->checkout($request->user(), $cart);
            $this->cartService->clear();

            return redirect()->route('payment.page', ['transactionGroup' => $transactionGroup]);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyiapkan pembayaran. Silakan coba lagi.');
        }
    }

    public function paymentPage(string $transactionGroup): View|RedirectResponse
    {
        $orders = Order::where('transaction_group', $transactionGroup)
            ->ownedBy((int) auth()->id())
            ->with(['tenant', 'orderDetails.menu'])
            ->get();

        $firstOrder = $orders->first();
        if (!$firstOrder || !$firstOrder->snap_token) {
            return redirect()->route('orders.history')->with('error', 'Token pembayaran tidak ditemukan.');
        }

        $grandTotal = $orders->sum('total_price');
        $snapToken = $firstOrder->snap_token;

        return view('orders.payment-pending', compact('orders', 'grandTotal', 'snapToken', 'transactionGroup'));
    }

    public function handleNotification(Request $request): JsonResponse
    {
        try {
            $result = $this->checkoutService->handleMidtransNotification($request->all());

            return response()->json(['message' => $result['message']], $result['status']);
        } catch (\Throwable $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
