@extends('layouts.app')

@section('content')
    @include('components.hero', ['categories' => $categories, 'menus' => $menus])

    <section class="tc-catalog-sheet" id="katalog-menu">
        <div class="container">
            <x-flash-alerts />

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

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @forelse($menus as $menu)
                    <div class="col">
                        <x-menu-card :menu="$menu" />
                    </div>
                @empty
                    <div class="col-12">
                        <x-empty-state
                            icon="bi-search"
                            title="Belum ada menu pada kategori ini"
                            description="Coba lihat semua menu atau jelajahi area kantin lainnya."
                            :action-url="route('menus.index')"
                            action-label="Lihat Semua Menu"
                        />
                    </div>
                @endforelse
            </div>

            <div class="tc-zones-band">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7">
                        <div class="tc-area-index">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <h2 class="tc-display h4 fw-bold text-white mb-0">Area Kantin Kampus</h2>
                                <a href="{{ route('search') }}" class="small fw-semibold text-white-50 text-decoration-none text-nowrap">Semua gerai &rarr;</a>
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
                                    Gabungkan makanan dan minuman dari beberapa tenant sekaligus. Begitu pembayaran lunas, setiap dapur langsung menyiapkan pesananmu.
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
