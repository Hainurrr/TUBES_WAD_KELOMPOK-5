@php
    $inStockMenus = isset($menus) ? $menus->where('stock', '>', 0)->values() : collect();
    $featuredMenu = $inStockMenus->count() > 1 ? $inStockMenus->last() : ($inStockMenus->first() ?? (isset($menus) ? $menus->first() : null));
    $featuredImage = $featuredMenu && $featuredMenu->image
        ? (\Illuminate\Support\Str::startsWith($featuredMenu->image, 'http') ? $featuredMenu->image : asset('storage/' . $featuredMenu->image))
        : 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=900';
    $zoneList = isset($categories) && $categories->isNotEmpty()
        ? $categories
        : collect(['Kantin TULT', 'Kantin GKU', 'Pujasera']);
    $selectedZone = request('category', '');

    $heroMenuCatalog = $inStockMenus->map(function ($m) {
        $img = $m->image
            ? (\Illuminate\Support\Str::startsWith($m->image, 'http') ? $m->image : asset('storage/' . $m->image))
            : 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=900';
        return [
            'name' => $m->name,
            'tenant' => $m->tenant->name ?? 'Kantin Kampus',
            'category' => $m->tenant->category ?? '',
            'price' => 'Rp ' . number_format($m->price, 0, ',', '.'),
            'image' => $img,
            'url' => $m->tenant ? route('tenant.show', $m->tenant->id_tenant) : route('menus.index'),
        ];
    })->values();
@endphp

