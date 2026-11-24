<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TelyuCan — Sistem Informasi Kantin Telkom University')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="{{ request()->routeIs('home') ? 'tc-home-body' : '' }}">

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('cart.checkout') && !request()->routeIs('payment.page'))
    <footer class="tc-footer mt-auto">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <a href="{{ route('home') }}" class="tc-brand mb-3">
                        <svg width="34" height="34" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                            <rect width="36" height="36" rx="11" fill="#B9112E"/>
                            <path d="M9.5 11.5H26.5V15.5H20.25V26H15.75V15.5H9.5V11.5Z" fill="#FFFFFF"/>
                            <rect x="21.5" y="21.5" width="5" height="4.5" rx="1.5" fill="#F59E0B"/>
                        </svg>
                        <span>TelyuCan</span>
                    </a>
                    <p class="small mb-3 tc-measure tc-footer-copy">
                        Platform pemesanan dan informasi kantin Telkom University. Pesan makanan dari kelas, bayar digital melalui Midtrans, dan ambil langsung di konter tanpa antri.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="tc-badge" style="background: rgba(255,255,255,0.08); color: #E2E8F0;">Ambil di Konter</span>
                        <span class="tc-badge" style="background: rgba(255,255,255,0.08); color: #E2E8F0;">Pembayaran Midtrans</span>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <h6>Navigasi</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('menus.index') }}">Katalog Menu</a></li>
                        <li><a href="{{ route('search') }}">Lokasi Kantin</a></li>
                        @auth
                            @if(Auth::user()->role !== 'admin')
                                <li><a href="{{ route('orders.history') }}">Riwayat Pesanan</a></li>
                            @endif
                        @else
                            <li><a href="{{ route('login') }}">Masuk / Daftar</a></li>
                        @endauth
                    </ul>
                </div>

                <div class="col-6 col-lg-4">
                    <h6>Area Kantin</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('search', ['location' => 'TULT']) }}">Kantin Gedung TULT</a></li>
                        <li><a href="{{ route('search', ['location' => 'GKU']) }}">Kantin Gedung GKU</a></li>
                        <li><a href="{{ route('search', ['location' => 'Pujasera']) }}">Area Pujasera Kampus</a></li>
                    </ul>
                </div>
            </div>

            <div class="tc-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
                <span>&copy; {{ date('Y') }} TelyuCan — Sistem Informasi Kantin Telkom University.</span>
                <span>Pemesanan Dine-in &amp; Takeaway Kampus</span>
            </div>
        </div>
    </footer>
    @endif

    @include('components.floating-cart')
    @include('components.auth-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
