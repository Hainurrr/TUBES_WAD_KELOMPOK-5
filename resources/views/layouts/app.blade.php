<!doctype html>
<html lang="id">
@include('layouts.partials.head', ['defaultTitle' => 'TelyuCan — Sistem Informasi Kantin Telkom University'])
<body class="{{ request()->routeIs('home') ? 'tc-home-body' : '' }}">

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('cart.checkout') && !request()->routeIs('payment.page'))
        @include('layouts.footer')
    @endif

    @include('components.floating-cart')
    @include('components.auth-modal')
    @include('components.academic-notice-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
