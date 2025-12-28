<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Menampilkan katalog menu lengkap dengan filter dan sorting.
     */
    public function catalog(Request $request)
    {
        $query = Menu::with('tenant')
                     ->withAvg('reviews', 'rating')
                     ->withCount('reviews')
                     ->where('is_available', true)
                     ->whereHas('tenant', function($q) {
                         $q->where('is_open', true);
                     });

        // Filter berdasarkan kategori tenant
        if($request->has('category') && $request->category != '') {
             $query->whereHas('tenant', function($q) use ($request) {
                 $q->where('category', $request->category);
             });
        }

        // Filter berdasarkan pencarian
        if($request->has('q') && $request->q != '') {
             $query->where('name', 'like', '%' . $request->q . '%')
                   ->orWhereHas('tenant', function($q) use ($request) {
                       $q->where('name', 'like', '%' . $request->q . '%');
                   });
        }

        // Filter berdasarkan kategori menu
        if($request->has('menu_category') && $request->menu_category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->menu_category);
            });
        }

        // Pengurutan data
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $query->orderByDesc('reviews_avg_rating');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $menus = $query->paginate(12)->withQueryString();
        
        $categories = Tenant::whereNotNull('category')->distinct()->pluck('category');
        
        return view('menus.index', compact('menus', 'categories'));
    }

    /**
     * Menampilkan halaman utama dengan menu rekomendasi.
     */
    public function index(Request $request)
    {
        $query = Menu::with('tenant')
                     ->withAvg('reviews', 'rating')
                     ->withCount('reviews')
                     ->where('is_available', true)
                     ->whereHas('tenant', function($q) {
                         $q->where('is_open', true);
                     });

        // Filter berdasarkan kategori tenant
        if($request->has('category') && $request->category != '') {
             $query->whereHas('tenant', function($q) use ($request) {
                 $q->where('category', $request->category);
             });
        }

        // Filter berdasarkan pencarian
        if($request->has('q') && $request->q != '') {
             $query->where('name', 'like', '%' . $request->q . '%')
                   ->orWhereHas('tenant', function($q) use ($request) {
                       $q->where('name', 'like', '%' . $request->q . '%');
                   });
        }

        // Filter berdasarkan kategori menu
        if($request->has('menu_category') && $request->menu_category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->menu_category);
            });
        }

        // Batasi 8 item untuk halaman utama
        $menus = $query->take(8)->get();
        
        $categories = Tenant::whereNotNull('category')->distinct()->pluck('category');
        
        return view('welcome', compact('menus', 'categories'));
    }

    /**
     * Menampilkan detail tenant beserta menu dan review.
     */
    public function showTenant($id)
    {
        $tenant = Tenant::withAvg('reviews', 'rating')->withCount('reviews')->findOrFail($id);
        $menus = Menu::where('id_tenant', $id)
                     ->withAvg('reviews', 'rating')
                     ->withCount('reviews')
                     ->where('is_available', true)
                     ->get();

        // Mengambil review terbaru untuk tenant ini
        $reviews = \App\Models\Review::whereHas('menu', function($q) use ($id) {
                        $q->where('id_tenant', $id);
                    })
                    ->with(['user', 'menu'])
                    ->latest()
                    ->limit(10)
                    ->get();

        return view('orders.tenant-show', compact('tenant', 'menus', 'reviews'));
    }

    /**
     * Menambahkan item ke keranjang belanja (berbasis session).
     */
    public function addToCart(Request $request, $id_menu)
    {
        $menu = Menu::with('tenant')->find($id_menu);

        if(!$menu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan!');
        }

        if($menu->stock < 1) {
            return redirect()->back()->with('error', 'Stok habis!');
        }

        if(!$menu->tenant->is_open) {
            return redirect()->back()->with('error', 'Maaf, Tenant ini sedang tutup.');
        }

        $cart = session()->get('cart', []);
        $tenantId = $menu->id_tenant;

        // Inisialisasi grup tenant jika belum ada
        if(!isset($cart[$tenantId])) {
            $cart[$tenantId] = [
                'tenant_name' => $menu->tenant->name,
                'items' => []
            ];
        }

        // Cek apakah item sudah ada di keranjang tenant
        if(isset($cart[$tenantId]['items'][$id_menu])) {
            $cart[$tenantId]['items'][$id_menu]['qty']++;
        } else {
            $cart[$tenantId]['items'][$id_menu] = [
                "name" => $menu->name,
                "price" => $menu->price,
                "qty" => 1,
                "image" => $menu->image,
                "stock" => $menu->stock
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    /**
     * Memperbarui jumlah item di keranjang.
     */
    public function updateCart(Request $request)
    {
        if($request->id_menu && $request->action) {
            $cart = session()->get('cart');
            $id = $request->id_menu;

            foreach($cart as $tenantId => &$tenant) {
                if(isset($tenant['items'][$id])) {
                    if($request->action == 'increase') {
                        $tenant['items'][$id]['qty']++;
                    } elseif($request->action == 'decrease') {
                        $tenant['items'][$id]['qty']--;
                        if($tenant['items'][$id]['qty'] < 1) {
                            unset($tenant['items'][$id]);
                        }
                    }
                    
                    // Bersihkan tenant jika kosong
                    if(empty($tenant['items'])) {
                        unset($cart[$tenantId]);
                    }
                    break; 
                }
            }
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Keranjang diperbarui!');
        }
    }

    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        return view('orders.cart-index', compact('cart'));
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart');

        if(!$cart || count($cart) == 0) {
            return redirect()->route('home')->with('error', 'Keranjang kosong!');
        }

        return view('orders.checkout', compact('cart'));
    }

    /**
     * Memproses dan menyimpan pesanan ke database.
     */
    public function storeOrder(Request $request)
    {
        // Validasi hari libur
        if($this->isHoliday()) {
            return redirect()->back()->with('error', 'Maaf, hari ini libur!');
        }

        $cart = session()->get('cart');

        if(!$cart) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            $transactionGroup = "TRX-" . strtoupper(Str::random(10));

            foreach($cart as $tenantId => $group) {
                
                // Hitung total per tenant
                $tenantTotal = 0;
                foreach($group['items'] as $menuId => $details) {
                    $tenantTotal += $details['price'] * $details['qty'];
                }

                // Buat header pesanan (per tenant)
                $order = Order::create([
                    'id_user' => auth()->id(), 
                    'id_tenant' => $tenantId,
                    'transaction_group' => $transactionGroup,
                    'total_price' => $tenantTotal,
                    'status' => 'pending'
                ]);

                // Buat detail pesanan
                foreach($group['items'] as $menuId => $details) {
                    // Cek ulang stok di database untuk keamanan
                    $menuDB = Menu::lockForUpdate()->find($menuId);
                    
                    if($menuDB->stock < $details['qty']) {
                        throw new \Exception("Stok untuk {$menuDB->name} tidak mencukupi!");
                    }

                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_menu'  => $menuId,
                        'qty'      => $details['qty'],
                        'subtotal' => $details['price'] * $details['qty']
                    ]);

                    // Kurangi stok
                    $menuDB->stock -= $details['qty'];
                    $menuDB->save();
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.history')->with('success', 'Transaksi berhasil! Kode: ' . $transactionGroup);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat pesanan pelanggan.
     */
    public function history()
    {
        $allOrders = Order::where('id_user', auth()->id())
                       ->with(['tenant', 'orderDetails.menu', 'reviews'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        $activeOrders = $allOrders->whereIn('status', ['pending', 'process', 'cooking', 'ready', 'shipping']);
        $pastOrders = $allOrders->whereIn('status', ['completed', 'cancelled']);
                       
        return view('orders.history', compact('activeOrders', 'pastOrders'));
    }

    /**
     * Mencari tenant berdasarkan lokasi atau kategori.
     */
    public function search(Request $request)
    {
        $location = $request->input('location');
        $category = $request->input('category');
        
        $query = Tenant::withAvg('reviews', 'rating')->withCount('reviews');

        $searchLabel = 'Semua Tenant';

        if ($category) {
            $query->where('category', $category);
            $searchLabel = $category;
        } elseif ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('address', 'like', "%{$location}%")
                  ->orWhere('name', 'like', "%{$location}%")
                  ->orWhere('city', 'like', "%{$location}%");
            });
            $searchLabel = $location;
        }

        $tenants = $query->get();

        return view('orders.search-tenants', [
            'tenants' => $tenants,
            'location' => $searchLabel
        ]);
    }

    /**
     * Menampilkan struk pesanan untuk dicetak.
     */
    public function print($id)
    {
        $order = Order::with(['tenant', 'orderDetails.menu', 'user'])->findOrFail($id);
        
        // Pastikan user adalah pemilik pesanan ini
        if($order->id_user != auth()->id()) {
            abort(403);
        }

        return view('orders.print', compact('order'));
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect()->route('orders.history');
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    /**
     * Membatalkan pesanan (hanya jika status masih pending).
     */
    public function cancel($id)
    {
        $order = Order::with('orderDetails')->where('id_user', auth()->id())->findOrFail($id);

        if($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok
            foreach($order->orderDetails as $detail) {
                $menu = Menu::find($detail->id_menu);
                if($menu) {
                    $menu->increment('stock', $detail->qty);
                }
            }

            $order->status = 'cancelled';
            $order->save();

            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    /**
     * Mengecek apakah hari ini adalah hari libur.
     */
    private function isHoliday() {
        return false;
    }
}
