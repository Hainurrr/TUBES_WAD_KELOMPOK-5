{{-- MANPROSI_TELYUCAN_IMPROVEMENT - Halaman Checkout UI/UX Ala Aplikasi Pesan Antar --}}
@extends('layouts.app')

@push('head')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<style>
    /* Custom Styling for Food Delivery App UI */
    body {
        background-color: #f8f9fa !important;
    }
    
    .checkout-container {
        max-width: 540px; /* Mirip lebar tampilan mobile app di desktop */
        margin: 0 auto;
        padding-bottom: 120px; /* Space untuk sticky bottom bar */
    }

    .card-checkout {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        background: #ffffff;
    }

    .qty-badge {
        background-color: #fff3f3;
        color: #800000;
        border: 1px solid #ffcccc;
        font-weight: 700;
        min-width: 32px;
        text-align: center;
    }

    .sticky-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        z-index: 1030;
        padding: 16px 0;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .bottom-bar-content {
        max-width: 540px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .btn-pesan {
        background: linear-gradient(135deg, #800000 0%, #b30000 100%);
        color: white;
        border: none;
        transition: transform 0.2s;
    }
    .btn-pesan:hover {
        transform: translateY(-2px);
        color: white;
    }

    /* Modal Bottom Sheet Style */
    .modal.bottom-sheet .modal-dialog {
        display: flex;
        align-items: flex-end;
        min-height: 100%;
        margin: 0 auto;
        max-width: 540px;
    }
    .modal.bottom-sheet .modal-content {
        border-radius: 24px 24px 0 0;
        border: none;
        padding-bottom: 20px;
    }
    .modal.bottom-sheet.fade .modal-dialog {
        transform: translateY(100%);
    }
    .modal.bottom-sheet.show .modal-dialog {
        transform: translateY(0);
    }
    /* Hide number input arrows */
    input[type="number"]::-webkit-inner-spin-button, 
    input[type="number"]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type="number"] {
        -moz-appearance: textfield; /* Firefox */
    }
    
    /* Hide footer on checkout for app-like feel */
    footer {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="checkout-container pt-4 px-3">
    
    <!-- Top Header -->
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-light rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left fs-5 text-dark"></i>
        </a>
        <h4 class="fw-bold mb-0">Checkout</h4>
    </div>

    @if(session('error'))
        <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4">{{ session('error') }}</div>
    @endif

    <!-- Section 1: Lokasi Pengambilan (Delivery Info alternative) -->
    <div class="card-checkout p-4">
        <div class="d-flex align-items-start">
            <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3">
                <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-1">Lokasi Pengambilan (Dine-in/Takeaway)</h6>
                <p class="text-secondary small mb-0">
                    Kantin Telkom University<br>
                    <span class="text-muted">Tunjukkan bukti pembayaran ke kasir tenant saat mengambil pesanan.</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Section 2: Ringkasan Pesanan -->
    <div class="card-checkout p-4">
        <h6 class="fw-bold mb-4"><i class="bi bi-bag-check-fill text-maroon me-2"></i>Pesananmu</h6>
        
        @php $globalTotal = 0; @endphp
        @foreach($cart as $tenantId => $group)
            <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-4 mb-4' : '' }}">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light p-2 rounded-3 me-2 border">
                        <i class="bi bi-shop text-maroon"></i>
                    </div>
                    <span class="fw-bold fs-6">{{ $group['tenant_name'] }}</span>
                </div>
                
                @foreach($group['items'] as $menuId => $item)
                    @php 
                        $subtotal = $item['price'] * $item['qty'];
                        $globalTotal += $subtotal;
                    @endphp
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex flex-grow-1 align-items-start">
                            
                            {{-- MANPROSI_TELYUCAN_IMPROVEMENT - Fitur modif jumlah pesanan (+/- & input text) --}}
                            <div class="d-flex align-items-center bg-white border rounded-pill px-1 py-1 me-3 mt-1 shadow-sm">
                                <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="btn btn-sm p-0 text-secondary border-0" style="line-height:0;"><i class="bi bi-dash-circle-fill fs-5"></i></button>
                                </form>
                                
                                <form action="{{ route('cart.update') }}" method="POST" class="m-0 mx-1">
                                    @csrf
                                    <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                    <input type="hidden" name="action" value="set">
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" 
                                           class="form-control form-control-sm text-center fw-bold p-0 bg-transparent border-0 qty-input" 
                                           style="width: 25px; font-size: 0.9rem;" 
                                           onchange="this.form.submit()">
                                </form>

                                <form action="{{ route('cart.update') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_menu" value="{{ $menuId }}">
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="btn btn-sm p-0 text-maroon border-0" style="line-height:0;"><i class="bi bi-plus-circle-fill fs-5"></i></button>
                                </form>
                            </div>

                            <div>
                                <span class="d-block text-dark fw-bold mb-1">{{ $item['name'] }}</span>
                                <span class="text-secondary small"><i class="bi bi-shop me-1"></i>{{ $group['tenant_name'] }}</span>
                            </div>
                        </div>
                        <span class="fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Section 3: Metode Pembayaran -->
    <div class="card-checkout p-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                    <i class="bi bi-wallet2 text-primary fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Metode Pembayaran</h6>
                    <span class="text-secondary small">Midtrans (QRIS, E-Wallet, VA)</span>
                </div>
            </div>
            <i class="bi bi-check-circle-fill text-success fs-4 shadow-sm rounded-circle bg-white"></i>
        </div>
    </div>

    <!-- Section 4: Ringkasan Biaya -->
    <div class="card-checkout p-4">
        <h6 class="fw-bold mb-4"><i class="bi bi-receipt text-secondary me-2"></i>Ringkasan Pembayaran</h6>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Harga Makanan</span>
            <span class="fw-medium">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Biaya Layanan Aplikasi</span>
            <span class="text-success fw-bold">Gratis</span>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <span class="text-secondary">Biaya Penanganan</span>
            <span class="text-success fw-bold">Gratis</span>
        </div>
        
        <div class="border-top border-dashed pt-3 mt-2 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
            <span class="fw-bold fs-4 text-maroon">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
        </div>
    </div>

</div>

<!-- Sticky Bottom Action Bar -->
<div class="sticky-bottom-bar">
    <div class="bottom-bar-content d-flex justify-content-between align-items-center">
        <div>
            <span class="d-block text-secondary small fw-medium">Total Harga</span>
            <h3 class="fw-bold text-maroon mb-0">Rp {{ number_format($globalTotal, 0, ',', '.') }}</h3>
        </div>
        <button type="button" class="btn btn-pesan rounded-pill px-4 py-3 fw-bold fs-6 shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#confirmationModal">
            Pesan Sekarang <i class="bi bi-chevron-right ms-2"></i>
        </button>
    </div>
</div>

<!-- Hidden Form for Submission -->
<form action="{{ route('order.store') }}" method="POST" id="checkoutForm" class="d-none">
    @csrf
</form>

<!-- Confirmation Modal (Bottom Sheet Style) -->
<div class="modal bottom-sheet fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 justify-content-center pb-0 pt-4">
                <div style="width: 50px; height: 5px; background-color: #dee2e6; border-radius: 10px; position: absolute; top: 15px;"></div>
                <h5 class="fw-bold text-center mb-0 mt-3">Konfirmasi Pesanan</h5>
            </div>
            <div class="modal-body px-4 py-4 text-center">
                <div class="mb-4">
                    <i class="bi bi-question-circle text-maroon" style="font-size: 3rem;"></i>
                </div>
                <h6 class="fw-bold mb-2">Udah yakin sama pesananmu?</h6>
                <p class="text-secondary mb-4 small">
                    Pastikan pesanan sudah benar karena tidak bisa dibatalkan setelah dibayar. Kantin akan langsung memprosesnya!
                </p>
                
                <div class="d-flex flex-column gap-3">
                    <button type="button" onclick="submitOrder()" class="btn btn-pesan w-100 rounded-pill py-3 fw-bold fs-6" id="btnConfirmOrder">
                        <i class="bi bi-lock-fill me-2"></i>Ya, Pesan & Bayar
                    </button>
                    <button type="button" class="btn btn-light w-100 rounded-pill py-3 fw-bold text-dark border shadow-sm" data-bs-dismiss="modal">
                        Cek Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.9); z-index:9999; backdrop-filter: blur(5px);">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="text-center">
            <div class="spinner-border text-maroon mb-3" role="status" style="width: 3rem; height: 3rem; border-width: 0.3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="fw-bold text-dark">Mengamankan Pesanan...</h5>
            <p class="text-secondary small">Mengarahkan ke halaman pembayaran</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function submitOrder() {
        // Tutup modal dulu biar smooth
        var modalEl = document.getElementById('confirmationModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }

        // Tampilkan loading screen keren
        setTimeout(() => {
            document.getElementById('loadingOverlay').style.display = 'block';
            document.getElementById('btnConfirmOrder').disabled = true;
            
            // Submit form
            document.getElementById('checkoutForm').submit();
        }, 300); // delay dikit biar animasi tutup modal selesai
    }
</script>
@endpush
