@extends('layouts.auth', ['pageTitle' => 'Masuk — TelyuCan', 'panelMaxWidth' => '420px'])

@section('title', 'Masuk — TelyuCan')

@section('auth-stage')
    <h1 class="tc-hero-title mb-3" style="font-size: clamp(2.1rem, 3.2vw, 3rem);">Pesan dari kelas,<br>ambil tanpa antri.</h1>
    <p class="tc-hero-sub mb-4">Masuk ke akun Pelanggan, Tenant, atau Admin untuk mengelola pemesanan kantin Telkom University dalam satu sistem terpadu.</p>

    <div class="row g-3" style="max-width: 520px;">
        <div class="col-7">
            <div class="tc-bento-tile p-3 px-4 h-100" style="background: var(--tc-obsidian-elevated); border: 1px solid var(--tc-obsidian-border); border-radius: 20px;">
                <div class="d-flex align-items-center gap-2 mb-2" style="color: var(--tc-amber);">
                    <i class="bi bi-wallet2 fs-5"></i>
                    <span class="fw-bold small text-white">Pembayaran Non-Tunai</span>
                </div>
                <div class="small tc-dark-copy">Proses pembayaran digital cepat dan terverifikasi otomatis.</div>
            </div>
        </div>
        <div class="col-5">
            <div class="tc-bento-tile p-3 px-4 h-100 d-flex flex-column justify-content-between" style="background: var(--tc-crimson); border-radius: 20px;">
                <i class="bi bi-geo-alt-fill text-white fs-5 mb-2"></i>
                <div>
                    <div class="fw-bold small text-white">TULT, GKU &amp; Pujasera</div>
                    <div class="small" style="color: rgba(255,255,255,0.88);">Ambil langsung di konter</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('auth-panel')
    <h2 class="tc-display h3 fw-bold mb-1">Masuk ke TelyuCan</h2>
    <p class="text-secondary small mb-4">Gunakan email dan kata sandi akun Anda.</p>

    <x-flash-alerts :show-validation="true" />

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
        </div>
        <div class="form-check mb-4">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label small text-secondary" for="remember">Ingat saya di perangkat ini</label>
        </div>
        <button type="submit" class="btn btn-tc-primary w-100 py-3">Masuk</button>
    </form>

    <p class="mt-4 text-center small text-secondary mb-0">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-maroon fw-bold text-decoration-none">Daftar sekarang</a>
    </p>
@endsection
