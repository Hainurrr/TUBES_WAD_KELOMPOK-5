@extends('layouts.app')

@section('title', 'Menunggu Pembayaran — TelyuCan')

@push('head')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="tc-flow-wrap"
     data-snap-root
     data-snap-token="{{ $snapToken }}"
     data-redirect-url="{{ route('orders.history') }}">
    <div class="tc-card-dark p-4 p-md-5 text-center mb-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(245,158,11,0.18); color: #F59E0B;">
            <i class="bi bi-hourglass-split fs-2"></i>
        </div>
        <h1 class="tc-display h4 fw-bold text-white mb-2">Menunggu Pembayaran</h1>
        <p class="small mb-0 mx-auto tc-dark-copy" style="max-width: 42ch;">
            Selesaikan pembayaran agar tiket pesananmu segera masuk ke dapur kantin.
        </p>
    </div>

    <div class="tc-card p-3 p-sm-4 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-3 gap-2">
            <div class="min-w-0">
                <span class="text-muted small d-block">Kode Transaksi</span>
                <span class="tc-mono fw-bold text-dark text-break">{{ $transactionGroup }}</span>
            </div>
            <div class="text-sm-end">
                <span class="text-muted small d-block">Total Tagihan</span>
                <span class="tc-mono fw-bold fs-5 text-maroon">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>

        @foreach($orders as $order)
            <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
                <div class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-shop text-maroon"></i>{{ $order->tenant->name }}
                </div>
                @foreach($order->orderDetails as $detail)
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <div class="d-flex align-items-center gap-2">
                            <span class="tc-badge tc-badge-neutral tc-mono">{{ $detail->qty }}x</span>
                            <span class="text-secondary">{{ $detail->menu->name }}</span>
                        </div>
                        <span class="tc-mono fw-semibold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <div class="tc-card p-4 mb-4" style="background-color: var(--tc-surface-muted);">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-shield-check fs-4" style="color: var(--tc-emerald);"></i>
            <div>
                <div class="fw-bold small text-dark mb-1">Pembayaran Digital Terverifikasi</div>
                <p class="text-secondary small mb-0">
                    Selesaikan transaksi pada jendela pembayaran yang muncul untuk meneruskan pesanan ke dapur gerai.
                </p>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-2">
        <button type="button" data-snap-pay class="btn btn-tc-primary w-100 py-3">
            <i class="bi bi-wallet2 me-2"></i>Lanjutkan Pembayaran
        </button>
        <a href="{{ route('orders.history') }}" class="btn btn-tc-ghost w-100 py-3 text-decoration-none text-center">
            Pantau Status di Riwayat Pesanan
        </a>
    </div>
</div>
@endsection
