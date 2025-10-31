# Proyek Blog PHP Modern

Ini adalah aplikasi blog sederhana yang dibangun menggunakan PHP murni dan database MySQL. Aplikasi ini memungkinkan pengguna untuk mendaftar, login, melihat artikel, mencari artikel, dan (untuk admin) membuat, mengedit, serta menghapus artikel.

## Fitur

  * Registrasi dan Login Pengguna
  * Penjelajahan Artikel dengan Paginasi
  * Pencarian Artikel
  * Panel Dashboard Admin
      * Buat Artikel Baru (CRUD)
      * Edit Artikel (CRUD)
      * Hapus Artikel (CRUD)
  * Desain Responsif
  * Perlindungan CSRF pada form
  * Sanitasi output untuk keamanan (XSS)

## Persyaratan Sistem

Sebelum memulai instalasi, pastikan server Anda memenuhi persyaratan berikut:

  * **Web Server**: Apache, Nginx, atau lainnya yang mendukung PHP.
  * **PHP**: Versi 7.4 atau lebih baru (disarankan).
  * **Database**: MySQL atau MariaDB.
  * **Ekstensi PHP**: `pdo_mysql` (untuk koneksi database).

## Panduan Instalasi

Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek ini di lingkungan lokal (seperti XAMPP, WAMP, atau MAMP).

### 1\. Dapatkan File Proyek

  * Unduh atau clone semua file proyek (`.php`, `.css`) ke dalam satu direktori.
  * Pindahkan direktori proyek ini ke dalam folder `htdocs` (untuk XAMPP) atau `www` (untuk WAMP/MAMP) di server lokal Anda. Untuk panduan ini, kita asumsikan nama direktorinya adalah `blog`.
  * Struktur file Anda seharusnya terlihat seperti ini:

<!-- end list -->

```
/blog/
    /css/
        style.css
    /dashboard/
        create.php
        delete.php
        edit.php
        index.php
    /includes/
        auth.php
        config.php
        footer.php
        functions.php
        header.php
    article.php
    index.php
    login.php
    logout.php
    register.php
    search.php
```

### 2\. Konfigurasi Database

Aplikasi ini memerlukan database MySQL untuk menyimpan data pengguna dan artikel.

**A. Buat Database**

1.  Buka `phpMyAdmin` (biasanya melalui `http://localhost/phpmyadmin`).
2.  Buat database baru. Berdasarkan file `includes/config.php`, nama database yang disarankan adalah `blog_app`.

**B. Buat Tabel-tabel**

Jalankan kueri SQL berikut di dalam database `blog_app` Anda untuk membuat tabel `users` dan `articles` yang diperlukan.

```sql
-- Tabel untuk menyimpan data pengguna
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk menyimpan artikel blog
CREATE TABLE `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` TEXT NOT NULL,
  `author_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
```

**C. Buat Akun Admin (Opsional tapi Direkomendasikan)**

Untuk mengakses dashboard admin, Anda perlu mengubah role salah satu pengguna menjadi 'admin' secara manual setelah mendaftar.

1.  **Daftar Akun**: Buka `http://localhost/blog/register.php` di browser Anda dan daftarkan akun baru.
2.  **Ubah Role**: Buka `phpMyAdmin`, pilih tabel `users`, cari pengguna yang baru Anda daftarkan, dan ubah nilai di kolom `role` dari `'user'` menjadi `'admin'`.

### 3\. Konfigurasi File `config.php`

Langkah terakhir adalah menghubungkan aplikasi ke database yang baru saja Anda buat.

1.  Buka file `blog/includes/config.php`.
2.  Sesuaikan pengaturan database sesuai dengan konfigurasi server lokal Anda. Untuk kebanyakan instalasi XAMPP/WAMP standar, pengaturannya adalah:

<!-- end list -->

```php
<?php
// Database configuration
define('DB_HOST', 'localhost'); // Biarkan 'localhost' jika database ada di server yang sama
define('DB_NAME', 'blog_app');  // Nama database yang Anda buat di Langkah 2A
define('DB_USER', 'root');      // Username database Anda (default XAMPP adalah 'root')
define('DB_PASS', '');          // Password database Anda (default XAMPP adalah kosong)

// ... sisa konfigurasi biarkan default ...
?>
```

**Catatan Penting untuk Produksi (Hosting Online):**
Jika Anda mengunggah ini ke server hosting online, Anda **HARUS** mengubah pengaturan `ini_set('session.cookie_secure', 1);` menjadi `1` dan memastikan website Anda berjalan di atas **HTTPS** untuk keamanan. Untuk pengembangan lokal (localhost) dengan HTTP, Anda mungkin perlu mengubahnya menjadi `ini_set('session.cookie_secure', 0);` jika Anda mengalami masalah sesi/login.

## Menjalankan Website

Setelah semua langkah di atas selesai, Anda dapat mengakses website Anda melalui browser:

  * **Halaman Utama**: `http://localhost/blog/`
  * **Halaman Login**: `http://localhost/blog/login.php`
  * **Halaman Registrasi**: `http://localhost/blog/register.php`
  * **Dashboard Admin** (setelah login sebagai admin): `http://localhost/blog/dashboard/`

Website Anda sekarang sudah siap dijalankan\!
