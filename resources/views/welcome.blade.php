@extends('layouts.app')

@section('content')
    @include('components.hero', ['categories' => $categories, 'menus' => $menus])

    <section class="tc-catalog-sheet" id="katalog-menu">
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

            {{-- Catalog Header & Category Pills --}}
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div>
                    <h2 class="tc-display fw-bold mb-1" style="font-size: clamp(1.55rem, 2.5vw, 2rem);">Menu Kantin Hari Ini</h2>
                    <p class="text-secondary mb-0">Hidangan terbaru dari gerai kantin yang sedang buka hari ini.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="tc-cat-pills" role="navigation" aria-label="Filter kategori menu">
                        <a href="{{ route('home') }}#katalog-menu"
                           class="tc-cat-pill {{ !request('menu_category') ? 'is-active' : '' }}">
                            Semua
                        </a>
                        <a href="{{ route('home', ['menu_category' => 'Makanan']) }}#katalog-menu"
                           class="tc-cat-pill {{ request('menu_category') == 'Makanan' ? 'is-active' : '' }}">
                            <i class="bi bi-egg-fried" aria-hidden="true"></i>Makanan
                        </a>
                        <a href="{{ route('home', ['menu_category' => 'Minuman']) }}#katalog-menu"
                           class="tc-cat-pill {{ request('menu_category') == 'Minuman' ? 'is-active' : '' }}">
                            <i class="bi bi-cup-straw" aria-hidden="true"></i>Minuman
                        </a>
                        <a href="{{ route('home', ['menu_category' => 'Snack']) }}#katalog-menu"
                           class="tc-cat-pill {{ request('menu_category') == 'Snack' ? 'is-active' : '' }}">
                            <i class="bi bi-box2-heart" aria-hidden="true"></i>Snack
                        </a>
                    </div>
                    <a href="{{ route('menus.index', ['menu_category' => request('menu_category')]) }}" class="fw-bold text-maroon text-decoration-none small">
                        Lihat Katalog Lengkap <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            {{-- Menu Grid --}}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
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
                                    <h3 class="tc-menu-title mb-0">{{ $menu->name }}</h3>
                                    @if($menu->reviews_count > 0)
                                        <span class="d-inline-flex align-items-center gap-1 small fw-bold flex-shrink-0" style="color: var(--tc-ink);">
                                            <i class="bi bi-star-fill" style="color: var(--tc-amber); font-size: 0.8rem;"></i>
                                            <span class="tc-num">{{ number_format($menu->reviews_avg_rating, 1) }}</span>
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
                            <i class="bi bi-search fs-2 text-muted d-block mb-2"></i>
                            <h3 class="tc-display h5 fw-bold mb-1">Belum ada menu pada kategori ini</h3>
                            <p class="text-secondary small mb-3">Coba lihat semua menu atau jelajahi area kantin lainnya.</p>
                            <a href="{{ route('menus.index') }}" class="btn btn-tc-primary btn-sm">Lihat Semua Menu</a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Asymmetric 2-Panel Bento Directory Strip (Refusing 3-Equal-Cards Default) --}}
            <div class="tc-zones-band">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7">
                        <div class="tc-area-index">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="tc-display h4 fw-bold text-white mb-0">Area Kantin Kampus</h2>
                                <a href="{{ route('search') }}" class="small fw-semibold text-white-50 text-decoration-none">Semua gerai &rarr;</a>
                            </div>

                            <a href="{{ route('search', ['location' => 'TULT']) }}" class="tc-area-row">
                                <div>
                                    <div class="tc-display fw-bold fs-6">Kantin TULT</div>
                                    <div class="small tc-dark-copy">Gedung TULT Lantai 1 · Gerai makanan utama dan minuman segar</div>
                                </div>
                                <i class="bi bi-arrow-up-right flex-shrink-0"></i>
                            </a>

                            <a href="{{ route('search', ['location' => 'GKU']) }}" class="tc-area-row">
                                <div>
                                    <div class="tc-display fw-bold fs-6">Kantin GKU</div>
                                    <div class="small tc-dark-copy">Gedung Kuliah Umum · Pilihan praktis di sela pergantian kelas</div>
                                </div>
                                <i class="bi bi-arrow-up-right flex-shrink-0"></i>
                            </a>

                            <a href="{{ route('search', ['location' => 'Pujasera']) }}" class="tc-area-row">
                                <div>
                                    <div class="tc-display fw-bold fs-6">Pujasera Kampus</div>
                                    <div class="small tc-dark-copy">Area Pujasera · Beragam gerai kuliner dalam satu titik pengambilan</div>
                                </div>
                                <i class="bi bi-arrow-up-right flex-shrink-0"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="tc-area-cta">
                            <div>
                                <h2 class="tc-display h3 fw-bold text-white mb-2">Satu keranjang lintas gerai, satu kali bayar.</h2>
                                <p class="small mb-0" style="color: rgba(255,255,255,0.9); line-height: 1.55;">
                                    Gabungkan makanan dan minuman dari beberapa tenant sekaligus. Begitu pembayaran Midtrans lunas, setiap dapur langsung menyiapkan pesananmu.
                                </p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('menus.index') }}" class="btn btn-tc-dark px-4 py-2 text-decoration-none">
                                    Jelajahi Semua Menu <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                @auth
                                    @if(Auth::user()->role !== 'admin')
                                        <a href="{{ route('orders.history') }}" class="btn px-4 py-2 rounded-pill fw-bold text-white text-decoration-none" style="background: rgba(16,24,32,0.22);">
                                            Pantau Pesanan
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
