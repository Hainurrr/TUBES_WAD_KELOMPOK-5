{{-- MANPROSI_TELYUCAN_IMPROVEMENT - Halaman Pembayaran Ala Aplikasi Mobile --}}
@extends('layouts.app')

@push('head')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<style>
    /* Custom Styling for Food Delivery App UI */
    body {
        background-color: #f8f9fa !important;
    }
    
    .payment-container {
        max-width: 540px; /* Lebar ala mobile app */
        margin: 0 auto;
        padding-bottom: 100px; /* Space untuk bottom button */
    }

    .card-payment {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 16px;
        background: #ffffff;
    }

    .status-icon-wrapper {
        width: 72px;
        height: 72px;
        background: rgba(255, 193, 7, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .status-icon-wrapper i {
        font-size: 2.5rem;
        color: #ffc107;
    }

    .btn-bayar {
        background: linear-gradient(135deg, #800000 0%, #b30000 100%);
        color: white;
        border: none;
        transition: transform 0.2s;
    }
    
    .btn-bayar:hover {
        transform: translateY(-2px);
        color: white;
    }

    /* Animasi shimmer */
    .btn-shimmer {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 2.5s infinite;
    }
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .qty-badge {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
        font-weight: 600;
        min-width: 28px;
        text-align: center;
    }

    /* Hide footer on checkout for app-like feel */
    footer {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="payment-container pt-4 px-3">
    
    <!-- Top Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold mb-0 text-center w-100">Status Pembayaran</h5>
    </div>

    <!-- Status Header -->
    <div class="card-payment p-4 text-center">
        <div class="status-icon-wrapper">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Menunggu Pembayaran</h4>
        <p class="text-secondary small mb-0">Selesaikan pembayaran agar kantin segera menyiapkan pesananmu.</p>
    </div>

    <!-- Detail Pesanan -->
    <div class="card-payment p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
            <div>
                <span class="text-secondary small d-block">ID Transaksi</span>
                <span class="fw-bold text-dark">{{ $transactionGroup }}</span>
            </div>
            <div class="text-end">
                <span class="text-secondary small d-block">Total Tagihan</span>
                <span class="fw-bold text-maroon fs-5">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>

        @foreach($orders as $order)
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-shop text-maroon me-2"></i>
                    <span class="fw-bold text-dark">{{ $order->tenant->name }}</span>
                </div>
                
                @foreach($order->orderDetails as $detail)
                    <div class="d-flex justify-content-between align-items-start mb-2 ms-4">
                        <div class="d-flex">
                            <span class="badge qty-badge me-2 mt-1">{{ $detail->qty }}x</span>
                            <span class="text-secondary">{{ $detail->menu->name }}</span>
                        </div>
                        <span class="fw-medium text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Info Box -->
    <div class="card-payment p-4 bg-light border border-info border-opacity-25 shadow-none">
        <div class="d-flex align-items-start">
            <i class="bi bi-shield-check text-success fs-4 me-3"></i>
            <div>
                <h6 class="fw-bold text-dark mb-1">Pembayaran Aman (Sandbox)</h6>
                <p class="text-secondary small mb-0">
                    Didukung oleh Midtrans. Kamu bisa membayar menggunakan QRIS, GoPay, Transfer Bank, dll.
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 gap-3 d-flex flex-column">
        <button type="button" onclick="payWithSnap()" class="btn btn-bayar w-100 rounded-pill py-3 fw-bold fs-6 shadow-sm position-relative overflow-hidden">
            <i class="bi bi-wallet2 me-2"></i>Lanjutkan Pembayaran
            <span class="btn-shimmer"></span>
        </button>

        <a href="{{ route('orders.history') }}" class="btn btn-light w-100 rounded-pill py-3 fw-bold text-dark border shadow-sm">
            Cek Status di Riwayat
        </a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function payWithSnap() {
        var snapToken = '{{ $snapToken }}';
        
        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('Payment Success:', result);
                showPaymentResult('success', 'Pembayaran Berhasil!', 'Kantin sedang menyiapkan pesananmu.');
                setTimeout(function() {
                    window.location.href = '{{ route("orders.history") }}';
                }, 2000);
            },
            onPending: function(result) {
                console.log('Payment Pending:', result);
                showPaymentResult('warning', 'Menunggu Pembayaran', 'Selesaikan pembayaran di aplikasi pilihanmu.');
            },
            onError: function(result) {
                console.log('Payment Error:', result);
                showPaymentResult('danger', 'Pembayaran Gagal', 'Silakan coba metode pembayaran lain.');
            },
            onClose: function() {
                console.log('Payment popup closed');
            }
        });
    }

    function showPaymentResult(type, title, message) {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show border-0 shadow-lg" role="alert" 
                 style="position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; width:90%; max-width:400px; border-radius:16px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : (type === 'warning' ? 'exclamation-triangle-fill' : 'x-circle-fill')} fs-3 me-3"></i>
                    <div>
                        <strong class="d-block">${title}</strong>
                        <span class="small">${message}</span>
                    </div>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            payWithSnap();
        }, 800); // Sedikit delay agar UI sempat terlihat jelas
    });
</script>
@endpush
