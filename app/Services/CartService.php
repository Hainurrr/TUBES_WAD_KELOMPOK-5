<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function all(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function add(int $menuId): array
    {
        $menu = Menu::with('tenant')->find($menuId);

        if (!$menu || !$menu->is_available) {
            return ['ok' => false, 'message' => 'Menu tidak ditemukan atau tidak tersedia.'];
        }

        if ($menu->stock < 1) {
            return ['ok' => false, 'message' => 'Stok menu ini sudah habis.'];
        }

        if (!$menu->tenant || !$menu->tenant->is_open) {
            return ['ok' => false, 'message' => 'Maaf, gerai kantin ini sedang tutup.'];
        }

        $cart = $this->all();
        $tenantId = $menu->id_tenant;

        if (!isset($cart[$tenantId])) {
            $cart[$tenantId] = [
                'tenant_name' => $menu->tenant->name,
                'items' => [],
            ];
        }

        $currentQty = (int) ($cart[$tenantId]['items'][$menuId]['qty'] ?? 0);
        if ($currentQty + 1 > $menu->stock) {
            return ['ok' => false, 'message' => "Jumlah melebihi stok tersedia ({$menu->stock})."];
        }

        $cart[$tenantId]['items'][$menuId] = [
            'name' => $menu->name,
            'price' => $menu->price,
            'qty' => $currentQty + 1,
            'image' => $menu->image,
            'stock' => $menu->stock,
        ];

        Session::put(self::SESSION_KEY, $cart);

        return ['ok' => true, 'message' => 'Berhasil ditambahkan ke keranjang.'];
    }

    public function updateQuantity(int $menuId, string $action, ?int $qty = null): array
    {
        $cart = $this->all();
        if (empty($cart)) {
            return ['ok' => false, 'message' => 'Keranjang masih kosong.'];
        }

        $menu = Menu::find($menuId);
        $maxStock = $menu ? max(0, (int) $menu->stock) : 0;

        foreach ($cart as $tenantId => &$tenant) {
            if (!isset($tenant['items'][$menuId])) {
                continue;
            }

            $currentQty = (int) $tenant['items'][$menuId]['qty'];

            if ($action === 'increase') {
                if ($maxStock > 0 && $currentQty + 1 > $maxStock) {
                    return ['ok' => false, 'message' => "Stok tersisa hanya {$maxStock} porsi."];
                }
                $tenant['items'][$menuId]['qty'] = $currentQty + 1;
            } elseif ($action === 'decrease') {
                $nextQty = $currentQty - 1;
                if ($nextQty < 1) {
                    unset($tenant['items'][$menuId]);
                } else {
                    $tenant['items'][$menuId]['qty'] = $nextQty;
                }
            } elseif ($action === 'set') {
                $desired = (int) $qty;
                if ($desired < 1) {
                    unset($tenant['items'][$menuId]);
                } elseif ($maxStock > 0 && $desired > $maxStock) {
                    $tenant['items'][$menuId]['qty'] = $maxStock;
                    Session::put(self::SESSION_KEY, $cart);
                    return ['ok' => false, 'message' => "Jumlah disesuaikan ke stok maksimum ({$maxStock})."];
                } else {
                    $tenant['items'][$menuId]['qty'] = $desired;
                }
            }

            if (isset($tenant['items'][$menuId]) && $menu) {
                $tenant['items'][$menuId]['price'] = $menu->price;
                $tenant['items'][$menuId]['stock'] = $menu->stock;
            }

            if (empty($tenant['items'])) {
                unset($cart[$tenantId]);
            }

            break;
        }
        unset($tenant);

        Session::put(self::SESSION_KEY, $cart);

        return ['ok' => true, 'message' => 'Keranjang diperbarui.'];
    }
}
