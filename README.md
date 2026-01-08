# TelyuCan - Kantin Digital Telkom University

[![Live Demo](https://img.shields.io/badge/Live-Demo-2ea44f?style=for-the-badge&logo=heroku&logoColor=white)](https://www.telyucan.app)

Aplikasi web untuk manajemen kantin (TelyuCanteen) berbasis Laravel 11. Mempermudah mahasiswa memesan makanan dan membantu tenant mengelola pesanan.

🔗 **Link Website:** [https://www.telyucan.app](https://www.telyucan.app)

## 📱 Fitur Utama

### 👤 Pelanggan (Customer)
- **Menu Browsing**: Cari makanan berdasarkan nama, kategori, atau tenant favorit.
- **Keranjang & Checkout**: Pesan dari beberapa tenant sekaligus dalam satu transaksi (Split Order).
- **Riwayat Pesanan**: Pantau status pesanan secara real-time (Pending -> Cooking -> Ready -> Completed).
- **Notifikasi Live**: Dapatkan notifikasi saat status pesanan berubah.
- **Review & Rating**: Beri ulasan dan bintang untuk menu yang sudah dibeli.
- **Profile Settings**: Ubah foto profil, nama, dan password.

### 🏪 Tenant (Penjual)
- **Kitchen Dashboard**: Kelola pesanan masuk, ubah status pesanan (Terima/Tolak, Masak, Siap).
- **Manajemen Menu**: Tambah, edit, hapus menu, dan atur stok/ketersediaan.
- **Buka/Tutup Toko**: Atur status operasional toko dengan satu klik.
- **Laporan PDF**: Download laporan riwayat penjualan dalam format PDF.

### 🛡️ Admin
- **Manajemen Tenant**: Tambah dan kelola data tenant kantin.
- **Manajemen User**: Kelola akun pengguna (Customer/Tenant).
- **Moderasi Review**: Hapus review yang tidak pantas.
- **Laporan Tenant PDF**: Download daftar tenant dan performanya.

---

## 🔐 Akun Testing (Default)

Gunakan akun berikut untuk mencoba aplikasi:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@gmail.com` | `password` |
| **Tenant 1 (TULT)** | `tult@gmail.com` | `password` |
| **Tenant 2 (GKU)** | `gku@gmail.com` | `password` |
| **Tenant 3 (Pujasera)** | `pujasera@gmail.com` | `password` |
| **Customer** | `customer@gmail.com` | `password` |

---

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | PHP 8.2, Laravel 11 |
| **Frontend** | Blade Templates, Bootstrap 5 |
| **Database** | MySQL (Local), PostgreSQL (Heroku) |
| **Server** | Apache (Heroku Dyno) |
| **Assets** | Vite, NPM |

---

## 🚀 Panduan Instalasi (Lokal)

Jika ingin menjalankan di komputer sendiri (Localhost):

### 1. Clone & Install
```bash
git clone https://github.com/WAD-kelompok5/WAD_kelompok5.github.io.git
cd WAD_kelompok5.github.io
composer install
npm install
```

### 2. Konfigurasi Environment
Salin file `.env.example` ke `.env` dan atur database:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan database MySQL bernama `telyucan` sudah dibuat.

### 3. Migrasi & Seeding
Isi database dengan data awal:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 4. Jalankan Aplikasi
Buka 2 terminal:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Akses di: `http://127.0.0.1:8000`

---

## ☁️ Deployment (Heroku)

Aplikasi ini sudah dikonfigurasi untuk Heroku dengan:
- **Procfile**: Mengatur Apache web server.
- **PostgreSQL**: Menggunakan add-on `heroku-postgresql`.
- **Custom Domain**: `www.telyucan.app` (HTTPS enabled).

---

## 👥 Tim Pengembang (Kelompok 5)

Dibuat untuk memenuhi Tugas Besar mata kuliah **Web Application Development**.
