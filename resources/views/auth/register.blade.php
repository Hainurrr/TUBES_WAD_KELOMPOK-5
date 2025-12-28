<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TelyuCan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .split-screen {
            min-height: 100vh;
        }
        .bg-image {
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=2080&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
        .btn-maroon {
            background-color: #800000;
            color: white;
        }
        .btn-maroon:hover {
            background-color: #600000;
            color: white;
        }
        .text-maroon {
            color: #800000;
        }
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="row g-0 split-screen">
        <div class="col-lg-6 d-none d-lg-block bg-image"></div>
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-light">
            <div class="w-50">
                <div class="mb-4">
                    <a href="{{ route('home') }}" class="text-secondary fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
                <h2 class="mb-4 fw-bold text-maroon">Daftar Akun</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="identity_number" class="form-label">Identity Number (NIM/NIP/Phone) <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="identity_number" name="identity_number" value="{{ old('identity_number') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100 py-2">Daftar Akun</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-maroon fw-bold">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
