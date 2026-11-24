<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Admin — TelyuCan')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <div class="tc-shell">
        <aside id="sidebar-wrapper" class="tc-sidebar">
            @include('layouts.admin-sidebar')
        </aside>

        <div class="tc-shell-main">
            <header class="tc-shell-topbar">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold text-dark tc-display" style="font-size: 1.05rem;">Panel Administrasi Kantin</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-tc-ghost btn-sm d-none d-md-inline-flex align-items-center gap-1 text-decoration-none">
                        <i class="bi bi-box-arrow-up-right"></i>Lihat Beranda
                    </a>
                    <div class="dropdown">
                        <button type="button" class="btn border-0 p-0 d-flex align-items-center gap-2 text-dark" id="dropdownAdminUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar && Auth::user()->avatar !== 'default_avatar.png' ? '/storage/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=B9112E&color=fff' }}"
                                 alt="Foto profil" width="34" height="34" class="rounded-circle object-fit-cover border">
                            <span class="fw-semibold small d-none d-md-inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu mt-2" aria-labelledby="dropdownAdminUser">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            const toggleBtn = document.getElementById('sidebarToggleInternal');
            const toggleIcon = document.getElementById('sidebarToggleIcon');

            if (toggleBtn && sidebar && toggleIcon) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    toggleIcon.className = isCollapsed ? 'bi bi-arrow-right-circle fs-5' : 'bi bi-arrow-left-circle fs-5';
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
