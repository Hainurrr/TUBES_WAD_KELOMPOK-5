@extends('layouts.app')

@section('title', 'Menunggu Pembayaran — TelyuCan')

@push('head')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="tc-flow-wrap">
    <div class="tc-card-dark p-4 p-md-5 text-center mb-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: rgba(245,158,11,0.18); color: #F59E0B;">
            <i class="bi bi-hourglass-split fs-2"></i>
        </div>
        <h1 class="tc-display h4 fw-bold text-white mb-2">Menunggu Pembayaran</h1>
        <p class="small mb-0 mx-auto" style="color: #CBD5E1; max-width: 42ch;">
            Selesaikan pembayaran melalui Midtrans agar tiket pesananmu segera masuk ke dapur kantin.
        </p>
    </div>

    <div class="tc-card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 gap-2">
            <div>
                <span class="text-muted small d-block">Kode Transaksi</span>
                <span class="tc-mono fw-bold text-dark">{{ $transactionGroup }}</span>
            </div>
            <div class="text-end">
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
                <div class="fw-bold small text-dark mb-1">Pembayaran Terintegrasi Midtrans {{ config('midtrans.is_production') ? '' : '(Mode Sandbox)' }}</div>
                <p class="text-secondary small mb-0">
                    Gunakan QRIS, GoPay, E-Wallet, atau Virtual Account pada jendela pembayaran Midtrans Snap.
                </p>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-2">
        <button type="button" onclick="payWithSnap()" class="btn btn-tc-primary w-100 py-3">
            <i class="bi bi-wallet2 me-2"></i>Lanjutkan Pembayaran
        </button>
        <a href="{{ route('orders.history') }}" class="btn btn-tc-ghost w-100 py-3 text-decoration-none text-center">
            Pantau Status di Riwayat Pesanan
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
                showPaymentResult('success', 'Pembayaran Berhasil', 'Kantin segera menyiapkan pesananmu.');
                setTimeout(function() {
                    window.location.href = '{{ route("orders.history") }}';
                }, 1800);
            },
            onPending: function(result) {
                showPaymentResult('warning', 'Menunggu Pembayaran', 'Selesaikan pembayaran di aplikasi pilihanmu.');
            },
            onError: function(result) {
                showPaymentResult('danger', 'Pembayaran Gagal', 'Silakan coba kembali dengan metode pembayaran lain.');
            },
            onClose: function() {}
        });
    }

    function showPaymentResult(type, title, message) {
        var icon = type === 'success' ? 'check-circle-fill' : (type === 'warning' ? 'exclamation-triangle-fill' : 'x-circle-fill');
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show border-0 shadow-lg" role="alert"
                 style="position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; width:92%; max-width:420px; border-radius:18px;">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-${icon} fs-4"></i>
                    <div>
                        <strong class="d-block">${title}</strong>
                        <span class="small">${message}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
    }

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() { payWithSnap(); }, 600);
    });
</script>
@endpush
