
---

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</p>

# Dua Insan Story – API Backend

**Backend RESTful API** untuk platform e-commerce **Dua Insan Story**, yang melayani pemesanan undangan pernikahan dan produk terkait secara online. Dibangun dengan **Laravel 12**, berfokus pada arsitektur yang bersih, skalabel, dan mudah dirawat.

---

## ⚙️ Fitur Utama

### Pelanggan (Customer)
- **Autentikasi**  
  - Registrasi, login, logout  
- **Produk**  
  - Daftar produk aktif  
  - Pencarian (nama, SKU, deskripsi)  
  - Filter berdasarkan kategori  
  - Detail produk: opsi, gambar, add-ons  
- **Kategori**  
  - Daftar kategori produk  
- **Keranjang Belanja**  
  - Tambah produk kustom  
  - Lihat, ubah jumlah, hapus, kosongkan  
  - Dukungan guest + merge saat login  
- **Checkout**  
  - Konversi keranjang ke pesanan dengan data undangan

### Admin
- **Keamanan**  
  - Semua endpoint terlindungi hanya untuk peran `admin`
- **CRUD Lengkap**  
  - Produk, kategori, gambar (upload), atribut, nilai atribut, add-ons  
- **Relasi Produk**  
  - Tautkan atribut & harga khusus  
  - Tautkan add-ons

---

## 🛠️ Teknologi

| Komponen        | Teknologi             |
| --------------- | --------------------- |
| Framework       | Laravel 11            |
| Bahasa          | PHP 8.2+              |
| Database        | MySQL                 |
| Autentikasi     | Laravel Sanctum       |
| Arsitektur      | Service Layer, API Resources, Form Requests |

---

## 🚀 Instalasi & Setup Lokal

1. **Clone repository**  
   ```bash
   git clone https://your-repository-url.git
   cd dua-insan-story-api

2. **Install dependencies**

   ```bash
   composer install
3. **Siapkan environment**

   ```bash
   cp .env.example .env

4. **Generate app key**

   ```bash
   php artisan key:generate

5. **Konfigurasi database**
   Edit `.env`:

   ```dotenv
   DB_DATABASE=nama_database
   DB_USERNAME=user_db
   DB_PASSWORD=password_db

6. **Migrasi & seeder**

   ```bash
   php artisan migrate:fresh --seed

7. **Link storage**

   ```bash
   php artisan storage:link

8. **Jalankan server**

   ```bash
   php artisan serve

   Akses di `http://127.0.0.1:8000/api/v1/`

---

## 📄 Dokumentasi API

* **pelanggan-api.yaml** (endpoint pelanggan)
* **admin-api.yaml** (endpoint admin)

Buka di [Swagger Editor](https://editor.swagger.io/) atau VS Code “Swagger Viewer”.

---

## 👤 Akun Default

* **Admin**

  * Email: `admin@duainsan.story`
  * Password: `password`
* **Pelanggan**

  * Email: `customer@example.com`
  * Password: `password`

---

## 📝 Lisensi

[MIT License](https://opensource.org/licenses/MIT)

```
```
