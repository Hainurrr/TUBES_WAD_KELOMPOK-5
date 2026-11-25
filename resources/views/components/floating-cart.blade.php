@if(Auth::check() && Auth::user()->role === 'customer' && session('cart') && count(session('cart')) > 0 && !request()->routeIs('cart.checkout') && !request()->routeIs('payment.page') && !request()->routeIs('cart.index'))
    @php
        $totalQty = 0;
        $totalPrice = 0;
        foreach (session('cart') as $tenant) {
            foreach ($tenant['items'] as $item) {
                $totalQty += $item['qty'];
                $totalPrice += $item['price'] * $item['qty'];
            }
        }
    @endphp

    <div class="tc-floating-cart-wrap">
        <div id="cartPopup" class="tc-cart-popover d-none">
            <div class="px-4 py-3 border-bottom sticky-top bg-white d-flex justify-content-between align-items-center">
                <h5 class="tc-display fw-bold mb-0 fs-6">Keranjang Pesanan</h5>
                <button type="button" data-cart-toggle class="btn-close" aria-label="Tutup keranjang"></button>
            </div>

            <div class="p-3 px-4">
                @foreach(session('cart') as $tenantId => $group)
                    <div class="mb-3">
                        <div class="small fw-bold text-secondary border-bottom pb-1 mb-2 d-flex align-items-center gap-1">
                            <i class="bi bi-shop text-maroon"></i>{{ $group['tenant_name'] }}
                        </div>
                        @foreach($group['items'] as $menuId => $item)
                            <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold small text-dark text-truncate">{{ $item['name'] }}</div>
                                    <div class="tc-mono text-muted" style="font-size: 0.78rem;">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</div>
                                </div>

                                <x-qty-stepper :menu-id="$menuId" :qty="$item['qty']" :item-name="$item['name']" />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="p-3 px-4 border-top sticky-bottom" style="background-color: var(--tc-surface-muted);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold small text-secondary">Total Pesanan</span>
                    <span class="tc-mono fw-bold fs-6 text-dark">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('cart.checkout') }}" class="btn btn-tc-primary w-100 py-2 text-decoration-none text-center">
                    Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <button type="button" data-cart-toggle class="tc-cart-trigger" aria-expanded="false" aria-controls="cartPopup">
            <span class="tc-cart-count">{{ $totalQty }}</span>
            <span class="text-start me-2">
                <span class="d-block" style="font-size: 0.72rem; color: #CBD5E1; font-weight: 600;">Total Keranjang</span>
                <span class="tc-mono fw-bold" style="font-size: 0.95rem;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </span>
            <i id="cartIcon" class="bi bi-bag-check fs-5"></i>
        </button>
    </div>
@endif
