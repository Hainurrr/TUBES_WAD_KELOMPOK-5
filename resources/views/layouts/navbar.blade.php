@php
    $homeRoute = route('home');
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $homeRoute = route('admin.dashboard');
        } elseif (Auth::user()->role === 'tenant') {
            $homeRoute = route('tenant.dashboard');
        }
    }

    $navCartCount = 0;
    if (Auth::check() && Auth::user()->role === 'customer') {
        foreach (session('cart', []) as $tGroup) {
            foreach ($tGroup['items'] as $cItem) {
                $navCartCount += $cItem['qty'];
            }
        }
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-dark tc-navbar sticky-top">
    <div class="container">
        <a class="tc-brand" href="{{ $homeRoute }}">
            <x-brand-logo :size="34" />
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

                <div class="d-flex align-items-center justify-content-end gap-2 tc-nav-actions">
                    @auth
                        @if(Auth::user()->role === 'customer')
                            <a href="{{ route('cart.index') }}" class="tc-nav-icon-btn position-relative text-decoration-none" aria-label="Keranjang belanja" title="Keranjang Belanja">
                                <i class="bi bi-bag"></i>
                                @if($navCartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill tc-mono" style="background-color: var(--tc-crimson); font-size: 0.68rem;">
                                        {{ $navCartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        @include('layouts.partials.navbar-notifications')
                        @include('layouts.partials.navbar-user-menu')
                    @else
                        <a href="{{ route('login') }}" class="btn btn-tc-primary px-4 py-2 text-decoration-none text-nowrap flex-shrink-0 tc-nav-guest-cta">
                            Masuk / Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