<section class="tc-obsidian-stage">
    <div class="container">
        <div class="tc-bento-grid">
            {{-- Main Hero Command Tile --}}
            <div class="tc-bento-tile tc-bento-hero">
                <div>
                    <h1 class="tc-hero-title">Kantin Tel-U,<br>tanpa antri lagi.</h1>
                    <p class="tc-hero-sub">
                        Pesan makanan dari berbagai gerai kantin Telkom University dalam satu keranjang, bayar digital melalui Midtrans, lalu ambil langsung di konter saat sudah siap.
                    </p>
                </div>

                <form action="{{ route('menus.index') }}" method="GET" class="tc-command-dock" id="tcHeroSearchForm">
                    <input type="hidden" name="category" id="tcZoneInput" value="{{ $selectedZone }}">
                    <div class="tc-zone-tabs" role="group" aria-label="Pilih lokasi kantin">
                        <button type="button"
                                class="tc-zone-tab {{ $selectedZone === '' ? 'is-active' : '' }}"
                                data-zone=""
                                aria-pressed="{{ $selectedZone === '' ? 'true' : 'false' }}">
                            Semua Kantin
                        </button>
                        @foreach($zoneList->take(4) as $zone)
                            <button type="button"
                                    class="tc-zone-tab {{ $selectedZone === $zone ? 'is-active' : '' }}"
                                    data-zone="{{ $zone }}"
                                    aria-pressed="{{ $selectedZone === $zone ? 'true' : 'false' }}">
                                {{ $zone }}
                            </button>
                        @endforeach
                    </div>

                    <div class="tc-dock-search">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari menu atau nama gerai..."
                               aria-label="Cari menu atau gerai kantin">
                        <button type="submit">Cari Menu</button>
                    </div>

                    <div class="tc-dock-hint">
                        <span id="tcZoneHint">
                            {{ $selectedZone !== '' ? 'Menyaring area: ' . $selectedZone : 'Menampilkan semua kantin buka di kampus' }}
                        </span>
                        <a href="{{ route('search') }}" class="text-white-50 text-decoration-none fw-semibold">Lihat semua lokasi &rarr;</a>
                    </div>
                </form>
            </div>

            {{-- Featured Culinary Photo Tile --}}
            <a href="{{ $featuredMenu && $featuredMenu->tenant ? route('tenant.show', $featuredMenu->tenant->id_tenant) : route('menus.index') }}"
               class="tc-bento-tile tc-bento-photo"
               id="tcFeaturedTile">
                <img src="{{ $featuredImage }}" alt="{{ $featuredMenu ? $featuredMenu->name : 'Menu kantin Telkom University' }}" id="tcFeaturedImg">
                <div class="tc-bento-photo-overlay">
                    <div>
                        <div class="tc-bento-photo-title" id="tcFeaturedTitle">{{ $featuredMenu ? $featuredMenu->name : 'Jelajahi Menu Kampus' }}</div>
                        <div class="tc-bento-photo-meta" id="tcFeaturedTenant">{{ $featuredMenu && $featuredMenu->tenant ? $featuredMenu->tenant->name : 'Pilihan hidangan siap pesan' }}</div>
                    </div>
                    <span class="tc-badge tc-badge-dark tc-mono" id="tcFeaturedPrice">
                        {{ $featuredMenu ? 'Rp ' . number_format($featuredMenu->price, 0, ',', '.') : 'Lihat' }}
                    </span>
                </div>
            </a>

            {{-- Crimson 3-Step Pickup Tile --}}
            <div class="tc-bento-tile tc-bento-crimson">
                <h2>3 langkah ambil cepat.</h2>
                <div class="tc-step-stack">
                    <div class="tc-step-pill"><span class="tc-step-num">01</span><span>Pilih Kantin &amp; Menu</span></div>
                    <div class="tc-step-pill"><span class="tc-step-num">02</span><span>Bayar via Midtrans</span></div>
                    <div class="tc-step-pill"><span class="tc-step-num">03</span><span>Ambil Tanpa Antri</span></div>
                </div>
            </div>

            {{-- Amber Midtrans Payment Info Tile (Honest non-link surface) --}}
            <div class="tc-bento-tile tc-bento-amber">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <h3>QRIS, E-Wallet &amp; Virtual Account</h3>
                    <span class="tc-tile-arrow" style="background: rgba(16,24,32,0.12); color: #101820;">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                    </span>
                </div>
                <p>Pembayaran digital terverifikasi langsung meneruskan tiket pesananmu ke dapur tenant.</p>
            </div>

            {{-- Emerald Campus Zones Live Link Tile --}}
            <a href="{{ route('search') }}" class="tc-bento-tile tc-bento-emerald" id="tcEmeraldZoneTile" data-base-url="{{ route('search') }}">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <h3 id="tcEmeraldZoneTitle">TULT, GKU &amp; Pujasera</h3>
                    <span class="tc-tile-arrow" style="background: rgba(255,255,255,0.18); color: #FFFFFF;">
                        <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                    </span>
                </div>
                <p id="tcEmeraldZoneSub">Cek status buka tiap gerai dan stok menu sebelum berjalan ke kantin.</p>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const zoneButtons = document.querySelectorAll('#tcHeroSearchForm .tc-zone-tab');
        const zoneInput = document.getElementById('tcZoneInput');
        const zoneHint = document.getElementById('tcZoneHint');
        const emeraldTile = document.getElementById('tcEmeraldZoneTile');
        const emeraldTitle = document.getElementById('tcEmeraldZoneTitle');
        const emeraldSub = document.getElementById('tcEmeraldZoneSub');

        const featuredTile = document.getElementById('tcFeaturedTile');
        const featuredImg = document.getElementById('tcFeaturedImg');
        const featuredTitle = document.getElementById('tcFeaturedTitle');
        const featuredTenant = document.getElementById('tcFeaturedTenant');
        const featuredPrice = document.getElementById('tcFeaturedPrice');
        const menuPool = @json($heroMenuCatalog);

        const defaultFeatured = featuredTile ? {
            name: featuredTitle.textContent,
            tenant: featuredTenant.textContent,
            price: featuredPrice.textContent,
            image: featuredImg.src,
            url: featuredTile.getAttribute('href')
        } : null;

        let spotlightTimer = null;

        zoneButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const selected = this.getAttribute('data-zone') || '';
                zoneInput.value = selected;

                zoneButtons.forEach(function(b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('is-active');
                this.setAttribute('aria-pressed', 'true');

                if (zoneHint) {
                    zoneHint.textContent = selected
                        ? 'Menyaring area: ' + selected
                        : 'Menampilkan semua kantin buka di kampus';
                }

                // Authored bento spotlight sync on emerald zone tile
                if (emeraldTile && emeraldTitle && emeraldSub) {
                    const base = emeraldTile.getAttribute('data-base-url');
                    if (selected) {
                        emeraldTitle.textContent = selected;
                        emeraldSub.textContent = 'Lihat semua gerai buka dan menu di area ' + selected + '.';
                        emeraldTile.setAttribute('href', base + '?location=' + encodeURIComponent(selected.replace(/^Kantin\s+/i, '')));
                    } else {
                        emeraldTitle.textContent = 'TULT, GKU & Pujasera';
                        emeraldSub.textContent = 'Cek status buka tiap gerai dan stok menu sebelum berjalan ke kantin.';
                        emeraldTile.setAttribute('href', base);
                    }
                    emeraldTile.classList.add('is-spotlight');
                    clearTimeout(spotlightTimer);
                    spotlightTimer = setTimeout(function() {
                        emeraldTile.classList.remove('is-spotlight');
                    }, 550);
                }

                // Sync featured dish bento tile with selected canteen zone when data matches
                if (featuredTile && defaultFeatured && Array.isArray(menuPool) && menuPool.length > 0) {
                    const needle = selected.toLowerCase().replace(/^kantin\s+/i, '');
                    const matched = selected
                        ? (menuPool.find(m => m.tenant.toLowerCase().includes(needle) || (m.category && m.category.toLowerCase().includes(needle))) || defaultFeatured)
                        : defaultFeatured;

                    featuredTile.classList.add('is-syncing');
                    setTimeout(function() {
                        featuredImg.src = matched.image;
                        featuredImg.alt = matched.name;
                        featuredTitle.textContent = matched.name;
                        featuredTenant.textContent = matched.tenant;
                        featuredPrice.textContent = matched.price;
                        featuredTile.setAttribute('href', matched.url);
                        featuredTile.classList.remove('is-syncing');
                    }, 120);
                }
            });
        });
    });
</script>
@endpush
