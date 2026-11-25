@php
    $isUnpaid = $order->payment_status == 'unpaid';
    $stepWidth = $order->status == 'ready' ? '100%' : ($order->status == 'cooking' ? '50%' : '0%');

    $stageHeading = match (true) {
        $isUnpaid => 'Menunggu Pembayaran',
        $order->status === 'pending' => 'Menunggu Konfirmasi Dapur',
        $order->status === 'cooking' => 'Sedang Dimasak',
        $order->status === 'ready' => 'Siap Diambil di Konter',
        default => 'Sedang Diproses',
    };

    [$badgeClass, $badgeText] = match (true) {
        $isUnpaid => ['tc-badge-amber', 'Belum Dibayar'],
        $order->status === 'ready' => ['tc-badge-emerald-solid', 'Tunjukkan Bukti di Konter'],
        $order->status === 'cooking' => ['tc-badge-amber', 'Dapur Sedang Menyiapkan'],
        default => ['tc-badge-neutral', 'Terbayar · Antre Konfirmasi'],
    };
@endphp

<article class="tc-card h-100 d-flex flex-column overflow-hidden">
    <div class="px-3 px-sm-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2" style="background-color: var(--tc-obsidian); color: #FFFFFF;">
        <div class="d-flex align-items-center gap-2 min-w-0">
            <i class="bi bi-shop flex-shrink-0" style="color: #FCA5A5;"></i>
            <span class="fw-bold text-truncate">{{ $order->tenant->name }}</span>
        </div>
        <span class="tc-mono small text-break" style="color: #CBD5E1;">#{{ $order->transaction_group }}</span>
    </div>

    <div class="p-3 p-sm-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
            <div>
                <div class="tc-display h5 fw-bold mb-1">{{ $stageHeading }}</div>
                <span class="tc-badge {{ $badgeClass }}">{{ $badgeText }}</span>
            </div>
            <div class="text-end flex-shrink-0">
                <span class="text-muted small d-block">No. Pesanan</span>
                <span class="tc-mono fw-bold fs-5">{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <ul class="list-unstyled mb-4 p-3 rounded-3 small" style="background-color: var(--tc-surface-muted);">
            @foreach($order->orderDetails as $detail)
                <li class="d-flex justify-content-between align-items-baseline gap-2 {{ !$loop->last ? 'mb-1' : '' }}">
                    <span class="min-w-0"><strong class="tc-mono">{{ $detail->qty }}x</strong> {{ $detail->menu->name ?? 'Menu' }}</span>
                    <span class="tc-mono text-secondary flex-shrink-0">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </li>
            @endforeach
        </ul>

        <div class="position-relative d-flex justify-content-between align-items-center text-center px-2 mb-1">
            <div class="position-absolute start-0 end-0" style="top: 13px; height: 3px; background: var(--tc-border); z-index: 0;"></div>
            <div class="position-absolute start-0" style="top: 13px; height: 3px; background: var(--tc-emerald); width: {{ $isUnpaid ? '0%' : $stepWidth }}; z-index: 0;"></div>

            <div class="position-relative z-1">
                <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center text-white"
                     style="width: 28px; height: 28px; background: {{ !$isUnpaid ? 'var(--tc-emerald)' : 'var(--tc-border)' }};">
                    <i class="bi bi-receipt small"></i>
                </div>
                <span class="d-block fw-semibold" style="font-size: 0.75rem;">Pesanan</span>
            </div>
            <div class="position-relative z-1">
                <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center text-white"
                     style="width: 28px; height: 28px; background: {{ in_array($order->status, ['cooking', 'ready']) ? 'var(--tc-emerald)' : 'var(--tc-border)' }};">
                    <i class="bi bi-fire small"></i>
                </div>
                <span class="d-block fw-semibold" style="font-size: 0.75rem;">Dimasak</span>
            </div>
            <div class="position-relative z-1">
                <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center text-white"
                     style="width: 28px; height: 28px; background: {{ $order->status == 'ready' ? 'var(--tc-emerald)' : 'var(--tc-border)' }};">
                    <i class="bi bi-bag-check small"></i>
                </div>
                <span class="d-block fw-semibold" style="font-size: 0.75rem;">Siap Ambil</span>
            </div>
        </div>
    </div>

    <div class="px-4 py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2 bg-white">
        <div>
            <span class="text-muted small d-block">Total</span>
            <span class="tc-mono fw-bold fs-6 text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($isUnpaid && $order->snap_token)
                <a href="{{ route('payment.page', ['transactionGroup' => $order->transaction_group]) }}" class="btn btn-tc-primary btn-sm px-3">
                    <i class="bi bi-wallet2 me-1"></i>Bayar Sekarang
                </a>
            @endif
            @if($order->status == 'pending')
                <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">Batalkan</button>
                </form>
            @endif
            <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-tc-ghost btn-sm px-3 text-decoration-none">
                <i class="bi bi-printer me-1"></i>Cetak Bukti
            </a>
        </div>
    </div>
</article>
