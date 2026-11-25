# TelyuCan - Sistem Pemesanan Kantin Digital Telkom University

[![Live Demo](https://img.shields.io/badge/Live-Demo-B9112E?style=for-the-badge&logo=heroku&logoColor=white)](https://www.telyucan.app)

TelyuCan adalah aplikasi web manajemen dan pemesanan kantin kampus Telkom University berbasis Laravel 11. Mahasiswa dan civitas akademika dapat memilih area kantin (`TULT`, `GKU`, `Pujasera`), memesan dari beberapa gerai sekaligus dalam satu keranjang, membayar secara digital melalui Midtrans, lalu mengambil pesanan langsung di konter tanpa antri.

**Alamat Website:** [https://www.telyucan.app](https://www.telyucan.app)

---

## Fitur Utama

### Pelanggan (Customer)
- **Jelajah Area & Katalog Menu:** Cari hidangan berdasarkan nama, kategori, atau lokasi kantin (`TULT`, `GKU`, `Pujasera`) beserta indikator status gerai buka dan ketersediaan stok.
- **Keranjang Multi-Tenant & Checkout Midtrans:** Gabungkan pesanan dari beberapa gerai sekaligus dalam satu transaksi dengan pembayaran digital Midtrans Snap (QRIS, GoPay, E-Wallet, dan Virtual Account).
- **Pelacakan Pesanan & Bukti Ambil:** Pantau status pesanan mulai dari `Menunggu` -> `Dimasak` -> `Siap Diambil` -> `Selesai`, batalkan pesanan yang belum diproses, dan cetak struk dengan kode QR verifikasi.
- **Notifikasi Langsung:** Terima pembaruan otomatis saat status pesanan berubah atau siap diambil di konter.
- **Ulasan & Rating:** Berikan, ubah, atau hapus ulasan untuk menu dari pesanan yang telah selesai (dilengkapi filter kata tidak pantas).
- **Pengaturan Profil:** Kelola nama, nomor identitas (NIM/NIP), kata sandi, dan foto profil yang tersimpan permanen di Cloudinary.
- **Antarmuka Responsif Mobile:** Navigasi adaptif, kartu keranjang dan riwayat khusus layar ponsel, serta kontrol sentuh yang ramah perangkat mobile.

### Tenant (Pengelola Gerai)
- **Layar Dapur (Kitchen Display System):** Kelola tiket pesanan yang sudah terbayar dan perbarui status pesanan secara langsung (`Terima & Masak`, `Siap Diambil`, `Selesai`, `Batalkan`).
- **Manajemen Menu & Stok:** Tambah, ubah, dan hapus menu beserta pengaturan harga, stok harian, kategori, unggah foto hidangan, dan ekspor daftar menu PDF.
- **Status Operasional Gerai:** Buka atau tutup gerai secara instan dengan satu tombol kontrol.
- **Riwayat Penjualan & Transaksi Manual:** Catat pesanan langsung di konter (walk-in), saring pendapatan berdasarkan rentang tanggal, serta ekspor laporan penjualan dalam format PDF.
- **Ulasan Pelanggan:** Pantau rata-rata rating dan masukan pelanggan terhadap menu gerai.

### Admin
- **Dashboard Ringkasan:** Pantau total gerai terdaftar beserta status operasional buka/tutup.
- **Manajemen Tenant:** Tambah, sunting (terintegrasi data wilayah Indonesia), hapus data gerai, dan ekspor laporan tenant dalam format PDF.
- **Manajemen Pengguna:** Kelola akun pelanggan, tenant, dan admin beserta peran serta data identitas.
- **Moderasi & Ekspor Ulasan:** Cari, tinjau, hapus ulasan yang tidak sesuai ketentuan, serta ekspor rekap ulasan ke format CSV.

---

## Arsitektur & Keamanan

- **Backend Modular:** Logika bisnis dipisahkan ke service (`app/Services/`: `CartService`, `OrderCheckoutService`, `MediaUploadService`, `HolidayService`) dan validasi terpusat menggunakan Form Request (`app/Http/Requests/`).
- **Frontend Modular:** Stylesheet terorganisasi di `resources/css/{base,components,layouts}/` dan interaksi terpisah ke 11 modul ES di `resources/js/modules/` yang dibundle oleh Vite.
- **Middleware & Kontrol Akses:** Menggunakan `SecurityHeaders` global (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`), pembatasan akses berbasis peran (`role`), verifikasi kepemilikan gerai (`has.tenant`), dan `trustProxies` untuk deployment di balik reverse proxy.
- **Keamanan Transaksi & Endpoint:** Rate limiting pada login, registrasi, checkout, ulasan, dan webhook; kalkulasi total harga dari database server (mencegah manipulasi harga session); verifikasi tanda tangan SHA-512 dan idempotensi pengembalian stok pada webhook Midtrans; serta sanitasi formula pada ekspor CSV.
- **Halaman Status HTTP Kustom (`403`, `404`, `500`, `503`):** Dirancang standalone di `resources/views/errors/` tanpa ketergantungan database, autentikasi, session, Vite, maupun CDN eksternal agar tetap tampil saat terjadi gangguan layanan.

---

## Akun Testing (Default)

Gunakan akun berikut untuk mencoba aplikasi setelah menjalankan seeder (`TelyuCanSeeder`):

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@gmail.com` | `password` |
| **Tenant 1 (TULT)** | `tult@gmail.com` | `password` |
| **Tenant 2 (GKU)** | `gku@gmail.com` | `password` |
| **Tenant 3 (Pujasera)** | `pujasera@gmail.com` | `password` |
| **Customer** | `customer@gmail.com` | `password` |

---

## Teknologi yang Digunakan

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.2+, Laravel 11 |
| **Frontend** | Blade Templates, Bootstrap 5.3, Vite 5, Modular CSS & ES Modules |
| **Tipografi** | Self-hosted Variable Fonts (`Archivo`, `Figtree`, `Chivo Mono`) |
| **Pembayaran** | Midtrans Snap API (QRIS, E-Wallet, Virtual Account) |
| **Penyimpanan Media** | Cloudinary (`cloudinary-labs/cloudinary-laravel`) |
| **Database** | MySQL (Lokal), PostgreSQL / JawsDB (Heroku) |
| **Pengujian** | PHPUnit 10.5 (Feature & Unit Tests) |
| **Server & Deploy** | Apache (`heroku-php-apache2`), GitHub Auto-Deploy ke Heroku |

---

## Panduan Instalasi (Lokal)

### 1. Clone & Install Dependensi
```bash
git clone https://github.com/Hainurrr/TUBES_WAD_KELOMPOK-5.git
cd TUBES_WAD_KELOMPOK-5
composer install
```
`composer install` otomatis menjalankan `npm install` dan `npm run build` melalui script `post-install-cmd`. Jika perlu memasang ulang paket frontend secara terpisah, jalankan `npm install`.

### 2. Konfigurasi Environment
Salin file `.env.example` ke `.env` lalu buat application key:
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan variabel penting di `.env`:
- **Database:** `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE=telyucan`, `DB_USERNAME`, `DB_PASSWORD`
- **Midtrans:** `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION`
- **Cloudinary:** `CLOUDINARY_URL`

Pastikan database MySQL `telyucan` sudah dibuat secara lokal.

### 3. Migrasi, Seeding, & Build Aset
```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
```

### 4. Jalankan Aplikasi
Untuk pengembangan lokal dengan hot-reload Vite, buka dua terminal:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Aplikasi dapat diakses melalui `http://127.0.0.1:8000`.

---

## Pengujian Otomatis

Jalankan seluruh pengujian keamanan backend, middleware RBAC, webhook Midtrans, dan halaman status HTTP kustom dengan perintah:
```bash
php artisan test
```

---

## Deployment (GitHub Auto-Deploy ke Heroku)

Repositori branch `main` terhubung ke pipeline auto-deploy Heroku dengan konfigurasi berikut:
- **Procfile:** Menjalankan Apache web server (`vendor/bin/heroku-php-apache2 public/`) dan fase `release` (`php artisan migrate --force && php artisan view:clear && php artisan config:clear`).
- **Build Aset Otomatis:** `composer.json` menjalankan `npm install --no-audit --no-fund` dan `npm run build` pada tahap `post-install-cmd` sehingga bundle Vite terbaru otomatis dikompilasi saat deploy.
- **Aset Font Self-Hosted:** Berkas font `.woff2` di `public/fonts/` disajikan langsung tanpa ketergantungan CDN pihak ketiga.
- **Database & Media:** Menggunakan add-on database Heroku, Cloudinary untuk unggahan gambar permanen, serta Midtrans untuk pembayaran digital.
- **Custom Domain:** `www.telyucan.app` (HTTPS aktif).

---

## Tim Pengembang (Kelompok 5)

Dikembangkan untuk memenuhi Tugas Besar mata kuliah **Web Application Development (WAD)**, Telkom University.
