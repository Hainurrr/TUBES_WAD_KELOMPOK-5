@extends('layouts.app')

@section('content')
<div class="container py-5">
    <a href="{{ route('home') }}" class="text-decoration-none text-secondary mb-3 d-inline-block hover-opacity">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
    </a>
    <div class="mb-4 text-center">
        <h2 class="fw-bold text-dark">Hasil Pencarian Kantin</h2>
        <p class="text-secondary">Menampilkan tenant untuk: <span class="fw-bold text-maroon">{{ $location ?? 'Semua Lokasi' }}</span></p>
    </div>

    @if($tenants->isEmpty())
        <div class="text-center py-5">
            <h4 class="text-secondary">Tidak ada tenant ditemukan di lokasi ini.</h4>
            <a href="{{ route('home') }}" class="btn btn-maroon mt-3">Kembali ke Beranda</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($tenants as $tenant)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('tenant.show', $tenant->id_tenant) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 border-0 shadow-sm hover-card rounded-4 overflow-hidden">
                            <!-- Placeholder Banner if no image -->
                            <div class="bg-maroon text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                                <h1 class="display-4 fw-bold opacity-25">{{ substr($tenant->name, 0, 1) }}</h1>
                            </div>
                            
                            <div class="card-body text-center p-4">
                                <div class="mt-n5 mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=fff&color=800000&size=80" 
                                         alt="{{ $tenant->name }}" 
                                         class="rounded-circle border border-4 border-white shadow-sm" 
                                         width="80" height="80">
                                </div>
                                <h5 class="fw-bold mb-1">{{ $tenant->name }}</h5>
                                <div class="mb-2">
                                    <span class="badge bg-light text-maroon border border-maroon-subtle small">
                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $tenant->category }}
                                    </span>
                                </div>
                                <p class="text-secondary small mb-3">{{ $tenant->description }}</p>

                                <div class="mt-2 d-flex justify-content-center align-items-center gap-1 text-warning">
                                    <span class="fw-bold text-dark">{{ $tenant->reviews_avg_rating ? number_format($tenant->reviews_avg_rating, 1) : '0.0' }}</span>
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-secondary small">({{ $tenant->reviews_count }})</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pb-4 pt-0">
                                <button class="btn btn-outline-maroon w-100 rounded-pill fw-bold">Kunjungi Tenant</button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
