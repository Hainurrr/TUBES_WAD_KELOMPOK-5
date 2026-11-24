<nav class="navbar navbar-expand-lg navbar-dark tc-navbar sticky-top">
    <div class="container">
        @php
            $homeRoute = route('home');
            if(Auth::check()) {
                if(Auth::user()->role === 'admin') $homeRoute = route('admin.dashboard');
                elseif(Auth::user()->role === 'tenant') $homeRoute = route('tenant.dashboard');
            }
        @endphp

        <a class="tc-brand" href="{{ $homeRoute }}">
            <svg width="34" height="34" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                <rect width="36" height="36" rx="11" fill="#B9112E"/>
                <path d="M9.5 11.5H26.5V15.5H20.25V26H15.75V15.5H9.5V11.5Z" fill="#FFFFFF"/>
                <rect x="21.5" y="21.5" width="5" height="4.5" rx="1.5" fill="#F59E0B"/>
            </svg>
            <span>TelyuCan</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarContent">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-1 gap-lg-2 mx-lg-auto my-2 my-lg-0">
                <a href="{{ route('home') }}" class="tc-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('menus.index') }}" class="tc-nav-link {{ request()->routeIs('menus.index') ? 'active' : '' }}">Katalog Menu</a>
                <a href="{{ route('search') }}" class="tc-nav-link {{ request()->routeIs('search') || request()->routeIs('tenant.show') ? 'active' : '' }}">Lokasi Kantin</a>
                @auth
                    @if(Auth::user()->role !== 'admin')
                        <a href="{{ route('orders.history') }}" class="tc-nav-link {{ request()->routeIs('orders.history') ? 'active' : '' }}">Riwayat Pesanan</a>
                    @endif
                @endauth
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 gap-lg-3 mt-2 mt-lg-0">
                @if(!request()->routeIs('home') && !request()->routeIs('menus.index'))
                    <form action="{{ route('menus.index') }}" method="GET" class="tc-nav-search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control shadow-none" placeholder="Cari menu atau kantin..." aria-label="Cari menu atau kantin">
                    </form>
                @endif

                <div class="d-flex align-items-center justify-content-end gap-2">
                    @auth
                        @if(Auth::user()->role === 'customer')
                            <a href="{{ route('cart.index') }}" class="tc-nav-icon-btn position-relative text-decoration-none" aria-label="Keranjang belanja" title="Keranjang Belanja">
                                <i class="bi bi-bag"></i>
                                @php
                                    $navCartCount = 0;
                                    foreach(session('cart', []) as $tGroup) {
                                        foreach($tGroup['items'] as $cItem) { $navCartCount += $cItem['qty']; }
                                    }
                                @endphp
                                @if($navCartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill tc-mono" style="background-color: var(--tc-crimson); font-size: 0.68rem;">
                                        {{ $navCartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <div class="dropdown">
                            <button class="tc-nav-icon-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                                <i class="bi bi-bell"></i>
                                @if(isset($notifications) && $notifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 rounded-circle" style="background-color: var(--tc-amber); border: 2px solid var(--tc-obsidian);">
                                        <span class="visually-hidden">Notifikasi baru</span>
                                    </span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu mt-2" style="width: 330px; max-height: 400px; overflow-y: auto;">
                                <li class="px-2 py-2 d-flex justify-content-between align-items-center border-bottom mb-1">
                                    <span class="fw-bold text-dark small">Notifikasi</span>
                                    @if(isset($notifications) && $notifications->count() > 0)
                                        <a href="{{ route('notifications.readAll') }}" class="text-decoration-none small fw-semibold text-maroon">Tandai Semua Dibaca</a>
                                    @endif
                                </li>

                                @if(isset($notifications) && $notifications->count() > 0)
                                    @foreach($notifications as $notif)
                                        @php
                                            $data = $notif->data;
                                            $status = $data['status'] ?? 'info';
                                            $tenantName = $data['tenant_name'] ?? 'Sistem';
                                        @endphp
                                        <li>
                                            <a class="dropdown-item d-flex align-items-start gap-2 py-2" href="{{ route('notifications.read', $notif->id) }}">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ in_array($status, ['completed', 'ready']) ? 'tc-badge-emerald' : 'tc-badge-amber' }}" style="width: 36px; height: 36px; padding: 0;">
                                                    <i class="bi {{ $status == 'ready' ? 'bi-bag-check' : ($status == 'completed' ? 'bi-check2-circle' : 'bi-clock') }}"></i>
                                                </div>
                                                <div class="text-wrap">
                                                    <div class="small fw-semibold text-dark lh-sm">
                                                        Pesanan di {{ $tenantName }}
                                                        @if($status == 'pending') menunggu konfirmasi.
                                                        @elseif($status == 'cooking') sedang dimasak.
                                                        @elseif($status == 'ready') siap diambil di konter.
                                                        @elseif($status == 'completed') selesai.
                                                        @else sedang diproses.
                                                        @endif
                                                    </div>
                                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="text-center py-4 px-3">
                                        <i class="bi bi-bell-slash fs-4 text-muted d-block mb-1"></i>
                                        <span class="text-muted small">Belum ada notifikasi baru.</span>
                                    </li>
                                @endif

                                <li class="border-top mt-1 pt-1">
                                    <a class="dropdown-item text-center small text-maroon fw-bold" href="{{ route('orders.history') }}">Lihat Semua Pesanan</a>
                                </li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <button class="btn border-0 p-0 d-flex align-items-center gap-2 text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu akun">
                                <img src="{{ Auth::user()->avatar && Auth::user()->avatar !== 'default_avatar.png' ? '/storage/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=B9112E&color=fff' }}"
                                     class="rounded-circle object-fit-cover" style="border: 2px solid rgba(255,255,255,0.22);" width="38" height="38" alt="Foto profil {{ Auth::user()->name }}">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu mt-2" style="min-width: 230px;">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold text-dark lh-sm">{{ Auth::user()->name }}</div>
                                    <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profil &amp; Pengaturan</a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Panel Admin</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Kelola Pengguna</a></li>
                                @endif
                                @if(Auth::user()->role === 'tenant')
                                    <li><a class="dropdown-item" href="{{ route('tenant.dashboard') }}"><i class="bi bi-fire me-2"></i>Layar Dapur</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tenant.menus.index') }}"><i class="bi bi-journal-text me-2"></i>Kelola Menu</a></li>
                                    <li><a class="dropdown-item" href="{{ route('tenant.orders.history') }}"><i class="bi bi-receipt me-2"></i>Riwayat Penjualan</a></li>
                                @endif
                                @if(Auth::user()->role !== 'admin')
                                    <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</a></li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-semibold">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-tc-primary px-4 py-2 text-decoration-none text-nowrap flex-shrink-0">
                            Masuk / Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
