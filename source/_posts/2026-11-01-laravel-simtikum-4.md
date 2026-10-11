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

- Menjelaskan fungsi migration sebagai version control database
- Membuat dan menjalankan migration
- Mendefinisikan foreign key
- Mengisi data awal dengan seeder dan factory

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

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
