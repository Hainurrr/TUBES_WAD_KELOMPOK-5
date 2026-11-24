@if(Auth::check() && Auth::user()->role === 'customer' && session('cart') && count(session('cart')) > 0 && !request()->routeIs('cart.checkout') && !request()->routeIs('payment.page') && !request()->routeIs('cart.index'))
    @php
        $totalQty = 0;
        $totalPrice = 0;
        foreach(session('cart') as $tenant) {
            foreach($tenant['items'] as $item) {
                $totalQty += $item['qty'];
                $totalPrice += $item['price'] * $item['qty'];
            }
        }
    @endphp

    <div class="tc-floating-cart-wrap">
        {{-- Cart Popover --}}
        <div id="cartPopup" class="tc-cart-popover d-none">
            <div class="px-4 py-3 border-bottom sticky-top bg-white d-flex justify-content-between align-items-center">
                <h5 class="tc-display fw-bold mb-0 fs-6">Keranjang Pesanan</h5>
                <button type="button" onclick="toggleCart()" class="btn-close" aria-label="Tutup keranjang"></button>
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

                                <div class="d-inline-flex align-items-center gap-1 p-1 rounded-pill border bg-white flex-shrink-0">
                                    <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0" style="width: 24px; height: 24px; background: var(--tc-surface-muted);" aria-label="Kurangi">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                        <input type="hidden" name="action" value="set">
                                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1"
                                               class="form-control form-control-sm text-center fw-bold tc-mono p-0 border-0 bg-transparent shadow-none"
                                               style="width: 30px; height: 24px; font-size: 0.85rem;"
                                               onchange="this.form.submit()" aria-label="Jumlah {{ $item['name'] }}">
                                    </form>

                                    <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center border-0 text-white" style="width: 24px; height: 24px; background: var(--tc-crimson);" aria-label="Tambah">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </form>
                                </div>
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

        {{-- Floating Obsidian Trigger --}}
        <button type="button" onclick="toggleCart()" class="tc-cart-trigger" aria-expanded="false" aria-controls="cartPopup">
            <span class="tc-cart-count">{{ $totalQty }}</span>
            <span class="text-start me-2">
                <span class="d-block" style="font-size: 0.72rem; color: #CBD5E1; font-weight: 600;">Total Keranjang</span>
                <span class="tc-mono fw-bold" style="font-size: 0.95rem;">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </span>
            <i id="cartIcon" class="bi bi-bag-check fs-5"></i>
        </button>
    </div>

    <script>
        function toggleCart() {
            const popup = document.getElementById('cartPopup');
            const trigger = document.querySelector('.tc-cart-trigger');
            if (!popup) return;
            const isHidden = popup.classList.contains('d-none');
            popup.classList.toggle('d-none', !isHidden);
            if (trigger) trigger.setAttribute('aria-expanded', String(isHidden));
        }
    </script>
@endif
