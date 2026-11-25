@php
    $paddedId = str_pad($order->id_order, 3, '0', STR_PAD_LEFT);
    $headerStyle = match ($stage) {
        'pending' => 'background-color: var(--tc-crimson); color: #FFFFFF;',
        'cooking' => 'background-color: var(--tc-amber); color: var(--tc-obsidian);',
        default => 'background-color: var(--tc-emerald); color: #FFFFFF;',
    };
@endphp

<div class="col-md-6 col-xl-4 order-card" data-id="{{ $order->id_order }}">
    <article class="tc-card h-100 d-flex flex-column overflow-hidden">
        <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="{{ $headerStyle }}">
            <span class="tc-mono fw-bold">#{{ $paddedId }}</span>
            @if($stage === 'pending')
                <span class="small">{{ $order->created_at->diffForHumans() }}</span>
            @elseif($stage === 'cooking')
                <span class="small fw-semibold tc-mono">{{ $order->created_at->format('H:i') }}</span>
            @else
                <span class="small fw-semibold">Siap Diambil</span>
            @endif
        </div>

        <div class="p-4 flex-grow-1 d-flex flex-column {{ $stage === 'ready' ? 'justify-content-between' : '' }}">
            @if($stage === 'ready')
                <div>
                    <h2 class="tc-display h6 fw-bold mb-3">{{ $order->user->name ?? 'Pelanggan' }}</h2>
                    <div class="text-center p-3 rounded-4 mb-4" style="background: var(--tc-emerald-soft);">
                        <div class="tc-mono fw-bold display-6 mb-0" style="color: var(--tc-emerald-ink);">{{ $paddedId }}</div>
                        <span class="small fw-semibold" style="color: var(--tc-emerald-ink);">Nomor Antrean Pengambilan</span>
                    </div>
                </div>
                <button type="button" data-order-id="{{ $order->id_order }}" data-order-status="completed" class="btn btn-tc-emerald w-100 py-2">
                    <i class="bi bi-check2-all me-1"></i>Selesaikan Pesanan
                </button>
            @else
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="tc-display h6 fw-bold mb-0">{{ $order->user->name ?? 'Pelanggan' }}</h2>
                    @if($stage === 'pending')
                        <span class="tc-badge tc-badge-emerald">Lunas</span>
                    @endif
                </div>

                <ul class="list-unstyled {{ $stage === 'pending' ? 'mb-3' : 'mb-4' }} flex-grow-1">
                    @foreach($order->orderDetails as $detail)
                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                            <span class="fw-medium">{{ $detail->menu->name }}</span>
                            <span class="tc-badge tc-badge-neutral tc-mono">x{{ $detail->qty }}</span>
                        </li>
                    @endforeach
                </ul>

                @if($stage === 'pending')
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3" style="background: var(--tc-surface-muted);">
                        <span class="small text-secondary fw-semibold">Total Pesanan</span>
                        <strong class="tc-mono text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" data-order-id="{{ $order->id_order }}" data-order-status="cancelled" class="btn btn-tc-ghost w-50 btn-sm py-2">
                            Batalkan
                        </button>
                        <button type="button" data-order-id="{{ $order->id_order }}" data-order-status="cooking" class="btn btn-tc-primary w-50 btn-sm py-2">
                            <i class="bi bi-fire me-1"></i>Terima &amp; Masak
                        </button>
                    </div>
                @else
                    <button type="button" data-order-id="{{ $order->id_order }}" data-order-status="ready" class="btn btn-tc-dark w-100 py-2">
                        <i class="bi bi-check2-circle me-1"></i>Tandai Siap Diambil
                    </button>
                @endif
            @endif
        </div>
    </article>
</div>
