@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Jelajahi Menu</h2>
            <p class="text-secondary mb-0">Temukan makanan favoritmu dari berbagai tenant</p>
        </div>
    </div>

    <!-- Filter & Sort Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-4">
            <form action="{{ route('menus.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-secondary">Pencarian</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="Cari menu..." value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                         <label class="form-label small fw-bold text-secondary">Jenis Menu</label>
                         <select name="menu_category" class="form-select">
                             <option value="">Semua</option>
                             <option value="Makanan" {{ request('menu_category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                             <option value="Minuman" {{ request('menu_category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                             <option value="Snack" {{ request('menu_category') == 'Snack' ? 'selected' : '' }}>Snack</option>
                         </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-secondary">Lokasi Kantin</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Lokasi</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-bold text-secondary">Urutkan</label>
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-maroon w-100 fw-bold" style="background-color: #800000; color: white;">Terapkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
        @forelse($menus as $menu)
        <div class="col">
            <div class="card h-100 border hover-card rounded-4 overflow-hidden border-0 shadow-sm">
                <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" class="card-img-top object-fit-cover" height="200" alt="{{ $menu->name }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">{{ $menu->name }}</h5>
                                <a href="{{ route('tenant.show', $menu->tenant->id_tenant) }}" class="text-decoration-none d-block mt-1">
                                    <span class="small text-secondary"><i class="bi bi-shop me-1"></i>{{ $menu->tenant->name }}</span>
                                    <span class="badgex small text-muted ms-1 border-start ps-2">{{ $menu->tenant->category }}</span>
                                </a>
                            </div>
                            <span class="fw-bold text-success">{{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-star-fill text-warning small me-1"></i>
                        <span class="fw-bold small me-1">{{ $menu->reviews_avg_rating ? number_format($menu->reviews_avg_rating, 1) : '0.0' }}</span>
                        <span class="text-secondary small">({{ $menu->reviews_count }})</span>
                    </div>
                    
                    <p class="card-text text-secondary small mb-3">{{ Str::limit($menu->description, 50) }}</p>

                    @if($menu->stock > 0)
                        <form action="{{ route('cart.add', $menu->id_menu) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-maroon w-100 fw-bold btn-sm">Pesan</button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 fw-bold btn-sm" disabled>Habis</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486777.png" width="100" class="mb-3 opacity-25" alt="Empty">
            <h5 class="text-secondary fw-bold">Menu tidak ditemukan</h5>
            <p class="text-muted">Coba ubah filter pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $menus->links() }}
    </div>
</div>
@endsection
