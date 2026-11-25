@extends('layouts.auth', ['pageTitle' => 'Daftar Akun — TelyuCan', 'panelMaxWidth' => '440px'])

@section('title', 'Daftar Akun — TelyuCan')

@section('auth-stage')
    <h1 class="tc-hero-title mb-3" style="font-size: clamp(2.1rem, 3.2vw, 3rem);">Satu akun untuk<br>semua kantin Tel-U.</h1>
    <p class="tc-hero-sub mb-4">Buat akun pelanggan untuk memesan lintas gerai dalam satu checkout, menerima notifikasi dapur, dan memberi ulasan menu.</p>

    <div class="tc-bento-tile p-4" style="background: var(--tc-obsidian-elevated); border: 1px solid var(--tc-obsidian-border); border-radius: 22px; max-width: 480px;">
        <div class="tc-step-stack">
            <div class="tc-step-pill"><span class="tc-step-num" style="color: #F59E0B;">01</span><span>Pilih menu dari TULT, GKU, atau Pujasera</span></div>
            <div class="tc-step-pill"><span class="tc-step-num" style="color: #F59E0B;">02</span><span>Bayar cepat secara non-tunai</span></div>
            <div class="tc-step-pill"><span class="tc-step-num" style="color: #34D399;">03</span><span>Ambil di konter begitu status Siap Diambil</span></div>
        </div>
    </div>
@endsection

@section('auth-panel')
    <h2 class="tc-display h3 fw-bold mb-1">Buat Akun Baru</h2>
    <p class="text-secondary small mb-4">Lengkapi data di bawah untuk mulai memesan.</p>

    <x-flash-alerts :show-validation="true" />

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Raka Pratama" required autofocus>
        </div>
        <div class="mb-3">
            <label for="identity_number" class="form-label">Nomor Identitas — NIM / NIP / No. HP <span class="text-muted fw-normal">(Opsional)</span></label>
            <input type="text" class="form-control" id="identity_number" name="identity_number" value="{{ old('identity_number') }}" placeholder="Contoh: 1202220001">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Min. 8 karakter" required>
            </div>
            <div class="col-sm-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
            </div>
        </div>
        <button type="submit" class="btn btn-tc-primary w-100 py-3">Daftar Akun</button>
    </form>

    <p class="mt-4 text-center small text-secondary mb-0">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-maroon fw-bold text-decoration-none">Masuk di sini</a>
    </p>
@endsection
