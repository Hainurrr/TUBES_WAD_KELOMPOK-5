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
                     class="rounded-4 object-fit-cover flex-shrink-0 tc-tenant-avatar" style="border: 2px solid rgba(255,255,255,0.2);" width="84" height="84">
                <div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        @if($tenant->is_open)
                            <span class="tc-badge tc-badge-emerald-solid"><span class="tc-dot bg-white"></span>Sedang Buka</span>
                        @else
                            <span class="tc-badge tc-badge-danger"><i class="bi bi-slash-circle"></i>Sedang Tutup</span>
                        @endif
                        @if($tenant->category)
                            <span class="tc-badge tc-badge-glass">
                                <i class="bi bi-geo-alt"></i>{{ $tenant->category }}
                            </span>
                        @endif
                    </div>
                    <h1 class="mb-1" style="font-size: clamp(1.7rem, 3vw, 2.4rem);">{{ $tenant->name }}</h1>
                    <p class="mb-0 tc-dark-copy">{{ $tenant->description ?: 'Layanan pesan dan ambil langsung di konter.' }}</p>
                </div>
            </div>

            <div class="tc-bento-tile p-3 px-4 flex-shrink-0" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); border-radius: 20px;">
                <div class="small tc-dark-copy">Rating Pelanggan</div>
                <div class="d-flex align-items-baseline gap-2 mt-1">
                    <i class="bi bi-star-fill" style="color: var(--tc-amber);"></i>
                    <span class="tc-display fw-bold fs-3 text-white tc-num">{{ $tenant->reviews_count > 0 ? number_format($tenant->reviews_avg_rating, 1) : '—' }}</span>
                    <span class="small" style="color: #94A3B8;">({{ $tenant->reviews_count }} ulasan)</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="tc-operate-sheet">
    <div class="container">
        <x-flash-alerts />

        <div class="mb-4">
            <h2 class="tc-display h4 fw-bold mb-1">Daftar Menu</h2>
            <p class="text-secondary small mb-0">Pilih hidangan dari {{ $tenant->name }} untuk masuk ke keranjang.</p>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mb-5">
            @forelse($menus as $menu)
                <div class="col">
                    <x-menu-card
                        :menu="$menu"
                        :show-tenant="false"
                        :show-open-badge="false"
                        :show-category-badge="true"
                        :tenant-open="(bool) $tenant->is_open"
                    />
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state
                        icon="bi-journal-x"
                        title="Belum ada menu tersedia saat ini"
                        description="Silakan cek gerai kantin lain yang sedang buka."
                    />
                </div>
            @endforelse
        </div>

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
                                    <x-rating-stars :rating="$review->rating" />
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
                <x-empty-state
                    icon="bi-chat-square-text"
                    heading-class="h6"
                    title="Belum ada ulasan untuk gerai ini"
                    description="Ulasan dapat ditulis setelah pesananmu berstatus selesai."
                />
            @endif
        </div>
    </div>
</section>
@endsection
