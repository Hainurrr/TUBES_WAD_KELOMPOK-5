@extends('layouts.app')

@section('title', 'Lokasi Kantin — TelyuCan')

@section('content')
<x-page-banner title="Daftar Gerai Kantin" :back-url="route('home')">
    <x-slot:subtitleSlot>
        Menampilkan hasil untuk: <strong class="text-white">{{ $location ?? 'Semua Lokasi' }}</strong>
    </x-slot:subtitleSlot>
    <x-slot:actions>
        <form action="{{ route('search') }}" method="GET" class="d-flex gap-2" style="max-width: 360px; width: 100%;">
            <input type="text" name="location" class="form-control bg-white border-0 rounded-pill px-4 flex-grow-1 min-w-0" placeholder="Cari TULT, GKU, Pujasera..." value="{{ request('location') }}">
            <button type="submit" class="btn btn-tc-primary px-4">Cari</button>
        </form>
    </x-slot:actions>
</x-page-banner>

<section class="tc-operate-sheet">
    <div class="container">
        @if($tenants->isEmpty())
            <x-empty-state
                icon="bi-shop-window"
                heading-tag="h2"
                title="Tidak ada gerai ditemukan di lokasi ini"
                description="Coba tampilkan seluruh gerai kantin yang terdaftar di kampus."
                :action-url="route('search')"
                action-label="Lihat Semua Gerai"
            />
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($tenants as $tenant)
                    <div class="col">
                        <a href="{{ route('tenant.show', $tenant->id_tenant) }}" class="text-decoration-none text-dark d-block h-100">
                            <article class="tc-menu-card">
                                <div class="position-relative d-flex align-items-end justify-content-between p-4" style="height: 168px; background-color: var(--tc-obsidian); overflow: hidden;">
                                    @if(!empty($tenant->banner_image))
                                        <img src="{{ \Illuminate\Support\Str::startsWith($tenant->banner_image, 'http') ? $tenant->banner_image : asset('storage/' . $tenant->banner_image) }}"
                                             alt="{{ $tenant->name }}"
                                             class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="opacity: 0.55;">
                                    @endif
                                    <div class="position-relative z-1 d-flex justify-content-between align-items-center w-100">
                                        <span class="tc-badge {{ $tenant->is_open ? 'tc-badge-emerald-solid' : 'tc-badge-danger' }}">
                                            <span class="tc-dot {{ $tenant->is_open ? 'bg-white' : '' }}" style="{{ $tenant->is_open ? '' : 'background: var(--tc-danger);' }}"></span>
                                            {{ $tenant->is_open ? 'Buka' : 'Tutup' }}
                                        </span>
                                        @if($tenant->reviews_count > 0)
                                            <span class="tc-badge tc-badge-dark">
                                                <i class="bi bi-star-fill" style="color: var(--tc-amber);"></i>
                                                <span class="tc-num">{{ number_format($tenant->reviews_avg_rating, 1) }}</span>
                                                <span class="opacity-75">({{ $tenant->reviews_count }})</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tc-menu-body">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <h2 class="tc-menu-title mb-0" style="font-size: 1.2rem;">{{ $tenant->name }}</h2>
                                        @if($tenant->category)
                                            <span class="tc-badge tc-badge-crimson flex-shrink-0">{{ $tenant->category }}</span>
                                        @endif
                                    </div>

                                    <p class="tc-menu-desc">{{ $tenant->description ?: 'Gerai kantin resmi di lingkungan Telkom University.' }}</p>

                                    <div class="tc-menu-foot">
                                        <span class="small text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $tenant->address ?? 'Area Kampus' }}</span>
                                        <span class="fw-bold small text-maroon">Lihat Menu <i class="bi bi-arrow-right ms-1"></i></span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
