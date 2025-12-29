# 📋 TelyuCan - Progress Report 40%
## Web Application Development - Telkom University

---

## 📌 Informasi Proyek

| Aspek | Detail |
|-------|--------|
| **Nama Proyek** | TelyuCan (Telyu Canteen) |
| **Mata Kuliah** | Web Application Development |
| **Progress** | 40% |
| **Tanggal Laporan** | 29 Desember 2024 |

---

## 👥 Anggota Kelompok

| No | Nama | Peran | Tanggung Jawab Utama |
|----|------|-------|----------------------|
| 1 | **Hainur Fadillah** | Ketua Kelompok | Manajemen Transaksi/Pesanan |
| 2 | Helmi Fauzan | Anggota | Manajemen Data Tenant |
| 3 | Galuh Shafa Auliana Rahman | Anggota | Manajemen Menu Makanan & Minuman |
| 4 | Aprilia Anggraeni Putri Soehaja | Anggota | Manajemen Riwayat & Laporan Penjualan |
| 5 | Radja Ilham Al-Ghozali | Anggota | Manajemen Ulasan & Rating |

---

## 🛠️ Tech Stack

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| PHP | >= 8.2 | Backend Language |
| Laravel | 11.x | PHP Framework |
| MySQL/MariaDB | 8.0+ | Database Server |
| Node.js | 18+ | Frontend Build |
| Vite | 5.x | Asset Bundler |
| Bootstrap | 5.3 | CSS Framework |

---

## ✅ Fitur yang Sudah Selesai (Progress 40%)

### 🔐 1. Sistem Autentikasi
- [x] **Registrasi User** - Form pendaftaran untuk pelanggan umum
- [x] **Login/Logout** - Sistem autentikasi multi-role (Admin, Tenant, Customer)
- [x] **Role-based Access Control** - Middleware untuk pembatasan akses berdasarkan role

### 🏪 2. Manajemen Tenant (Admin)
- [x] **CRUD Tenant** - Tambah, lihat, ubah, hapus data tenant
- [x] **Informasi Lokasi** - Data lokasi kantin (TULT, GKU, Pujasera, dll)
- [x] **Export PDF** - Cetak daftar tenant dalam format PDF
- [x] **Dashboard Admin** - Halaman utama admin dengan statistik

### 🍽️ 3. Manajemen Menu (Tenant)
- [x] **CRUD Menu** - Tambah, lihat, ubah, hapus menu makanan/minuman
- [x] **Kategori Menu** - Pengelompokan menu berdasarkan kategori
- [x] **Upload Gambar** - Unggah foto menu
- [x] **Toggle Ketersediaan** - Status tersedia/habis
- [x] **Export PDF** - Cetak daftar menu dalam format PDF

### 🛒 4. Sistem Pemesanan (Customer)
- [x] **Browse Menu** - Lihat semua menu dari berbagai tenant
- [x] **Filter Menu** - Filter berdasarkan lokasi dan kategori
- [x] **Pencarian** - Cari menu atau tenant
- [x] **Keranjang Belanja** - Tambah/hapus item ke keranjang
- [x] **Checkout** - Proses pemesanan dengan validasi stok

### 📜 5. Riwayat Pesanan
- [x] **Riwayat Customer** - Lihat daftar pesanan pelanggan
- [x] **Riwayat Tenant** - Lihat daftar pesanan masuk untuk tenant
- [x] **Update Status** - Tenant dapat mengubah status pesanan (Pending → Proses → Siap → Selesai)
- [x] **Cetak Struk** - Cetak detail pesanan

### ⭐ 6. Sistem Review & Rating
- [x] **Buat Review** - Customer dapat memberi ulasan setelah pesanan selesai
- [x] **Rating Bintang** - Sistem rating 1-5 bintang
- [x] **Filter Kata Kasar** - Integrasi API untuk filter konten tidak pantas
- [x] **Lihat Review Tenant** - Tenant dapat melihat ulasan untuk toko mereka
- [x] **Moderasi Admin** - Admin dapat menghapus review tidak pantas

---

## 📊 Struktur Database

### Tabel yang Sudah Dibuat:

| Tabel | Deskripsi | Status |
|-------|-----------|--------|
| `users` | Data user (admin, tenant, customer) | ✅ Selesai |
| `tenants` | Data tenant/penjual | ✅ Selesai |
| `categories` | Kategori menu | ✅ Selesai |
| `menus` | Data menu makanan/minuman | ✅ Selesai |
| `orders` | Data pesanan | ✅ Selesai |
| `order_details` | Detail item pesanan | ✅ Selesai |
| `reviews` | Ulasan pelanggan | ✅ Selesai |
| `notifications` | Sistem notifikasi | ✅ Selesai |

---

## 📁 Struktur File Utama

```
WAD_kelompok5/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php        # Dashboard admin
│   │   │   ├── AdminUserController.php    # CRUD user
│   │   │   ├── AuthController.php         # Login/Register
│   │   │   ├── OrderController.php        # Transaksi pesanan
│   │   │   ├── ReviewController.php       # Sistem review
│   │   │   ├── TenantController.php       # CRUD tenant
│   │   │   └── Tenant/
│   │   │       ├── MenuController.php     # CRUD menu tenant
│   │   │       └── TenantOrderController.php
│   │   └── Middleware/
│   │       └── CheckRole.php              # Role middleware
│   ├── Models/
│   │   ├── User.php
│   │   ├── Tenant.php
│   │   ├── Menu.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── OrderDetail.php
│   │   └── Review.php
│   └── Helpers/
│       └── BadWordHelper.php              # Filter kata kasar
├── resources/views/
│   ├── admin/                             # View admin panel
│   ├── auth/                              # View login/register
│   ├── layouts/                           # Layout templates
│   ├── menus/                             # View menu publik
│   ├── orders/                            # View pesanan
│   ├── tenant/                            # View tenant panel
│   └── welcome.blade.php                  # Landing page
├── database/
│   ├── migrations/                        # Schema database
│   └── seeders/                           # Data dummy
└── routes/
    └── web.php                            # Route definitions
```

---

## 🔐 Akun Demo (Testing)

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@gmail.com | password |
| **Tenant TULT** | tult@gmail.com | password |
| **Tenant GKU** | gku@gmail.com | password |
| **Tenant Pujasera** | pujasera@gmail.com | password |
| **Customer** | customer@gmail.com | password |

---

## 🚀 Cara Menjalankan Aplikasi

### Prasyarat
- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18
- MySQL/MariaDB

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/Hainurrr/TUBES_WAD_KELOMPOK-5.git
cd TUBES_WAD_KELOMPOK-5

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di file .env
# DB_DATABASE=telyucan
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & Seeding
php artisan migrate:fresh --seed

# 6. Storage link (untuk gambar)
php artisan storage:link

# 7. Jalankan server (2 terminal)
php artisan serve        # Terminal 1
npm run dev              # Terminal 2
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## 📈 Rencana Pengembangan (60% - 100%)

### Tahap Selanjutnya:
- [ ] Integrasi Payment Gateway
- [ ] Sistem Notifikasi Real-time (WebSocket)
- [ ] Fitur Favorit Menu
- [ ] Sistem Voucher/Diskon
- [ ] Dashboard Analytics yang lebih lengkap
- [ ] Mobile Responsive Optimization
- [ ] API Endpoint untuk Mobile App

---

## 📞 Kontak

Untuk pertanyaan atau demonstrasi, silakan hubungi ketua kelompok:
- **Nama:** Hainur Fadillah
- **Peran:** Ketua Kelompok - Manajemen Transaksi/Pesanan

---

**© 2024 - Kelompok 5 WAD**
