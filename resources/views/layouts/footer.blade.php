<footer class="tc-footer mt-auto">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <a href="{{ route('home') }}" class="tc-brand mb-3">
                    <x-brand-logo :size="34" />
                    <span>TelyuCan</span>
                </a>
                <p class="small mb-3 tc-measure tc-footer-copy">
                    Platform pemesanan dan informasi kantin Telkom University. Pesan makanan dari kelas, bayar non-tunai, dan ambil langsung di konter tanpa antri.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="tc-badge tc-badge-glass">Ambil di Konter</span>
                    <span class="tc-badge tc-badge-glass">Pembayaran Non-Tunai</span>
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
