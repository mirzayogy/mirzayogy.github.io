---
title: "Laravel 2: Instalasi dan Struktur (Proyek Routing dan Controller)"
description: "menyiapkan lingkungan kerja, dan membuat proyek SIMTIKUM pertama. Mendefinisikan route, mengirim parameter, dan memindahkan logika ke controller."
date: 2026-10-18
series: laravel-simtikum
pertemuan: 2
draft: false
tags: [laravel, perkuliahan, routing]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat route GET dan POST
- Menggunakan parameter route dan named route
- Membuat controller dengan Artisan
- Mengelompokkan route dengan prefix dan name

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi


### Membuat proyek SIMTIKUM

Buat proyek baru lalu jalankan server pengembangan.

Pada project ini menggunakan contoh kelas yang mengerjakan adalah kelas 5A, kelas lain silakan menyesuaikan. 

Jalankan perintah berikut untuk membuat project Laravel, sesuaikan dengan kelas masing-masing.

```bash
composer create-project laravel/laravel laravel5A
```

```bash
laravel new simtikum
cd simtikum
php artisan serve
```

### Struktur folder

Bahas fungsi `app/`, `routes/`, `resources/views/`, `database/`, `config/`, `public/`, dan file `.env`.

## Praktik: SIMTIKUM

1. Instal Laravel dan buat proyek `simtikum`
2. Atur koneksi database MySQL di file `.env`
3. Jalankan `php artisan migrate` untuk memastikan koneksi berhasil
4. Buka `http://localhost:8000` dan pastikan halaman awal Laravel tampil

## Latihan / tugas

- Tangkap layar halaman awal Laravel yang berjalan di komputer Anda
- Tuliskan fungsi lima folder utama Laravel dengan bahasa Anda sendiri

Batas pengumpulan: …

## Rangkuman

- …

### Dasar routing

Jelaskan file `routes/web.php` dan cara kerja route.

```php
Route::get('/', fn () => view('welcome'));
Route::get('/praktikum/{id}', fn ($id) => "Praktikum $id");
```

### Named route

Gunakan nama route agar URL mudah diubah.

```php
Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');

// di Blade: route('periode.index')
```

### Controller

Buat controller dan pindahkan logika dari closure ke method.

```bash
php artisan make:controller PeriodeController
```

### Route group

Kelompokkan route admin dengan `prefix` dan `name`.

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
});
```

## Praktik: SIMTIKUM

1. Buat `DashboardController` dengan method `index`
2. Buat route `/dashboard` yang memanggil controller tersebut
3. Buat `PeriodeController` dan route `/admin/periode`
4. Cek semua route dengan `php artisan route:list`

## Latihan / tugas

- Buat route dan controller untuk halaman `/praktikum` dan `/pengajar` (cukup mengembalikan teks)

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Routing](https://laravel.com/docs/routing)
- [Controllers](https://laravel.com/docs/controllers)
