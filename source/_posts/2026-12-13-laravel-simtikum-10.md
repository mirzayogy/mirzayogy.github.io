---
title: "Pertemuan 10: Otorisasi: Role, Middleware, Gate, dan Policy"
description: "Membedakan hak akses admin dan pengajar dengan middleware, Gate, dan Policy."
date: 2026-12-13
series: laravel-simtikum
pertemuan: 10
draft: true
tags: [laravel, perkuliahan, otorisasi]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat middleware untuk membatasi akses berdasarkan role
- Menggunakan Gate untuk aturan sederhana
- Menggunakan Policy untuk aturan per model
- Menyembunyikan menu sesuai hak akses

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Middleware role

```php
public function handle(Request $request, Closure $next, string $role): Response
{
    abort_unless($request->user()?->role === $role, 403);

    return $next($request);
}
```

### Gate

```php
Gate::define('kelola-master', fn (User $user) => $user->role === 'admin');
```

### Policy

```bash
php artisan make:policy JadwalPolicy --model=Jadwal
```

### Di Blade

```blade
@can('kelola-master')
    <a href="{{ route('periode.index') }}">Periode</a>
@endcan
```

## Praktik: SIMTIKUM

1. Buat middleware `role` dan daftarkan aliasnya
2. Batasi menu Periode dan Praktikum hanya untuk admin
3. Buat dashboard berbeda untuk admin dan pengajar
4. Uji akses dengan dua akun berbeda

## Latihan / tugas

- Buat halaman error 403 kustom dengan pesan berbahasa Indonesia

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Authorization](https://laravel.com/docs/authorization)
- [Middleware](https://laravel.com/docs/middleware)
