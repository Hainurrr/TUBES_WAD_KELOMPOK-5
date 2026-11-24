@extends('layouts.app')

@section('title', $tenant->name . ' — TelyuCan')

@section('content')
<section class="tc-page-banner position-relative overflow-hidden">
    @if(!empty($tenant->banner_image))
        <img src="{{ \Illuminate\Support\Str::startsWith($tenant->banner_image, 'http') ? $tenant->banner_image : asset('storage/' . $tenant->banner_image) }}"
             alt="" aria-hidden="true"
             class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="opacity: 0.22;">
    @endif
    <div class="container position-relative z-1">
        <a href="{{ route('search') }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-4">
            <i class="bi bi-arrow-left"></i>Kembali ke Lokasi Kantin
        </a>

        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3 gap-md-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=B9112E&color=fff&size=128"
                     alt="{{ $tenant->name }}"
                     class="rounded-4 object-fit-cover flex-shrink-0" style="border: 2px solid rgba(255,255,255,0.2);" width="84" height="84">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        @if($tenant->is_open)
                            <span class="tc-badge tc-badge-emerald-solid"><span class="tc-dot bg-white"></span>Sedang Buka</span>
                        @else
                            <span class="tc-badge tc-badge-danger"><i class="bi bi-slash-circle"></i>Sedang Tutup</span>
                        @endif
                        @if($tenant->category)
                            <span class="tc-badge" style="background: rgba(255,255,255,0.12); color: #F8FAFC;">
                                <i class="bi bi-geo-alt"></i>{{ $tenant->category }}
                            </span>
                        @endif
                    </div>
                    <h1 class="mb-1" style="font-size: clamp(1.7rem, 3vw, 2.4rem);">{{ $tenant->name }}</h1>
                    <p class="mb-0" style="color: #CBD5E1;">{{ $tenant->description ?: 'Layanan pesan dan ambil langsung di konter.' }}</p>
                </div>
            </div>

            <div class="tc-bento-tile p-3 px-4 flex-shrink-0" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); border-radius: 20px;">
                <div class="small" style="color: #CBD5E1;">Rating Pelanggan</div>
                <div class="d-flex align-items-baseline gap-2 mt-1">
                    <i class="bi bi-star-fill" style="color: var(--tc-amber);"></i>
                    <span class="tc-display fw-bold fs-3 text-white tc-num">{{ $tenant->reviews_avg_rating ? number_format($tenant->reviews_avg_rating, 1) : '0.0' }}</span>
                    <span class="small" style="color: #94A3B8;">({{ $tenant->reviews_count }} ulasan)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4 py-lg-5">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 d-flex align-items-center justify-content-between mb-4 px-4 py-3" role="alert">
            <span class="fw-semibold"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 d-flex align-items-center justify-content-between mb-4 px-4 py-3" role="alert">
            <span class="fw-semibold"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- Menu Grid --}}
    <div class="mb-4">
        <h2 class="tc-display h4 fw-bold mb-1">Daftar Menu</h2>
        <p class="text-secondary small mb-0">Pilih hidangan dari {{ $tenant->name }} untuk masuk ke keranjang.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
        @forelse($menus as $menu)
            <div class="col">
                <article class="tc-menu-card">
                    <div class="tc-menu-thumb">
                        <img src="{{ \Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}"
                             alt="{{ $menu->name }}" loading="lazy">
                        <span class="position-absolute top-0 start-0 m-3 tc-badge tc-badge-dark">
                            {{ $menu->category->name ?? 'Menu' }}
                        </span>
                        @if($menu->stock > 0)
                            <span class="position-absolute top-0 end-0 m-3 tc-badge tc-badge-neutral tc-num" style="font-size: 0.74rem;">
                                Stok {{ $menu->stock }}
                            </span>
                        @else
                            <span class="position-absolute top-0 end-0 m-3 tc-badge tc-badge-danger" style="font-size: 0.74rem;">
                                Habis
                            </span>
                        @endif
                    </div>

                    <div class="tc-menu-body">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <h3 class="tc-menu-title mb-0">{{ $menu->name }}</h3>
                            @if($menu->reviews_count > 0)
                                <span class="d-inline-flex align-items-center gap-1 small fw-bold flex-shrink-0">
                                    <i class="bi bi-star-fill" style="color: var(--tc-amber); font-size: 0.8rem;"></i>
                                    <span class="tc-num">{{ number_format($menu->reviews_avg_rating, 1) }}</span>
                                </span>
                            @endif
                        </div>

                        <p class="tc-menu-desc mt-1">{{ \Illuminate\Support\Str::limit($menu->description, 65) }}</p>

                        <div class="tc-menu-foot">
                            <span class="tc-menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            @if($tenant->is_open)
                                @if($menu->stock > 0)
                                    <form action="{{ route('cart.add', $menu->id_menu) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-tc-primary btn-sm px-3 py-2">
                                            <i class="bi bi-plus-lg me-1"></i>Pesan
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-tc-ghost btn-sm px-3 py-2" disabled>Habis</button>
                                @endif
                            @else
                                <button type="button" class="btn btn-tc-ghost btn-sm px-3 py-2" disabled>Tutup</button>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="tc-card p-5 text-center">
                    <i class="bi bi-journal-x fs-1 text-muted d-block mb-2"></i>
                    <h3 class="tc-display h5 fw-bold mb-1">Belum ada menu tersedia saat ini</h3>
                    <p class="text-secondary small mb-0">Silakan cek gerai kantin lain yang sedang buka.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Verified Reviews Section --}}
    <div class="pt-4 border-top">
        <div class="mb-4">
            <h2 class="tc-display h4 fw-bold mb-1">Ulasan Pelanggan</h2>
            <p class="text-secondary small mb-0">Ulasan terverifikasi dari pesanan selesai di {{ $tenant->name }}.</p>
        </div>

        @if($reviews->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 g-3">
                @foreach($reviews as $review)
                    <div class="col">
                        <div class="tc-card p-4 h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" style="width: 40px; height: 40px; background-color: var(--tc-obsidian);">
                                        {{ strtoupper(substr($review->user->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark lh-sm">{{ $review->user->name ?? 'Pelanggan' }}</div>
                                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="d-inline-flex align-items-center gap-1" style="color: var(--tc-amber);" aria-label="Rating {{ $review->rating }} dari 5">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-2">
                                <span class="tc-badge tc-badge-neutral"><i class="bi bi-bag"></i>{{ $review->menu->name ?? 'Menu' }}</span>
                            </div>
                            <p class="text-secondary small mb-0">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="tc-card p-5 text-center">
                <i class="bi bi-chat-square-text fs-2 text-muted d-block mb-2"></i>
                <h3 class="tc-display h6 fw-bold mb-1">Belum ada ulasan untuk gerai ini</h3>
                <p class="text-secondary small mb-0">Ulasan dapat ditulis setelah pesananmu berstatus selesai.</p>
            </div>
        @endif
    </div>
</div>
@endsection
