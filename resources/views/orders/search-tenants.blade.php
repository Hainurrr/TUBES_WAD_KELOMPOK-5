@extends('layouts.app')

@section('title', 'Lokasi Kantin — TelyuCan')

@section('content')
<section class="tc-page-banner">
    <div class="container">
        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i>Kembali ke Beranda
        </a>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <div>
                <h1 class="mb-2" style="font-size: clamp(1.75rem, 3vw, 2.35rem);">Daftar Gerai Kantin</h1>
                <p class="mb-0" style="color: #CBD5E1;">Menampilkan hasil untuk: <strong class="text-white">{{ $location ?? 'Semua Lokasi' }}</strong></p>
            </div>
            <form action="{{ route('search') }}" method="GET" class="d-flex gap-2" style="max-width: 360px; width: 100%;">
                <input type="text" name="location" class="form-control bg-white border-0 rounded-pill px-4" placeholder="Cari TULT, GKU, Pujasera..." value="{{ request('location') }}">
                <button type="submit" class="btn btn-tc-primary px-4">Cari</button>
            </form>
        </div>
    </div>
</section>

<div class="container py-4 py-lg-5">
    @if($tenants->isEmpty())
        <div class="tc-card p-5 text-center">
            <i class="bi bi-shop-window fs-1 text-muted d-block mb-3"></i>
            <h2 class="tc-display h5 fw-bold mb-1">Tidak ada gerai ditemukan di lokasi ini</h2>
            <p class="text-secondary small mb-3">Coba tampilkan seluruh gerai kantin yang terdaftar di kampus.</p>
            <a href="{{ route('search') }}" class="btn btn-tc-primary btn-sm">Lihat Semua Gerai</a>
        </div>
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
                                    <span class="tc-badge tc-badge-dark">
                                        <i class="bi bi-star-fill" style="color: var(--tc-amber);"></i>
                                        <span class="tc-num">{{ $tenant->reviews_avg_rating ? number_format($tenant->reviews_avg_rating, 1) : '0.0' }}</span>
                                        <span class="opacity-75">({{ $tenant->reviews_count }})</span>
                                    </span>
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
@endsection
