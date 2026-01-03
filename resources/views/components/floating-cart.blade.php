@if(Auth::check() && Auth::user()->role === 'customer' && session('cart') && count(session('cart')) > 0)
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

    <!-- Floating Trigger Button -->
    <div class="fixed-bottom p-3" style="z-index: 1050; pointer-events: none;">
        <div class="container d-flex justify-content-end pointer-events-none">
            
            <!-- Cart Popup Widget (Hidden by default) -->
            <div id="cartPopup" class="bg-white rounded-4 shadow-lg mb-3 d-none position-absolute bottom-100 end-0 me-3" 
                 style="width: 380px; max-height: 80vh; overflow-y: auto; pointer-events: auto;">
                
                <!-- Header -->
                <div class="p-3 border-bottom sticky-top bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0">Pesanan Anda</h5>
                        <button onclick="toggleCart()" class="btn-close"></button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-3">
                    @foreach(session('cart') as $tenantId => $group)
                        <div class="mb-3">
                            <small class="text-secondary fw-bold text-uppercase border-bottom d-block mb-2 pb-1">{{ $group['tenant_name'] }}</small>
                            @foreach($group['items'] as $menuId => $item)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="flex-grow-1 me-2">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $item['name'] }}</h6>
                                        <div class="d-flex align-items-center mt-1">
                                            <a href="#" class="text-success text-decoration-none small fw-bold me-2"><i class="bi bi-pencil-fill me-1"></i> Edit</a>
                                            <a href="#" class="text-secondary text-decoration-none small fw-bold"><i class="bi bi-journal-text me-1"></i> Notes</a>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-outline-success btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                        </form>
                                        
                                        <span class="fw-bold small" style="min-width: 20px; text-align: center;">{{ $item['qty'] }}</span>

                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-outline-success btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="text-end ms-3" style="min-width: 70px;">
                                        <span class="small fw-medium">{{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="p-3 border-top bg-light sticky-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold">Total Harga</span>
                        <span class="fw-bold fs-5 text-dark">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="btn btn-success w-100 rounded-pill fw-bold py-2 text-decoration-none">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>

            <!-- Main Button -->
            <div onclick="toggleCart()" 
               class="d-flex align-items-center bg-success text-white rounded-pill shadow-lg p-2 text-decoration-none hover-scale cursor-pointer"
               style="pointer-events: auto; min-width: 200px; transition: transform 0.2s; cursor: pointer;"
               data-aos="fade-up" data-aos-offset="10">
                
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" 
                     style="width: 40px; height: 40px; flex-shrink: 0;">
                    <span class="fw-bold">{{ $totalQty }}</span>
                </div>

                <div class="flex-grow-1 me-3">
                    <span class="d-block small fw-bold opacity-75">Total Pesanan</span>
                    <span class="fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>

                <div class="me-2">
                    <i id="cartIcon" class="bi bi-cart-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCart() {
            const popup = document.getElementById('cartPopup');
            if (popup.classList.contains('d-none')) {
                popup.classList.remove('d-none');
                popup.classList.add('d-block');
                // Optional: Simple animation
                popup.style.opacity = '0';
                setTimeout(() => popup.style.opacity = '1', 50);
                popup.style.transition = 'opacity 0.2s';
            } else {
                popup.classList.add('d-none');
                popup.classList.remove('d-block');
            }
        }
    </script>
@endif
