# TelyuCan - Kantin Digital Telkom University

Aplikasi web untuk manajemen kantin (TelyuCanteen) berbasis Laravel 11.

## 🛠️ Tech Stack

| Teknologi | Versi |
|-----------|-------|
| PHP | >= 8.2 |
| Laravel | 11.x |
| MySQL / MariaDB | 8.0+ |
| Node.js | 18+ |
| Vite | 5.x |
| Bootstrap | 5.3 |

---

## 📋 Persyaratan Sistem

Pastikan perangkat Anda memiliki:
- **PHP** >= 8.2 (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer** >= 2.0
- **Node.js** >= 18 & **NPM** >= 9
- **MySQL** / **MariaDB** (Database Server)
- **Git**

---

## 🚀 Panduan Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/WAD-kelompok5/WAD_kelompok5.github.io.git
cd WAD_kelompok5.github.io
```

### 2. Install Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=telyucan
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Buat Database
Buat database baru di MySQL/PHPMyAdmin dengan nama `telyucan`:
```sql
CREATE DATABASE telyucan;
```

### 6. Migrasi & Seeding Database
```bash
php artisan migrate:fresh --seed
```

### 7. Setup Storage Link
```bash
php artisan storage:link
```

---

## ▶️ Menjalankan Aplikasi

Buka **2 Terminal** secara bersamaan:

**Terminal 1 - Backend Server:**
```bash
php artisan serve
```
> Akses di: http://127.0.0.1:8000

**Terminal 2 - Frontend (Vite):**
```bash
npm run dev
```

---

## 🔐 Akun Default (Setelah Seeding)

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@gmail.com | password |
| **Tenant (TULT)** | tult@gmail.com | password |
| **Tenant (GKU)** | gku@gmail.com | password |
| **Tenant (Pujasera)** | pujasera@gmail.com | password |
| **Customer** | customer@gmail.com | password |

---

## 📱 Fitur Utama

### Pelanggan (Customer)
- ✅ Browse menu dari berbagai tenant
- ✅ Filter berdasarkan kategori & lokasi
- ✅ Keranjang belanja & checkout
- ✅ Riwayat pesanan
- ✅ Beri review setelah pesanan selesai

### Tenant
- ✅ Dashboard Kitchen Display
- ✅ Kelola menu (CRUD)
- ✅ Update status pesanan
- ✅ Toggle Buka/Tutup toko
- ✅ Lihat review pelanggan

### Admin
- ✅ Dashboard statistik
- ✅ Kelola tenant (CRUD)
- ✅ Kelola user
- ✅ Moderasi review

---

## 🛠️ Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Gambar tidak muncul | `php artisan storage:link` |
| Vendor not found | `composer install` |
| Vite manifest not found | Pastikan `npm run dev` berjalan |
| SQLSTATE Connection refused | Pastikan MySQL server aktif |
| 403 Forbidden di Admin/Tenant | Login dengan akun role yang sesuai |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan tugas mata kuliah **Web Application Development** - Telkom University.
