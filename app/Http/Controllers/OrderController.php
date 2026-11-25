<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartRequest;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Models\Tenant;
use App\Services\CartService;
use App\Services\OrderCheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderCheckoutService $checkoutService
    ) {}

    public function catalog(Request $request): View
    {
        $menus = Menu::availableCatalog()
            ->filterCatalog($request->only(['category', 'q', 'menu_category', 'sort']))
            ->paginate(12)
            ->withQueryString();

        $categories = Tenant::distinctCategories();

        return view('menus.index', compact('menus', 'categories'));
    }

    public function index(Request $request): View
    {
        $menus = Menu::availableCatalog()
            ->filterCatalog($request->only(['category', 'q', 'menu_category']))
            ->take(8)
            ->get();

        $categories = Tenant::distinctCategories();

        return view('welcome', compact('menus', 'categories'));
    }

    public function showTenant(int $id): View
    {
        $tenant = Tenant::withReviewStats()->findOrFail($id);

        $menus = Menu::where('id_tenant', $tenant->id_tenant)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('is_available', true)
            ->get();

        $reviews = Review::forTenant($tenant->id_tenant)
            ->limit(10)
            ->get();

        return view('orders.tenant-show', compact('tenant', 'menus', 'reviews'));
    }

    public function addToCart(int $id): RedirectResponse
    {
        $result = $this->cartService->add($id);

        return redirect()->back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    public function updateCart(UpdateCartRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $result = $this->cartService->updateQuantity(
            (int) $validated['id_menu'],
            $validated['action'],
            isset($validated['qty']) ? (int) $validated['qty'] : null
        );

        return redirect()->back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    public function cart(): View
    {
        $cart = $this->cartService->all();

        return view('orders.cart-index', compact('cart'));
    }

    public function checkout(): View|RedirectResponse
    {
        $cart = $this->cartService->all();

        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Keranjang kosong!');
        }

        return view('orders.checkout', compact('cart'));
    }

    public function history(): View
    {
        $allOrders = Order::ownedBy((int) auth()->id())
            ->with(['tenant', 'orderDetails.menu', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activeOrders = $allOrders->whereIn('status', Order::ACTIVE_STATUSES);
        $pastOrders = $allOrders->whereIn('status', Order::PAST_STATUSES);

        return view('orders.history', compact('activeOrders', 'pastOrders'));
    }

    public function search(Request $request): View
    {
        $location = $request->input('location');
        $category = $request->input('category');

        $tenants = Tenant::withReviewStats()
            ->filterDirectory($category, $location)
            ->get();

        $searchLabel = $category ?: ($location ?: 'Semua Tenant');

        return view('orders.search-tenants', [
            'tenants' => $tenants,
            'location' => $searchLabel,
        ]);
    }

    public function print(int $id): View
    {
        $order = Order::with(['tenant', 'orderDetails.menu', 'user'])
            ->ownedBy((int) auth()->id())
            ->findOrFail($id);

        return view('orders.print', compact('order'));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->route('orders.history');
    }

    public function markAllRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function cancel(int $id): RedirectResponse
    {
        try {
            $result = $this->checkoutService->cancelPendingOrderForUser($id, (int) auth()->id());

            return redirect()->back()->with($result['ok'] ? 'success' : 'error', $result['message']);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }
}
