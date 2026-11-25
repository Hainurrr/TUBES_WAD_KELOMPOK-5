<!doctype html>
<html lang="id">
@include('layouts.partials.head', ['defaultTitle' => $defaultTitle ?? 'Panel Operasional — TelyuCan'])
<body>
    <div class="tc-shell">
        <aside id="sidebar-wrapper" class="tc-sidebar">
            @include($sidebarView)
        </aside>

        <div class="tc-shell-main">
            <header class="tc-shell-topbar">
                <div class="d-flex align-items-center gap-2 gap-md-3 min-w-0">
                    <span class="fw-bold text-dark tc-display text-truncate" style="font-size: 1.05rem;">{{ $topbarTitle }}</span>
                    @if(!empty($tenantBadgeName))
                        <span class="tc-badge tc-badge-neutral d-none d-sm-inline-flex flex-shrink-0">
                            <i class="bi bi-shop"></i>{{ $tenantBadgeName }}
                        </span>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-3 flex-shrink-0">
                    <a href="{{ route('home') }}" class="btn btn-tc-ghost btn-sm d-none d-md-inline-flex align-items-center gap-1 text-decoration-none">
                        <i class="bi bi-box-arrow-up-right"></i>Lihat Beranda
                    </a>
                    <div class="dropdown">
                        <button type="button" class="btn border-0 p-0 d-flex align-items-center gap-2 text-dark" id="{{ $dropdownId ?? 'dropdownShellUser' }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <x-user-avatar :user="Auth::user()" :size="34" class="border" />
                            <span class="fw-semibold small d-none d-md-inline">{{ Auth::user()->name ?? ($fallbackUserLabel ?? 'Pengguna') }}</span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu mt-2" aria-labelledby="{{ $dropdownId ?? 'dropdownShellUser' }}">
                            <li class="d-md-none"><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-box-arrow-up-right me-2"></i>Lihat Beranda</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profil &amp; Pengaturan</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="tc-shell-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
