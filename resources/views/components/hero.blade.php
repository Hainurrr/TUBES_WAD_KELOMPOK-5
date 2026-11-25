@php
    $inStockMenus = isset($menus) ? $menus->where('stock', '>', 0)->values() : collect();
    $featuredMenu = $inStockMenus->count() > 1 ? $inStockMenus->last() : ($inStockMenus->first() ?? (isset($menus) ? $menus->first() : null));
    $fallbackImg = 'https://images.unsplash.com/photo-1572656631137-7935297eff55?q=80&w=900';

    $featuredImage = $featuredMenu && $featuredMenu->image
        ? (\Illuminate\Support\Str::startsWith($featuredMenu->image, 'http') ? $featuredMenu->image : asset('storage/' . $featuredMenu->image))
        : $fallbackImg;

    $zoneList = isset($categories) && $categories->isNotEmpty()
        ? $categories
        : collect(['Kantin TULT', 'Kantin GKU', 'Pujasera']);
    $selectedZone = request('category', '');

    $heroMenuCatalog = $inStockMenus->map(function ($m) use ($fallbackImg) {
        $img = $m->image
            ? (\Illuminate\Support\Str::startsWith($m->image, 'http') ? $m->image : asset('storage/' . $m->image))
            : $fallbackImg;

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
            <div class="tc-bento-tile tc-bento-hero">
                <div>
                    <h1 class="tc-hero-title">Kantin Tel-U,<br>tanpa antri lagi.</h1>
                    <p class="tc-hero-sub">
                        Pesan makanan dari berbagai gerai kantin Telkom University dalam satu keranjang, bayar non-tunai secara instan, lalu ambil langsung di konter saat sudah siap.
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

            <a href="{{ $featuredMenu && $featuredMenu->tenant ? route('tenant.show', $featuredMenu->tenant->id_tenant) : route('menus.index') }}"
               class="tc-bento-tile tc-bento-photo"
               id="tcFeaturedTile">
                <img src="{{ $featuredImage }}" alt="{{ $featuredMenu ? $featuredMenu->name : 'Menu kantin Telkom University' }}" id="tcFeaturedImg">
                <div class="tc-bento-photo-overlay">
                    <div>
                        <div class="tc-bento-photo-title" id="tcFeaturedTitle">{{ $featuredMenu ? $featuredMenu->name : 'Jelajahi Menu Kampus' }}</div>
                        <div class="tc-bento-photo-meta" id="tcFeaturedTenant">{{ $featuredMenu && $featuredMenu->tenant ? $featuredMenu->tenant->name : 'Pilihan hidangan siap pesan' }}</div>
                    </div>
                    <span class="tc-badge tc-badge-dark tc-mono text-nowrap flex-shrink-0" id="tcFeaturedPrice">
                        {{ $featuredMenu ? 'Rp ' . number_format($featuredMenu->price, 0, ',', '.') : 'Lihat' }}
                    </span>
                </div>
            </a>

            <div class="tc-bento-tile tc-bento-crimson">
                <h2>3 langkah ambil cepat.</h2>
                <div class="tc-step-stack">
                    <div class="tc-step-pill"><span class="tc-step-num">01</span><span>Pilih Kantin &amp; Menu</span></div>
                    <div class="tc-step-pill"><span class="tc-step-num">02</span><span>Bayar Non-Tunai</span></div>
                    <div class="tc-step-pill"><span class="tc-step-num">03</span><span>Ambil Tanpa Antri</span></div>
                </div>
            </div>

            <div class="tc-bento-tile tc-bento-amber">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <h3>Pembayaran Digital Instan</h3>
                    <span class="tc-tile-arrow" style="background: rgba(16,24,32,0.12); color: #101820;">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                    </span>
                </div>
                <p>Transaksi non-tunai terverifikasi otomatis dan langsung meneruskan tiket pesananmu ke dapur tenant.</p>
            </div>

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

    <script type="application/json" id="tcHeroMenuCatalog">@json($heroMenuCatalog)</script>
</section>
