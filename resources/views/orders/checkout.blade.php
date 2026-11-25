@extends('layouts.app')

@section('title', 'Checkout — TelyuCan')

@push('head')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="tc-flow-wrap">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('cart.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" style="width: 42px; height: 42px;" aria-label="Kembali ke keranjang">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h1 class="tc-display h4 fw-bold mb-0">Konfirmasi Checkout</h1>
            <p class="text-secondary small mb-0">Periksa kembali pesanan sebelum melakukan pembayaran digital.</p>
        </div>
    </div>

    <x-flash-alerts />

    <div class="tc-card-dark p-4 mb-3">
        <div class="d-flex align-items-start gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(185,17,46,0.22); color: #FCA5A5;">
                <i class="bi bi-geo-alt-fill fs-5"></i>
            </div>
            <div>
                <h2 class="tc-display h6 fw-bold text-white mb-1">Ambil Langsung di Konter (Dine-in / Takeaway)</h2>
                <p class="small mb-0 tc-dark-copy" style="line-height: 1.5;">
                    Kantin Telkom University. Tunjukkan bukti transaksi dari halaman Riwayat Pesanan saat mengambil hidangan di gerai tujuan.
                </p>
            </div>
        </div>
    </div>

    <div class="tc-card p-4 mb-3">
        <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-bag-check text-maroon"></i>Rincian Pesanan
        </h2>

        @php $globalTotal = 0; @endphp
        @foreach($cart as $tenantId => $group)
            <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="tc-badge tc-badge-neutral"><i class="bi bi-shop text-maroon"></i>{{ $group['tenant_name'] }}</span>
                </div>

                @foreach($group['items'] as $menuId => $item)
                    @php
                        $subtotal = $item['price'] * $item['qty'];
                        $globalTotal += $subtotal;
                    @endphp
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 gap-sm-3 mb-3">
                        <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0 flex-grow-1">
                            <x-qty-stepper :menu-id="$menuId" :qty="$item['qty']" :item-name="$item['name']" />

                            <div class="min-w-0">
                                <div class="fw-bold text-dark lh-sm">{{ $item['name'] }}</div>
                                <small class="text-muted tc-mono">Rp {{ number_format($item['price'], 0, ',', '.') }} / porsi</small>
                            </div>
                        </div>

                        <span class="fw-bold tc-mono text-dark flex-shrink-0 ms-auto">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="tc-card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: var(--tc-amber-soft); color: var(--tc-amber-ink);">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div>
                    <h2 class="tc-display h6 fw-bold mb-0">Pembayaran Non-Tunai</h2>
                    <span class="text-secondary small">Terverifikasi otomatis setelah transaksi selesai</span>
                </div>
            </div>
            <span class="tc-badge tc-badge-emerald"><i class="bi bi-check2"></i>Aktif</span>
        </div>
    </div>

    <div class="tc-card p-4">
        <h2 class="tc-display h6 fw-bold mb-3">Ringkasan Biaya</h2>
        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-secondary">Subtotal Makanan &amp; Minuman</span>
            <span class="tc-mono fw-semibold">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2 small">
            <span class="text-secondary">Biaya Layanan Aplikasi</span>
            <span class="fw-bold" style="color: var(--tc-emerald);">Gratis</span>
        </div>
        <div class="d-flex justify-content-between mb-3 small">
            <span class="text-secondary">Biaya Penanganan</span>
            <span class="fw-bold" style="color: var(--tc-emerald);">Gratis</span>
        </div>
        <div class="border-top pt-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark">Total Pembayaran</span>
            <span class="tc-mono fw-bold fs-4 text-maroon">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<div class="tc-sticky-bar">
    <div class="mx-auto px-3 d-flex flex-wrap justify-content-between align-items-center gap-2 gap-sm-3" style="max-width: 600px;">
        <div>
            <span class="d-block text-secondary small fw-semibold">Total Tagihan</span>
            <span class="tc-mono fw-bold text-dark" style="font-size: clamp(1.15rem, 4vw, 1.5rem);">Rp {{ number_format($globalTotal, 0, ',', '.') }}</span>
        </div>
        <button type="button" class="btn btn-tc-primary px-3 px-sm-4 py-2 py-sm-3" data-bs-toggle="modal" data-bs-target="#confirmationModal">
            Pesan Sekarang <i class="bi bi-chevron-right ms-1"></i>
        </button>
    </div>
</div>

<form action="{{ route('order.store') }}" method="POST" id="checkoutForm" class="d-none">
    @csrf
</form>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 26px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="tc-display fw-bold mb-0" id="confirmModalLabel">Konfirmasi Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-3">
                <p class="text-secondary small mb-4" style="line-height: 1.6;">
                    Pastikan menu dan jumlah pesanan sudah sesuai. Setelah pembayaran selesai, tiket pesanan langsung diteruskan ke dapur tenant dan tidak dapat dibatalkan.
                </p>
                <div class="d-flex flex-column gap-2">
                    <button type="button" data-checkout-confirm class="btn btn-tc-primary w-100 py-3" id="btnConfirmOrder">
                        <i class="bi bi-lock-fill me-2"></i>Ya, Lanjut ke Pembayaran
                    </button>
                    <button type="button" class="btn btn-tc-ghost w-100 py-2" data-bs-dismiss="modal">
                        Periksa Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="tc-loading-overlay">
    <div class="d-flex align-items-center justify-content-center h-100 px-3">
        <div class="tc-card p-4 p-md-5 text-center" style="max-width: 360px; width: 100%;">
            <div class="spinner-border text-maroon mb-3" role="status" style="width: 2.75rem; height: 2.75rem;">
                <span class="visually-hidden">Memuat...</span>
            </div>
            <h5 class="tc-display fw-bold mb-1">Mengamankan Pesanan</h5>
            <p class="text-secondary small mb-0">Menyiapkan halaman pembayaran...</p>
        </div>
    </div>
</div>
@endsection
