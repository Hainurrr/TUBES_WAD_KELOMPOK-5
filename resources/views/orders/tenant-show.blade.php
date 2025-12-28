@extends('layouts.app')

@section('content')

<!-- Tenant Header -->
<div class="bg-maroon text-white py-5 position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>
    <div class="container position-relative z-1">
        <a href="{{ route('home') }}" class="text-decoration-none text-white-50 mb-4 d-inline-block hover-opacity">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
        </a>
        <div class="text-center">
        <div class="mb-4">
            <div class="rounded-circle bg-white p-1 d-inline-block shadow-lg">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=800000&color=fff&size=128" 
                     alt="{{ $tenant->name }}" 
                     class="rounded-circle border border-4 border-white" 
                     width="120" height="120">
            </div>
        </div>
        <h1 class="fw-bold display-5 mb-2">{{ $tenant->name }}</h1>
        <p class="fs-5 opacity-75 mb-3">{{ $tenant->description }}</p>
        <div class="d-flex justify-content-center gap-3 text-white-50 small">
            <span class="badge bg-light text-maroon border border-maroon-subtle"><i class="bi bi-geo-alt-fill me-1"></i> {{ $tenant->category }}</span>

            @if($tenant->is_open)
                <span class="badge bg-success"><i class="bi bi-shop me-1"></i> Buka</span>
            @else
                <span class="badge bg-danger"><i class="bi bi-shop-window me-1"></i> Tutup</span>
            @endif
            <span class="text-warning">
                <i class="bi bi-star-fill me-1"></i> 
                {{ $tenant->reviews_avg_rating ? number_format($tenant->reviews_avg_rating, 1) : '0.0' }}
                <span class="text-white-50">({{ $tenant->reviews_count }})</span>
            </span>
        </div>
    </div>
</div>
</div>

<!-- Menu Grid -->
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h3 class="fw-bold text-dark mb-0">Daftar Menu</h3>
            <p class="text-secondary mb-0">Semua hidangan lezat dari {{ $tenant->name }}</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        @forelse($menus as $menu)
            <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="card h-100 border hover-card rounded-4 overflow-hidden border-0 shadow-sm">
                    <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" class="card-img-top object-fit-cover" height="200" alt="{{ $menu->name }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light text-maroon border border-maroon-subtle">
                                {{ $menu->category->name ?? 'Umum' }}
                            </span>
                            <span class="small fw-bold text-success">{{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-1">{{ $menu->name }}</h5>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-star-fill text-warning small me-1"></i>
                            <span class="fw-bold small me-1">{{ $menu->reviews_avg_rating ? number_format($menu->reviews_avg_rating, 1) : '0.0' }}</span>
                            <span class="text-secondary small">({{ $menu->reviews_count }})</span>
                        </div>

                        <p class="card-text text-secondary small mb-3">{{ Str::limit($menu->description, 50) }}</p>

                        @if($tenant->is_open)
                            @if($menu->stock > 0)
                                <form action="{{ route('cart.add', $menu->id_menu) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-maroon w-100 fw-bold btn-sm">Pesan</button>
                                </form>
                            @else
                                <button class="btn btn-secondary w-100 fw-bold btn-sm" disabled>Habis</button>
                            @endif
                        @else
                             <button class="btn btn-danger w-100 fw-bold btn-sm" disabled>Tutup</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" width="80" class="mb-3 opacity-50" alt="Empty">
                <h5 class="text-secondary">Belum ada menu tersedia saat ini.</h5>
            </div>
        @endforelse
    </div>
</div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12 mb-4">
            <h3 class="fw-bold text-dark mb-0">Ulasan Pelanggan</h3>
            <p class="text-secondary mb-0">Apa kata mereka tentang {{ $tenant->name }}</p>
        </div>
        <div class="col-12">
            @if($reviews->isNotEmpty())
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach($reviews as $review)
                        <div class="col">
                            <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-maroon shadow-sm" style="width: 40px; height: 40px;">
                                                {{ substr($review->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name ?? 'Guest' }}</h6>
                                                <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <div class="text-warning">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-white text-dark border"><i class="bi bi-basket me-1"></i> {{ $review->menu->name ?? 'Menu' }}</span>
                                    </div>
                                    <p class="text-secondary mb-0">"{{ $review->comment }}"</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="bi bi-chat-square-text fs-1 text-secondary mb-3 opacity-50"></i>
                    <h5 class="text-secondary">Belum ada ulasan untuk kantin ini.</h5>
                    <p class="text-secondary small">Jadilah yang pertama memberikan ulasan setelah memesan!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
