---
title: "Pertemuan 2: Routing dan Controller"
description: "Mendefinisikan route, mengirim parameter, dan memindahkan logika ke controller."
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
