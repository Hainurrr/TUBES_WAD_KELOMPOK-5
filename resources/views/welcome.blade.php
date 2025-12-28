@extends('layouts.app')

@section('content')

    @include('components.hero', ['categories' => $categories])

    <div class="container py-5">
        <div class="text-center mb-5">
            <h3 class="fw-bold text-dark">Mau Mam Apa?</h3>
            <p class="text-secondary">Pilih kategori menu favoritmu hari ini</p>
            
            <div class="d-flex justify-content-center gap-4 mt-4 flex-wrap">
                <a href="{{ route('home') }}" class="text-decoration-none text-center group" data-aos="fade-up" data-aos-delay="0">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border hover-card" 
                         style="width: 80px; height: 80px;">
                        <span class="fs-1">🍽️</span>
                    </div>
                    <span class="fw-bold text-dark small">Semua</span>
                </a>
                <a href="{{ route('home', ['menu_category' => 'Makanan']) }}" class="text-decoration-none text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border hover-card" 
                         style="width: 80px; height: 80px;">
                        <span class="fs-1">🍚</span>
                    </div>
                    <span class="fw-bold text-dark small">Makanan</span>
                </a>
                <a href="{{ route('home', ['menu_category' => 'Minuman']) }}" class="text-decoration-none text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border hover-card" 
                         style="width: 80px; height: 80px;">
                        <span class="fs-1">🥤</span>
                    </div>
                    <span class="fw-bold text-dark small">Minuman</span>
                </a>
                <a href="{{ route('home', ['menu_category' => 'Snack']) }}" class="text-decoration-none text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm border hover-card" 
                         style="width: 80px; height: 80px;">
                        <span class="fs-1">🍟</span>
                    </div>
                    <span class="fw-bold text-dark small">Snack</span>
                </a>
            </div>
        </div>

        <!-- Business Value Section -->
        <div class="row g-4 mb-5" data-aos="fade-up">
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100 text-center hover-card">
                    <div class="mb-3 text-maroon fs-1">⚡</div>
                    <h5 class="fw-bold text-dark">Pemesanan Cepat</h5>
                    <p class="text-secondary small mb-0">Pesan dari mana saja, ambil saat sudah siap. Tanpa antri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100 text-center hover-card">
                    <div class="mb-3 text-maroon fs-1">🛡️</div>
                    <h5 class="fw-bold text-dark">Higienis & Aman</h5>
                    <p class="text-secondary small mb-0">Standar kebersihan terjamin dari tenant-tenant terpercaya.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100 text-center hover-card">
                    <div class="mb-3 text-maroon fs-1">💳</div>
                    <h5 class="fw-bold text-dark">Pembayaran Mudah</h5>
                    <p class="text-secondary small mb-0">Dukung berbagai metode pembayaran digital.</p>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Rekomendasi Menu</h3>
                <p class="text-secondary mb-0 small">Pilihan favorit pelanggan minggu ini</p>
            </div>
            <a href="{{ route('menus.index', ['menu_category' => request('menu_category')]) }}" class="text-maroon fw-bold text-decoration-none">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
@foreach($menus as $menu)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
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
            @endforeach
        </div>
    </div>
@endsection