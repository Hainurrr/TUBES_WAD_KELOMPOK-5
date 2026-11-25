@extends('layouts.app')

@section('title', 'Katalog Menu — TelyuCan')

@section('content')
<x-page-banner title="Katalog Menu Kantin" subtitle="Temukan hidangan siap ambil dari gerai kantin yang sedang buka hari ini.">
    <x-slot:actions>
        <a href="{{ route('search') }}" class="btn btn-tc-outline text-white border-white border-opacity-25 text-decoration-none">
            <i class="bi bi-geo-alt me-2"></i>Lihat Daftar Kantin
        </a>
    </x-slot:actions>
</x-page-banner>

<section class="tc-operate-sheet">
    <div class="container">
        <x-flash-alerts />

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

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
            @forelse($menus as $menu)
                <div class="col">
                    <x-menu-card :menu="$menu" :show-review-count="true" heading-tag="h2" />
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state
                        icon="bi-search"
                        title="Menu tidak ditemukan"
                        description="Coba ubah kata kunci pencarian atau pilih lokasi kantin lainnya."
                        :action-url="route('menus.index')"
                        action-label="Tampilkan Semua Menu"
                    />
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $menus->links() }}
        </div>
    </div>
</section>
@endsection
