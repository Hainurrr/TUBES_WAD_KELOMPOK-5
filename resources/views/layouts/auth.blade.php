<!doctype html>
<html lang="id">
@include('layouts.partials.head', ['defaultTitle' => $pageTitle ?? 'TelyuCan'])
<body>
    <div class="tc-auth-shell">
        <header class="tc-auth-mobile-bar">
            <a href="{{ route('home') }}" class="tc-brand" style="font-size: 1.2rem;">
                <x-brand-logo :size="30" />
                <span>TelyuCan</span>
            </a>
            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>Beranda
            </a>
        </header>

        <div class="tc-auth-stage">
            <a href="{{ route('home') }}" class="tc-brand">
                <x-brand-logo :size="36" />
                <span>TelyuCan</span>
            </a>

            <div class="my-auto py-4">
                @yield('auth-stage')
            </div>

            <div class="small" style="color: #94A3B8;">Sistem Informasi Kantin Telkom University</div>
        </div>

        <div class="tc-auth-panel">
            <div class="w-100" style="max-width: {{ $panelMaxWidth ?? '420px' }};">
                <a href="{{ route('home') }}" class="text-secondary text-decoration-none small fw-semibold d-none d-lg-inline-flex align-items-center gap-1 mb-4">
                    <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                </a>

                @yield('auth-panel')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
