<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — TelyuCan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="tc-auth-shell">
        <div class="tc-auth-stage">
            <a href="{{ route('home') }}" class="tc-brand">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                    <rect width="36" height="36" rx="11" fill="#B9112E"/>
                    <path d="M9.5 11.5H26.5V15.5H20.25V26H15.75V15.5H9.5V11.5Z" fill="#FFFFFF"/>
                    <rect x="21.5" y="21.5" width="5" height="4.5" rx="1.5" fill="#F59E0B"/>
                </svg>
                <span>TelyuCan</span>
            </a>

            <div class="my-auto py-4">
                <h1 class="tc-hero-title mb-3" style="font-size: clamp(2.1rem, 3.2vw, 3rem);">Pesan dari kelas,<br>ambil tanpa antri.</h1>
                <p class="tc-hero-sub mb-4">Masuk ke akun Pelanggan, Tenant, atau Admin untuk mengelola pemesanan kantin Telkom University dalam satu sistem terpadu.</p>

                <div class="row g-3" style="max-width: 520px;">
                    <div class="col-7">
                        <div class="tc-bento-tile p-3 px-4 h-100" style="background: var(--tc-obsidian-elevated); border: 1px solid var(--tc-obsidian-border); border-radius: 20px;">
                            <div class="d-flex align-items-center gap-2 mb-2" style="color: var(--tc-amber);">
                                <i class="bi bi-wallet2 fs-5"></i>
                                <span class="fw-bold small text-white">Pembayaran Midtrans</span>
                            </div>
                            <div class="small tc-dark-copy">QRIS, GoPay, E-Wallet, dan Virtual Account terverifikasi otomatis.</div>
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
            </div>

            <div class="small" style="color: #94A3B8;">Sistem Informasi Kantin Telkom University</div>
        </div>

        <div class="tc-auth-panel">
            <div class="w-100" style="max-width: 420px;">
                <a href="{{ route('home') }}" class="text-secondary text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-4">
                    <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                </a>

                <h2 class="tc-display h3 fw-bold mb-1">Masuk ke TelyuCan</h2>
                <p class="text-secondary small mb-4">Gunakan email dan kata sandi akun Anda.</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 small mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
