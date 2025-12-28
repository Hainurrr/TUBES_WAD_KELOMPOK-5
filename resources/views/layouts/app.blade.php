<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>TelyuCan - Sistem Informasi Kantin Telkom University</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa; /* Abu-abu muda lembut */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1; /* Agar footer selalu di bawah */
        }
        
        /* Custom Animations */
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .zoom-effect {
            animation: zoom 20s infinite alternate;
        }
        
        @keyframes zoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
    </style>
</head>
<body>

    @include('layouts.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-top py-5 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded text-white d-flex align-items-center justify-content-center fw-bold" 
                             style="width: 32px; height: 32px; background-color: #800000;">T</div>
                        <span class="fs-4 fw-bold" style="color: #800000;">TelyuCan</span>
                    </div>
                    <p class="text-secondary small">
                        Platform pemesanan dan informasi kantin universitas yang memudahkan civitas akademika.
                    </p>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold mb-3">Layanan</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Pesan Makanan</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Daftar Kantin</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Info Promo</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold mb-3">Bantuan</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Pusat Bantuan</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Syarat & Ketentuan</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3">Hubungi Kami</h6>
                    <p class="small text-secondary mb-1">Gedung Layanan, Lt. 1</p>
                    <p class="small text-secondary">support@telyucan.ac.id</p>
                </div>
            </div>
            <div class="border-top mt-4 pt-4 text-center small text-secondary">
                &copy; {{ date('Y') }} TelyuCan. All rights reserved.
            </div>
        </div>
    </footer>

    @include('components.floating-cart')
    @include('components.auth-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
        });
    </script>
    @stack('scripts')
</body>
</html>