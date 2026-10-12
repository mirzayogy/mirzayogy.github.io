---
title: "Pertemuan 4: Migration dan Seeder"
description: "Merancang tabel SIMTIKUM dengan migration dan mengisi data awal dengan seeder."
date: 2026-11-01
series: laravel-simtikum
pertemuan: 4
draft: true
tags: [laravel, perkuliahan, database]
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
- Menjelaskan fungsi migration sebagai version control database
- Membuat dan menjalankan migration
- Mendefinisikan foreign key
- Mengisi data awal dengan seeder dan factory

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


### Rancangan tabel SIMTIKUM

Tampilkan ERD: `users`, `periode`, `praktikum`, `jadwal`, `presensi`. Sesuaikan dengan rancangan Anda.

### Membuat migration

```bash
php artisan make:migration create_periode_table
php artisan make:migration create_praktikum_table
```

### Isi migration

```php
Schema::create('praktikum', function (Blueprint $table) {
    $table->id();
    $table->foreignId('periode_id')->constrained('periode')->cascadeOnDelete();
    $table->string('kode')->unique();
    $table->string('nama');
    $table->timestamps();
});
```

### Seeder dan factory

```bash
php artisan make:seeder PeriodeSeeder
php artisan migrate:fresh --seed
```

## Praktik: SIMTIKUM

1. Buat migration untuk tabel `periode` dan `praktikum`
2. Tambahkan kolom `role` pada tabel `users` (admin, pengajar)
3. Buat seeder untuk satu admin dan dua periode
4. Jalankan `php artisan migrate:fresh --seed` dan cek di database

## Latihan / tugas

- Lengkapi migration tabel `jadwal` sesuai ERD dan buat seeder-nya

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Migrations](https://laravel.com/docs/migrations)
- [Seeding](https://laravel.com/docs/seeding)
