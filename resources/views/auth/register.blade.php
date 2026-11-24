<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun — TelyuCan</title>
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
                <h1 class="tc-hero-title mb-3" style="font-size: clamp(2.1rem, 3.2vw, 3rem);">Satu akun untuk<br>semua kantin Tel-U.</h1>
                <p class="tc-hero-sub mb-4">Buat akun pelanggan untuk memesan lintas gerai dalam satu checkout, menerima notifikasi dapur, dan memberi ulasan menu.</p>

                <div class="tc-bento-tile p-4" style="background: var(--tc-obsidian-elevated); border: 1px solid var(--tc-obsidian-border); border-radius: 22px; max-width: 480px;">
                    <div class="tc-step-stack">
                        <div class="tc-step-pill"><span class="tc-step-num" style="color: #F59E0B;">01</span><span>Pilih menu dari TULT, GKU, atau Pujasera</span></div>
                        <div class="tc-step-pill"><span class="tc-step-num" style="color: #F59E0B;">02</span><span>Bayar cepat via Midtrans (QRIS / E-Wallet / VA)</span></div>
                        <div class="tc-step-pill"><span class="tc-step-num" style="color: #34D399;">03</span><span>Ambil di konter begitu status Siap Diambil</span></div>
                    </div>
                </div>
            </div>

            <div class="small" style="color: #94A3B8;">Sistem Informasi Kantin Telkom University</div>
        </div>

        <div class="tc-auth-panel">
            <div class="w-100" style="max-width: 440px;">
                <a href="{{ route('home') }}" class="text-secondary text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-4">
                    <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                </a>

                <h2 class="tc-display h3 fw-bold mb-1">Buat Akun Baru</h2>
                <p class="text-secondary small mb-4">Lengkapi data di bawah untuk mulai memesan.</p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 small mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
