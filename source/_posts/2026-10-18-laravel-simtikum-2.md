---
title: "Laravel 2: Instalasi dan Struktur Proyek"
description: "Menyiapkan lingkungan kerja, membuat proyek SIMTIKUM pertama, dan memahami setiap folder Laravel."
date: 2026-09-22
series: laravel-simtikum
pertemuan: 2
draft: false
tags: [laravel, perkuliahan, instalasi, struktur-proyek]
---


## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat proyek Laravel baru dan menjalankannya di browser
- Memahami struktur proyek Laravel dan fungsinya


## Materi


### Membuat proyek laravel5A (SIMTIKUM)

Pada project ini menggunakan contoh kelas yang mengerjakan adalah kelas 5A, kelas lain silakan menyesuaikan. 

Jalankan perintah berikut pada command-prompt/terminal yang sama untuk membuat project Laravel, sesuaikan dengan kelas masing-masing.

```bash
composer create-project laravel/laravel laravel5A
```

### Konten Laravel

Buka folder menggunakan [Visual Studio Code](https://code.visualstudio.com), perhatikan masing-masing folder:

- `app/` : Inti logika aplikasi yang mengikuti pola MVC — tempat sebagian besar kode custom Anda berada.
  - <mark>Http/Controllers/</mark> : Menangani request pengguna dan menyiapkan response.
  - <mark>Models</mark> : Merepresentasikan tabel database melalui Eloquent ORM.
  - <mark>Http/Middleware/</mark> : (belum ada secara bawaan, dibuat saat menambah middleware) Menyaring request sebelum diproses oleh controller.
  - `Providers/` : Mendaftarkan service dan melakukan bootstrapping aplikasi.
- `bootstrap/` : Berisi file yang menginisialisasi (bootstrapping) framework Laravel pada setiap request masuk.
  - `cache/` : Menyimpan file hasil optimisasi seperti route cache dan config cache.
  - `app.php` : Membuat dan mengonfigurasi instance aplikasi Laravel.
- `config/` : Kumpulan seluruh berkas konfigurasi aplikasi, dari nama aplikasi hingga koneksi database.
  - `app.php` : Nama aplikasi, timezone, locale, dan pengaturan umum.
  - `database.php` : Konfigurasi koneksi database (MySQL, SQLite, dll).
  - `auth.php` : Konfigurasi guard dan provider autentikasi.
  - `mail.php`, `filesystems.php` : Konfigurasi pengiriman email dan penyimpanan file.
- `database/` : Menyimpan segala sesuatu yang berkaitan dengan struktur dan isi awal database aplikasi.
  - <mark>migrations/</mark> : Skema tabel database yang version-controlled.
  - <mark>seeders/</mark> : Mengisi data awal ke dalam tabel database.
  - <mark>factories/</mark> : Membuat data dummy untuk keperluan testing.
- <mark>public/</mark> : Satu-satunya folder yang diakses langsung oleh web server — document root aplikasi.
- `resources/` : Tempat file-file mentah sebelum diproses atau dikompilasi menjadi aset akhir.
  - `css/` , `js/` : Aset mentah (Sass/JS) sebelum dikompilasi oleh Vite.
  - <mark>views/</mark> : Template Blade (.blade.php) untuk tampilan aplikasi.
- `routes/` : Kumpulan berkas yang mendefinisikan seluruh rute (endpoint) yang dimiliki aplikasi.
  - `console.php` : Definisi closure-based Artisan command.
  - <mark>web.php</mark> : Rute untuk aplikasi web — mendukung sesi, cookie, dan CSRF.
- `storage/` : Lokasi penyimpanan berbagai file yang dihasilkan aplikasi selama berjalan.
  - `app/` : File yang diunggah oleh pengguna aplikasi.
  - `framework/` : Cache, session, dan compiled view Blade.
  - `logs/` : Catatan error aplikasi (laravel.log).
- `tests/` : Tempat menulis seluruh pengujian otomatis untuk menjaga kualitas aplikasi.
  - <mark>Feature/</mark> : Pengujian yang melibatkan beberapa komponen sekaligus (HTTP, database).
  - <mark>Unit/</mark> : Pengujian unit kecil dan terisolasi, misalnya satu method saja.
- `vendor/` : Berisi seluruh pustaka (dependency) pihak ketiga yang digunakan aplikasi. Tidak diikutkan ke GitHub; dibuat ulang dengan composer install.

Perhatikan bagian yang berwarna jingga yang akan digunakan dan ditambahkan.

Selain itu terdapat juga beberapa file yang biasa digunakan untuk pengelolaan proyek yang dikerjakan: 

- `.env` : Variabel environment aktif: koneksi database, API key, dll. Biasanya tidak diikutkan dalam GitHub.
- `.env.example` : Contoh/template variabel environment untuk disalin menjadi .env, dan ikut di-push ke GitHub.
- `composer.json` : Mendefinisikan dependency PHP beserta metadata proyek.
- `package.json` : Mendefinisikan dependency & script build JavaScript (npm).
- `README.md` : Dokumentasi umum: deskripsi, instalasi, dan cara penggunaan proyek. Ditulis oleh programmer.


### Menjalankan proyek laravel5A

Jalankan perintah berikut pada command-prompt/terminal sebelumnya:

```bash
cd laravel5A
php artisan serve
```

Buka `http://localhost:8000` dan pastikan halaman awal Laravel tampil
