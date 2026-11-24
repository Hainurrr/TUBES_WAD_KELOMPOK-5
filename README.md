# TelyuCan - Sistem Pemesanan Kantin Digital Telkom University

[![Live Demo](https://img.shields.io/badge/Live-Demo-B9112E?style=for-the-badge&logo=heroku&logoColor=white)](https://www.telyucan.app)

TelyuCan adalah aplikasi web manajemen dan pemesanan kantin kampus Telkom University berbasis Laravel 11. Mahasiswa dan civitas akademika dapat memilih area kantin (`TULT`, `GKU`, `Pujasera`), memesan dari beberapa gerai sekaligus dalam satu keranjang, membayar secara digital melalui Midtrans, lalu mengambil pesanan langsung di konter tanpa antri.

**Alamat Website:** [https://www.telyucan.app](https://www.telyucan.app)

## Fitur Utama

### Pelanggan (Customer)
- **Jelajah Area & Katalog Menu:** Cari hidangan berdasarkan nama, kategori, atau lokasi kantin (`TULT`, `GKU`, `Pujasera`) beserta indikator status gerai buka dan ketersediaan stok.
- **Keranjang Multi-Tenant & Checkout Midtrans:** Gabungkan pesanan dari beberapa gerai sekaligus dalam satu transaksi dengan pembayaran digital Midtrans Snap (QRIS, GoPay, E-Wallet, dan Virtual Account).
- **Pelacakan Pesanan Real-Time:** Pantau status pesanan mulai dari `Menunggu` -> `Dimasak` -> `Siap Diambil` -> `Selesai`, lengkap dengan pembatalan pesanan yang belum diproses dan cetak struk.
- **Notifikasi Langsung:** Terima pembaruan otomatis saat status pesanan berubah atau siap diambil di konter.
- **Ulasan & Rating:** Berikan ulasan dan penilaian untuk menu dari pesanan yang telah selesai.
- **Pengaturan Profil:** Kelola nama, nomor identitas, kata sandi, dan foto profil (tersimpan permanen melalui Cloudinary).

### Tenant (Pengelola Gerai)
- **Layar Dapur (Kitchen Display System):** Kelola tiket pesanan yang sudah terbayar dan perbarui status pesanan secara langsung (`Terima`, `Masak`, `Siap Diambil`, `Selesai`).
- **Manajemen Menu & Stok:** Tambah, ubah, dan hapus menu beserta pengaturan harga, stok harian, kategori, dan unggah foto hidangan.
- **Status Operasional Gerai:** Buka atau tutup gerai secara instan dengan satu tombol kontrol.
- **Riwayat Penjualan & Transaksi Manual:** Catat pesanan langsung di konter (walk-in), pantau ringkasan pendapatan, serta ekspor laporan penjualan dalam format PDF.
- **Ulasan Pelanggan:** Pantau rata-rata rating dan masukan pelanggan terhadap menu gerai.

### Admin
- **Dashboard Ringkasan:** Pantau total gerai terdaftar beserta status operasional aktif/tutup.
- **Manajemen Tenant:** Tambah, sunting (dengan integrasi data wilayah Indonesia), hapus data gerai, dan ekspor laporan tenant dalam format PDF.
- **Manajemen Pengguna:** Kelola akun pelanggan, tenant, dan admin beserta peran serta data identitas.
- **Moderasi Ulasan:** Tinjau dan hapus ulasan yang tidak sesuai ketentuan.

---

## Akun Testing (Default)

Gunakan akun berikut untuk mencoba aplikasi setelah menjalankan seeder:

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
| **Backend** | PHP 8.2, Laravel 11 |
| **Frontend** | Blade Templates, Bootstrap 5, Vite, Custom Bento Design System |
| **Tipografi** | Self-hosted Variable Fonts (`Archivo`, `Figtree`, `Chivo Mono`) |
| **Pembayaran** | Midtrans Snap API (QRIS, E-Wallet, Virtual Account) |
| **Penyimpanan Media** | Cloudinary (`cloudinary-laravel`) |
| **Database** | MySQL (Lokal), PostgreSQL / JawsDB (Heroku) |
| **Server & Deploy** | Apache (`heroku-php-apache2`), GitHub Auto-Deploy ke Heroku |

---

## Panduan Instalasi (Lokal)

### 1. Clone & Install Dependensi
```bash
git clone https://github.com/Hainurrr/TUBES_WAD_KELOMPOK-5.git
cd TUBES_WAD_KELOMPOK-5
composer install
npm install
```

### 2. Konfigurasi Environment
Salin file `.env.example` ke `.env`, buat application key, lalu sesuaikan kredensial database, Midtrans, dan Cloudinary:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan database MySQL bernama `telyucan` sudah tersedia secara lokal.

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

## Deployment (GitHub Auto-Deploy ke Heroku)

Repositori branch `main` terhubung ke pipeline auto-deploy Heroku dengan konfigurasi berikut:
- **Procfile:** Menjalankan Apache web server (`vendor/bin/heroku-php-apache2 public/`) dan fase `release` (`php artisan migrate --force && php artisan view:clear && php artisan config:clear`).
- **Build Aset Otomatis:** `composer.json` menjalankan `npm install` dan `npm run build` pada tahap `post-install-cmd` sehingga bundle Vite terbaru otomatis dikompilasi saat deploy.
- **Aset Font Self-Hosted:** Berkas font `.woff2` di `public/fonts/` disajikan langsung tanpa ketergantungan CDN pihak ketiga.
- **Database & Media:** Menggunakan add-on database Heroku, Cloudinary untuk unggahan gambar permanen, serta Midtrans untuk pembayaran digital.
- **Custom Domain:** `www.telyucan.app` (HTTPS aktif).

---

## Tim Pengembang (Kelompok 5)

Dikembangkan untuk memenuhi Tugas Besar mata kuliah **Web Application Development (WAD)**, Telkom University.
