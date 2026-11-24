@extends('layouts.app')

@section('title', 'Katalog Menu — TelyuCan')

@section('content')
<section class="tc-page-banner">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <div>
                <h1 class="mb-2" style="font-size: clamp(1.75rem, 3vw, 2.4rem);">Katalog Menu Kantin</h1>
                <p class="mb-0 tc-dark-copy">Temukan hidangan siap ambil dari gerai kantin yang sedang buka hari ini.</p>
            </div>
            <a href="{{ route('search') }}" class="btn btn-tc-outline text-white border-white border-opacity-25 text-decoration-none">
                <i class="bi bi-geo-alt me-2"></i>Lihat Daftar Kantin
            </a>
        </div>
    </div>
</section>

<section class="tc-operate-sheet">
    <div class="container">
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

        {{-- Filter & Sort Bar --}}
        <div class="tc-card p-3 p-md-4 mb-4">
            <form action="{{ route('menus.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label for="filterQ" class="form-label">Cari Menu atau Gerai</label>
                        <input type="text" id="filterQ" name="q" class="form-control" placeholder="Contoh: ayam geprek, es teh..." value="{{ request('q') }}">
                    </div>
                    <div class="col-6 col-md-2">
                        <label for="filterMenuCat" class="form-label">Kategori</label>
                        <select id="filterMenuCat" name="menu_category" class="form-select">
                            <option value="">Semua</option>
                            <option value="Makanan" {{ request('menu_category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ request('menu_category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Snack" {{ request('menu_category') == 'Snack' ? 'selected' : '' }}>Snack</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label for="filterZone" class="form-label">Lokasi Kantin</label>
                        <select id="filterZone" name="category" class="form-select">
                            <option value="">Semua Lokasi</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="filterSort" class="form-label">Urutkan</label>
                        <select id="filterSort" name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-tc-primary w-100">Terapkan</button>
                        @if(request()->hasAny(['q', 'menu_category', 'category', 'sort']))
                            <a href="{{ route('menus.index') }}" class="btn btn-tc-ghost px-3" title="Reset filter" aria-label="Reset filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Menu Grid --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
            @forelse($menus as $menu)
                <div class="col">
                    <article class="tc-menu-card">
                        <div class="tc-menu-thumb">
                            <img src="{{ \Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}"
                                 alt="{{ $menu->name }}" loading="lazy">
                            @if($menu->stock > 0)
                                <span class="position-absolute top-0 start-0 m-3 tc-badge tc-badge-emerald-solid">
                                    <span class="tc-dot bg-white"></span>Buka
                                </span>
                                <span class="position-absolute top-0 end-0 m-3 tc-badge tc-badge-dark tc-num" style="font-size: 0.74rem;">
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
                                <h2 class="tc-menu-title mb-0">{{ $menu->name }}</h2>
                                @if($menu->reviews_count > 0)
                                    <span class="d-inline-flex align-items-center gap-1 small fw-bold flex-shrink-0">
                                        <i class="bi bi-star-fill" style="color: var(--tc-amber); font-size: 0.8rem;"></i>
                                        <span class="tc-num">{{ number_format($menu->reviews_avg_rating, 1) }}</span>
                                        <span class="text-muted fw-normal">({{ $menu->reviews_count }})</span>
                                    </span>
                                @endif
                            </div>

                            <div class="mb-2">
                                <a href="{{ route('tenant.show', $menu->tenant->id_tenant) }}" class="tc-menu-tenant">
                                    <i class="bi bi-shop"></i>
                                    <span>{{ $menu->tenant->name }}</span>
                                    @if($menu->tenant->category)
                                        <span class="text-muted">· {{ $menu->tenant->category }}</span>
                                    @endif
                                </a>
                            </div>

                            <p class="tc-menu-desc">{{ \Illuminate\Support\Str::limit($menu->description, 65) }}</p>

                            <div class="tc-menu-foot">
                                <span class="tc-menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
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
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="tc-card p-5 text-center">
                        <i class="bi bi-search fs-1 text-muted d-block mb-3"></i>
                        <h3 class="tc-display h5 fw-bold mb-1">Menu tidak ditemukan</h3>
                        <p class="text-secondary small mb-3">Coba ubah kata kunci pencarian atau pilih lokasi kantin lainnya.</p>
                        <a href="{{ route('menus.index') }}" class="btn btn-tc-primary btn-sm">Tampilkan Semua Menu</a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $menus->links() }}
        </div>
    </div>
</section>
@endsection
