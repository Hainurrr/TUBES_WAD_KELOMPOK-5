@extends('layouts.tenant')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-maroon fw-bold mb-0">🧑‍🍳 Kitchen Display System</h2>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="tenantStatusSwitch" onchange="toggleTenantStatus(this)" {{ $tenant->is_open ? 'checked' : '' }}>
            <label class="form-check-label fw-bold" for="tenantStatusSwitch" id="tenantStatusLabel">
                {{ $tenant->is_open ? 'Buka' : 'Tutup' }}
            </label>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="kitchenTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-danger fw-bold" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                Pesanan Masuk
                <span class="badge bg-danger ms-2" id="count-pending">{{ $orders->where('status', 'pending')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-warning fw-bold" id="cooking-tab" data-bs-toggle="tab" data-bs-target="#cooking" type="button" role="tab" aria-controls="cooking" aria-selected="false">
                Sedang Dimasak
                <span class="badge bg-warning text-dark ms-2" id="count-cooking">{{ $orders->where('status', 'cooking')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-success fw-bold" id="ready-tab" data-bs-toggle="tab" data-bs-target="#ready" type="button" role="tab" aria-controls="ready" aria-selected="false">
                Siap Disajikan
                <span class="badge bg-success ms-2" id="count-ready">{{ $orders->where('status', 'ready')->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="kitchenTabsContent">
        <!-- Pending Orders -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'pending') as $order)
                    <div class="col-md-4 order-card" data-id="{{ $order->id_order }}">
                        <div class="card border-danger shadow-sm h-100">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <span class="fw-bold">#{{ $order->id_order }}</span>
                                <small>{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $order->user->name ?? 'Guest' }}</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach($order->orderDetails as $detail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            {{ $detail->menu->name }}
                                            <span class="badge bg-secondary rounded-pill">x{{ $detail->qty }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="alert alert-light border">
                                    Total: <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                </div>
                                <button onclick="updateStatus({{ $order->id_order }}, 'cooking')" class="btn btn-danger w-100">
                                    <i class="bi bi-fire"></i> Terima & Masak
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p>Belum ada pesanan masuk.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Cooking Orders -->
        <div class="tab-pane fade" id="cooking" role="tabpanel" aria-labelledby="cooking-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'cooking') as $order)
                    <div class="col-md-4 order-card" data-id="{{ $order->id_order }}">
                        <div class="card border-warning shadow-sm h-100">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <span class="fw-bold">#{{ $order->id_order }}</span>
                                <small>{{ $order->created_at->format('H:i') }}</small>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $order->user->name ?? 'Guest' }}</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach($order->orderDetails as $detail)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            {{ $detail->menu->name }}
                                            <span class="badge bg-secondary rounded-pill">x{{ $detail->qty }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <button onclick="updateStatus({{ $order->id_order }}, 'ready')" class="btn btn-warning w-100">
                                    <i class="bi bi-check-circle"></i> Siap Disajikan
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <p>Tidak ada pesanan yang sedang dimasak.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Ready Orders -->
        <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab">
            <div class="row g-3">
                @forelse($orders->where('status', 'ready') as $order)
                    <div class="col-md-4 order-card" data-id="{{ $order->id_order }}">
                        <div class="card border-success shadow-sm h-100">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <span class="fw-bold">#{{ $order->id_order }}</span>
                                <small>Siap Pick-up</small>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $order->user->name ?? 'Guest' }}</h6>
                                <div class="text-center my-3">
                                    <h1 class="display-6 fw-bold text-success">{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</h1>
                                    <p class="text-muted">Nomor Antrian</p>
                                </div>
                                <button onclick="updateStatus({{ $order->id_order }}, 'completed')" class="btn btn-success w-100">
                                    <i class="bi bi-check-all"></i> Selesaikan Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <p>Belum ada pesanan yang siap diambil.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    // Auto Refresh every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);

    // Update Status Function
    function updateStatus(orderId, newStatus) {
        if(!confirm('Ubah status pesanan ini?')) return;

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
                location.reload(); // Reload to update UI buckets
            } else {
                alert('Gagal mengupdate status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
    // Toggle Status Tenant
    function toggleTenantStatus(checkbox) {
        const isOpen = checkbox.checked ? 1 : 0;
        const label = document.getElementById('tenantStatusLabel');
        
        // Update UI secara optimis
        label.innerText = isOpen ? 'Buka' : 'Tutup';
        label.className = isOpen ? 'form-check-label fw-bold text-success' : 'form-check-label fw-bold text-danger';

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
            if(data.success) {
                // Status berhasil diubah
            } else {
                alert('Gagal mengubah status toko');
                checkbox.checked = !checkbox.checked; // Revert
            }
        })
        .catch(error => {
            console.error('Error:', error);
            checkbox.checked = !checkbox.checked; // Revert
        });
    }
</script>

<style>
    .text-maroon { color: #800000; }
    .nav-tabs .nav-link { color: #6c757d; }
    .nav-tabs .nav-link.active { background-color: transparent; border-bottom: 3px solid; }
    #pending-tab.active { border-color: #dc3545; color: #dc3545; }
    #cooking-tab.active { border-color: #ffc107; color: #ffca2c; }
    #ready-tab.active { border-color: #198754; color: #198754; }
</style>
@endsection
