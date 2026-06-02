<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- MANPROSI_TELYUCAN_IMPROVEMENT - CSRF token untuk AJAX request -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
    <!-- MANPROSI_TELYUCAN_IMPROVEMENT - Stack untuk script head (Midtrans Snap.js) -->
    @stack('head')
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

    {{-- MANPROSI_TELYUCAN_IMPROVEMENT - Pop-up peringatan disclaimer proyek tugas kuliah --}}
    <div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                {{-- Header dengan gradient --}}
                <div class="text-center py-4" style="background: linear-gradient(135deg, #800000 0%, #b30000 100%);">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25 mb-3" style="width: 64px; height: 64px;">
                        <i class="bi bi-exclamation-triangle-fill fs-2 text-white"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-0" id="disclaimerModalLabel">⚠️ Peringatan / Disclaimer</h5>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">Website Ini Hanya Proyek Tugas Kuliah</p>
                    <p class="text-secondary mb-3">
                        <strong>TelyuCan</strong> adalah website <strong>simulasi marketplace kantin</strong> yang dibuat untuk keperluan 
                        <strong>Tugas Besar mata kuliah Pengembangan Aplikasi Web (WAD)</strong> di Telkom University.
                    </p>
                    <div class="alert alert-warning rounded-3 text-start small mb-3">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Penting:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Semua transaksi bersifat <strong>simulasi (Sandbox)</strong> dan <strong>tidak melibatkan uang nyata</strong>.</li>
                            <li>Data yang ditampilkan adalah <strong>data dummy</strong> untuk keperluan demonstrasi.</li>
                            <li>Pembayaran menggunakan <strong>Midtrans Sandbox</strong> (mode testing).</li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-maroon rounded-pill px-5 py-2 fw-bold" data-bs-dismiss="modal" id="btnDisclaimerAccept">
                        <i class="bi bi-check-circle me-2"></i>Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
        });

        // MANPROSI_TELYUCAN_IMPROVEMENT - Tampilkan disclaimer popup sekali per session
        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('disclaimer_accepted')) {
                var disclaimerModal = new bootstrap.Modal(document.getElementById('disclaimerModal'));
                disclaimerModal.show();

                document.getElementById('btnDisclaimerAccept').addEventListener('click', function() {
                    sessionStorage.setItem('disclaimer_accepted', 'true');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>