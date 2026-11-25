@props([
    'menu',
    'showTenant' => true,
    'showReviewCount' => false,
    'showOpenBadge' => true,
    'showCategoryBadge' => false,
    'tenantOpen' => true,
    'headingTag' => 'h3',
])

@php
    $imageUrl = $menu->image
        ? (\Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image))
        : null;
@endphp

<article class="tc-menu-card">
    <div class="tc-menu-thumb">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $menu->name }}" loading="lazy">
        @else
            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                <i class="bi bi-image fs-2"></i>
            </div>
        @endif

        @if($showCategoryBadge)
            <span class="position-absolute top-0 start-0 m-3 tc-badge tc-badge-dark">
                {{ $menu->category->name ?? 'Menu' }}
            </span>
        @elseif($showOpenBadge && $menu->stock > 0)
            <span class="position-absolute top-0 start-0 m-3 tc-badge tc-badge-emerald-solid">
                <span class="tc-dot bg-white"></span>Buka
            </span>
        @endif

        @if($menu->stock > 0)
            <span class="position-absolute top-0 end-0 m-3 tc-badge {{ $showCategoryBadge ? 'tc-badge-neutral' : 'tc-badge-dark' }} tc-num" style="font-size: 0.74rem;">
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
            <{{ $headingTag }} class="tc-menu-title mb-0">{{ $menu->name }}</{{ $headingTag }}>
            @if(($menu->reviews_count ?? 0) > 0)
                <span class="d-inline-flex align-items-center gap-1 small fw-bold flex-shrink-0" style="color: var(--tc-ink);">
                    <i class="bi bi-star-fill" style="color: var(--tc-amber); font-size: 0.8rem;" aria-hidden="true"></i>
                    <span class="tc-num">{{ number_format($menu->reviews_avg_rating, 1) }}</span>
                    @if($showReviewCount)
                        <span class="text-muted fw-normal">({{ $menu->reviews_count }})</span>
                    @endif
                </span>
            @endif
        </div>

        @if($showTenant && $menu->tenant)
            <div class="mb-2">
                <a href="{{ route('tenant.show', $menu->tenant->id_tenant) }}" class="tc-menu-tenant">
                    <i class="bi bi-shop" aria-hidden="true"></i>
                    <span>{{ $menu->tenant->name }}</span>
                    @if($menu->tenant->category)
                        <span class="text-muted">· {{ $menu->tenant->category }}</span>
                    @endif
                </a>
            </div>
        @endif

        <p class="tc-menu-desc {{ !$showTenant ? 'mt-1' : '' }}">{{ \Illuminate\Support\Str::limit($menu->description, 65) }}</p>

        <div class="tc-menu-foot">
            <span class="tc-menu-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
            @if(!$tenantOpen)
                <button type="button" class="btn btn-tc-ghost btn-sm px-3 py-2" disabled>Tutup</button>
            @elseif($menu->stock > 0)
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
