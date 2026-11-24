@extends('layouts.tenant')

@section('title', 'Layar Dapur — Panel Tenant')

@section('content')
<div class="container-fluid px-0">
    {{-- Header & Store Status Toggle --}}
    <div class="tc-card-dark p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="tc-display h4 fw-bold text-white mb-1">Layar Dapur &amp; Pesanan Masuk</h1>
            <p class="small mb-0" style="color: #CBD5E1;">Menampilkan pesanan yang sudah terbayar via Midtrans. Halaman memuat ulang otomatis setiap 30 detik.</p>
        </div>

        <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-pill" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
            <span class="small fw-semibold" style="color: #CBD5E1;">Status Gerai</span>
            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                <input class="form-check-input m-0" type="checkbox" role="switch" id="tenantStatusSwitch" onchange="toggleTenantStatus(this)" {{ $tenant->is_open ? 'checked' : '' }} style="width: 2.5rem; height: 1.35rem; cursor: pointer;">
                <label class="form-check-label fw-bold small mb-0" for="tenantStatusSwitch" id="tenantStatusLabel" style="color: {{ $tenant->is_open ? '#34D399' : '#FCA5A5' }};">
                    {{ $tenant->is_open ? 'Buka' : 'Tutup' }}
                </label>
            </div>
        </div>
    </div>

    {{-- Kitchen Stage Tabs --}}
    <ul class="nav tc-kds-tabs mb-4" id="kitchenTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                <i class="bi bi-inbox"></i>
                <span>Pesanan Masuk</span>
                <span class="tc-badge tc-badge-crimson tc-mono" id="count-pending">{{ $orders->where('status', 'pending')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cooking-tab" data-bs-toggle="tab" data-bs-target="#cooking" type="button" role="tab" aria-controls="cooking" aria-selected="false">
                <i class="bi bi-fire"></i>
                <span>Sedang Dimasak</span>
                <span class="tc-badge tc-badge-amber tc-mono" id="count-cooking">{{ $orders->where('status', 'cooking')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ready-tab" data-bs-toggle="tab" data-bs-target="#ready" type="button" role="tab" aria-controls="ready" aria-selected="false">
                <i class="bi bi-bag-check"></i>
                <span>Siap Diambil</span>
                <span class="tc-badge tc-badge-emerald tc-mono" id="count-ready">{{ $orders->where('status', 'ready')->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="kitchenTabsContent">
        {{-- Pending Orders --}}
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'pending') as $order)
                    <div class="col-md-6 col-xl-4 order-card" data-id="{{ $order->id_order }}">
                        <article class="tc-card h-100 d-flex flex-column overflow-hidden">
                            <div class="px-4 py-3 d-flex justify-content-between align-items-center text-white" style="background-color: var(--tc-crimson);">
                                <span class="tc-mono fw-bold">#{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="small">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h2 class="tc-display h6 fw-bold mb-0">{{ $order->user->name ?? 'Pelanggan' }}</h2>
                                    <span class="tc-badge tc-badge-emerald">Lunas</span>
                                </div>
                                <ul class="list-unstyled mb-3 flex-grow-1">
                                    @foreach($order->orderDetails as $detail)
                                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                                            <span class="fw-medium">{{ $detail->menu->name }}</span>
                                            <span class="tc-badge tc-badge-neutral tc-mono">x{{ $detail->qty }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="d-flex justify-content-between align-items-center p-3 rounded-3 mb-3" style="background: var(--tc-surface-muted);">
                                    <span class="small text-secondary fw-semibold">Total Pesanan</span>
                                    <strong class="tc-mono text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" onclick="updateStatus({{ $order->id_order }}, 'cancelled')" class="btn btn-tc-ghost w-50 btn-sm py-2">
                                        Batalkan
                                    </button>
                                    <button type="button" onclick="updateStatus({{ $order->id_order }}, 'cooking')" class="btn btn-tc-primary w-50 btn-sm py-2">
                                        <i class="bi bi-fire me-1"></i>Terima &amp; Masak
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="tc-card p-5 text-center">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <h3 class="tc-display h6 fw-bold mb-1">Belum ada pesanan masuk</h3>
                            <p class="text-secondary small mb-0">Pesanan baru yang sudah dibayar akan otomatis tampil di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Cooking Orders --}}
        <div class="tab-pane fade" id="cooking" role="tabpanel" aria-labelledby="cooking-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'cooking') as $order)
                    <div class="col-md-6 col-xl-4 order-card" data-id="{{ $order->id_order }}">
                        <article class="tc-card h-100 d-flex flex-column overflow-hidden">
                            <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="background-color: var(--tc-amber); color: var(--tc-obsidian);">
                                <span class="tc-mono fw-bold">#{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="small fw-semibold tc-mono">{{ $order->created_at->format('H:i') }}</span>
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column">
                                <h2 class="tc-display h6 fw-bold mb-3">{{ $order->user->name ?? 'Pelanggan' }}</h2>
                                <ul class="list-unstyled mb-4 flex-grow-1">
                                    @foreach($order->orderDetails as $detail)
                                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom small">
                                            <span class="fw-medium">{{ $detail->menu->name }}</span>
                                            <span class="tc-badge tc-badge-neutral tc-mono">x{{ $detail->qty }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <button type="button" onclick="updateStatus({{ $order->id_order }}, 'ready')" class="btn btn-tc-dark w-100 py-2">
                                    <i class="bi bi-check2-circle me-1"></i>Tandai Siap Diambil
                                </button>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="tc-card p-5 text-center">
                            <i class="bi bi-fire fs-1 text-muted d-block mb-2"></i>
                            <h3 class="tc-display h6 fw-bold mb-1">Tidak ada pesanan yang sedang dimasak</h3>
                            <p class="text-secondary small mb-0">Pesanan yang Anda terima dari tab Pesanan Masuk akan muncul di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Ready Orders --}}
        <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'ready') as $order)
                    <div class="col-md-6 col-xl-4 order-card" data-id="{{ $order->id_order }}">
                        <article class="tc-card h-100 d-flex flex-column overflow-hidden">
                            <div class="px-4 py-3 d-flex justify-content-between align-items-center text-white" style="background-color: var(--tc-emerald);">
                                <span class="tc-mono fw-bold">#{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="small fw-semibold">Siap Diambil</span>
                            </div>
                            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <h2 class="tc-display h6 fw-bold mb-3">{{ $order->user->name ?? 'Pelanggan' }}</h2>
                                    <div class="text-center p-3 rounded-4 mb-4" style="background: var(--tc-emerald-soft);">
                                        <div class="tc-mono fw-bold display-6 mb-0" style="color: var(--tc-emerald-ink);">{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</div>
                                        <span class="small fw-semibold" style="color: var(--tc-emerald-ink);">Nomor Antrean Pengambilan</span>
                                    </div>
                                </div>
                                <button type="button" onclick="updateStatus({{ $order->id_order }}, 'completed')" class="btn btn-tc-emerald w-100 py-2">
                                    <i class="bi bi-check2-all me-1"></i>Selesaikan Pesanan
                                </button>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="tc-card p-5 text-center">
                            <i class="bi bi-bag-check fs-1 text-muted d-block mb-2"></i>
                            <h3 class="tc-display h6 fw-bold mb-1">Belum ada pesanan yang siap diambil</h3>
                            <p class="text-secondary small mb-0">Pesanan yang selesai dimasak akan menunggu pengambilan pelanggan di sini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    setInterval(function() {
        location.reload();
    }, 30000);

    function updateStatus(orderId, newStatus) {
        if (!confirm('Ubah status pesanan ini?')) return;

        fetch(`{{ url('tenant/orders') }}/${orderId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memperbarui status pesanan.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan sistem saat memperbarui status.');
        });
    }

    function toggleTenantStatus(checkbox) {
        const isOpen = checkbox.checked ? 1 : 0;
        const label = document.getElementById('tenantStatusLabel');

        label.innerText = isOpen ? 'Buka' : 'Tutup';
        label.style.color = isOpen ? '#34D399' : '#FCA5A5';

        fetch('{{ route("tenant.updateStatus") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_open: isOpen })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Gagal mengubah status gerai.');
                checkbox.checked = !checkbox.checked;
            }
        })
        .catch(() => {
            checkbox.checked = !checkbox.checked;
        });
    }
</script>
@endpush
@endsection
