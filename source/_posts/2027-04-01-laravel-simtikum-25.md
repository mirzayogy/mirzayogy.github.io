---
title: "Pertemuan 9: Autentikasi"
description: "Menambahkan login, registrasi, dan logout memakai starter kit Laravel."
date: 2026-12-06
series: laravel-lanjutan
pertemuan: 9
draft: true
tags: [laravel, perkuliahan, auth]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menjelaskan konsep autentikasi dan session
- Memasang starter kit autentikasi Laravel
- Melindungi route dengan middleware `auth`
- Menampilkan data pengguna yang sedang login

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Konsep autentikasi

Bedakan autentikasi (siapa Anda) dan otorisasi (apa yang boleh Anda lakukan).

### Starter kit

Pasang starter kit autentikasi (Breeze atau starter kit resmi versi terbaru). Sesuaikan perintah dengan versi Laravel yang dipakai.

### Middleware auth

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('periode', PeriodeController::class);
});
```

### Pengguna yang login

```blade
@auth
    Halo, {{ auth()->user()->name }}
@endauth
```

## Praktik: SIMTIKUM

1. Pasang autentikasi pada proyek SIMTIKUM
2. Lindungi semua route admin dengan middleware `auth`
3. Tampilkan nama pengguna dan tombol logout di navbar
4. Matikan registrasi publik (akun dibuat oleh admin)

## Latihan / tugas

- Buat halaman profil untuk mengganti nama dan kata sandi

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Authentication](https://laravel.com/docs/authentication)
- [Starter Kits](https://laravel.com/docs/starter-kits)
