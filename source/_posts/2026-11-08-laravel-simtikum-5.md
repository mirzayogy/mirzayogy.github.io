---
title: "Pertemuan 5: Eloquent ORM dan Relasi"
description: "Mengakses database lewat model Eloquent dan menghubungkan tabel dengan relasi."
date: 2026-11-08
series: laravel-simtikum
pertemuan: 5
draft: true
tags: [laravel, perkuliahan, eloquent]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat model dan melakukan query dasar
- Menggunakan mass assignment dengan aman
- Mendefinisikan relasi one-to-many dan belongs-to
- Menghindari masalah N+1 dengan eager loading

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Model

```bash
php artisan make:model Periode
php artisan make:model Praktikum
```

### Query dasar

```php
Periode::all();
Periode::where('aktif', true)->first();
Praktikum::create(['kode' => 'PW01', 'nama' => 'Pemrograman Web', 'periode_id' => 1]);
```

### Relasi

```php
// Periode.php
public function praktikum(): HasMany
{
    return $this->hasMany(Praktikum::class);
}

// Praktikum.php
public function periode(): BelongsTo
{
    return $this->belongsTo(Periode::class);
}
```

### Eager loading

Bandingkan jumlah query dengan dan tanpa `with()`.

```php
$praktikum = Praktikum::with('periode')->get();
```

## Praktik: SIMTIKUM

1. Buat model `Periode` dan `Praktikum` beserta `$fillable` (atau `$guarded`)
2. Tambahkan relasi di kedua model
3. Coba query di `php artisan tinker`
4. Tampilkan daftar praktikum beserta nama periodenya di view

## Latihan / tugas

- Tampilkan jumlah praktikum di setiap periode menggunakan `withCount()`

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Eloquent](https://laravel.com/docs/eloquent)
- [Relationships](https://laravel.com/docs/eloquent-relationships)
