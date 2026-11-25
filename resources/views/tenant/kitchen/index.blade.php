@extends('layouts.tenant')

@section('title', 'Layar Dapur — Panel Tenant')

@section('content')
@php
    $pendingOrders = $orders->where('status', 'pending');
    $cookingOrders = $orders->where('status', 'cooking');
    $readyOrders = $orders->where('status', 'ready');
@endphp

<div class="container-fluid px-0"
     data-kds-root
     data-status-url-base="{{ url('tenant/orders') }}"
     data-tenant-toggle-url="{{ route('tenant.updateStatus') }}">
    <div class="tc-card-dark p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="tc-display h4 fw-bold text-white mb-1">Layar Dapur &amp; Pesanan Masuk</h1>
            <p class="small mb-0 tc-dark-copy">Menampilkan pesanan yang sudah terbayar. Halaman memuat ulang otomatis setiap 30 detik.</p>
        </div>

        <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-pill align-self-start align-self-md-auto" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
            <span class="small fw-semibold tc-dark-copy">Status Gerai</span>
            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                <input class="form-check-input m-0" type="checkbox" role="switch" id="tenantStatusSwitch" {{ $tenant->is_open ? 'checked' : '' }} style="width: 2.5rem; height: 1.35rem; cursor: pointer;">
                <label class="form-check-label fw-bold small mb-0" for="tenantStatusSwitch" id="tenantStatusLabel" style="color: {{ $tenant->is_open ? '#34D399' : '#FCA5A5' }};">
                    {{ $tenant->is_open ? 'Buka' : 'Tutup' }}
                </label>
            </div>
        </div>
    </div>

    <ul class="nav tc-kds-tabs mb-4" id="kitchenTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                <i class="bi bi-inbox"></i>
                <span>Pesanan Masuk</span>
                <span class="tc-badge tc-badge-crimson tc-mono" id="count-pending">{{ $pendingOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cooking-tab" data-bs-toggle="tab" data-bs-target="#cooking" type="button" role="tab" aria-controls="cooking" aria-selected="false">
                <i class="bi bi-fire"></i>
                <span>Sedang Dimasak</span>
                <span class="tc-badge tc-badge-amber tc-mono" id="count-cooking">{{ $cookingOrders->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ready-tab" data-bs-toggle="tab" data-bs-target="#ready" type="button" role="tab" aria-controls="ready" aria-selected="false">
                <i class="bi bi-bag-check"></i>
                <span>Siap Diambil</span>
                <span class="tc-badge tc-badge-emerald tc-mono" id="count-ready">{{ $readyOrders->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="kitchenTabsContent">
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="row g-3">
                @forelse($pendingOrders as $order)
                    @include('tenant.kitchen.partials.order-ticket', ['order' => $order, 'stage' => 'pending'])
                @empty
                    <div class="col-12">
                        <x-empty-state icon="bi-inbox" heading-class="h6" title="Belum ada pesanan masuk" description="Pesanan baru yang sudah dibayar akan otomatis tampil di sini." />
                    </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="cooking" role="tabpanel" aria-labelledby="cooking-tab">
            <div class="row g-3">
                @forelse($cookingOrders as $order)
                    @include('tenant.kitchen.partials.order-ticket', ['order' => $order, 'stage' => 'cooking'])
                @empty
                    <div class="col-12">
                        <x-empty-state icon="bi-fire" heading-class="h6" title="Tidak ada pesanan yang sedang dimasak" description="Pesanan yang Anda terima dari tab Pesanan Masuk akan muncul di sini." />
                    </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab">
            <div class="row g-3">
                @forelse($readyOrders as $order)
                    @include('tenant.kitchen.partials.order-ticket', ['order' => $order, 'stage' => 'ready'])
                @empty
                    <div class="col-12">
                        <x-empty-state icon="bi-bag-check" heading-class="h6" title="Belum ada pesanan yang siap diambil" description="Pesanan yang selesai dimasak akan menunggu pengambilan pelanggan di sini." />
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
