---
title: "Pertemuan 3: Routing dan Controller, Blade Templating dan Layout"
description: "Mendefinisikan route, mengirim parameter, dan memindahkan logika ke controller. Membuat tampilan dengan Blade: layout utama, komponen, dan menampilkan data dari controller."
date: 2026-10-25
series: laravel-simtikum
pertemuan: 3
draft: true
tags: [laravel, perkuliahan, blade]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat route GET dan POST
- Menggunakan parameter route dan named route
- Membuat controller dengan Artisan
- Mengelompokkan route dengan prefix dan name
- Mengirim data dari controller ke view
- Membuat layout utama dan memakainya di banyak halaman
- Menggunakan direktif Blade (`@if`, `@foreach`, `@include`)
- Membuat komponen Blade sederhana

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

2. Atur koneksi database MySQL di file `.env`
3. Jalankan `php artisan migrate` untuk memastikan koneksi berhasil
4. 

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


### Mengirim data ke view

```php
return view('periode.index', [
    'periode' => ['2025/2026 Ganjil', '2025/2026 Genap'],
]);
```

### Layout dengan komponen

Buat `resources/views/components/layouts/app.blade.php` berisi kerangka HTML, navbar, dan `{{ $slot }}`.

```blade
<x-layouts.app title="Periode">
    <h1>Daftar Periode</h1>
</x-layouts.app>
```

### Direktif Blade

```blade
@forelse ($periode as $item)
    <li>{{ $item }}</li>
@empty
    <p>Belum ada data.</p>
@endforelse
```

### Aset CSS

Pilih Tailwind (bawaan Vite) atau Bootstrap via CDN. Jelaskan perbedaan `{{ }}` dan `{!! !!}`.

## Praktik: SIMTIKUM

1. Buat layout utama SIMTIKUM dengan navbar: Dashboard, Periode, Praktikum, Pengajar
2. Ubah halaman `/dashboard` dan `/admin/periode` agar memakai layout tersebut
3. Tampilkan daftar periode (data array) dalam tabel

## Latihan / tugas

- Buat komponen `<x-alert>` untuk menampilkan pesan sukses/gagal dan gunakan di dashboard

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Blade Templates](https://laravel.com/docs/blade)
- [Views](https://laravel.com/docs/views)
