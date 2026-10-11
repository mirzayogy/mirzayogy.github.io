---
title: "Pertemuan 15: Testing dan Deployment"
description: "Menguji fitur utama SIMTIKUM dan menyiapkan aplikasi untuk dijalankan di server."
date: 2027-01-17
series: laravel-simtikum
pertemuan: 15
draft: true
tags: [laravel, perkuliahan, deployment]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menulis feature test sederhana
- Menjalankan test dengan Artisan
- Menyiapkan konfigurasi produksi
- Men-deploy aplikasi ke server atau hosting

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Feature test

```php
it('melarang pengajar membuka halaman periode', function () {
    $pengajar = User::factory()->create(['role' => 'pengajar']);

    $this->actingAs($pengajar)
        ->get(route('periode.index'))
        ->assertForbidden();
});
```

### Menjalankan test

```bash
php artisan test
```

### Persiapan produksi

Bahas `APP_ENV=production`, `APP_DEBUG=false`, dan optimasi.

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

### Pilihan hosting

Shared hosting (cPanel), VPS, atau layanan PaaS. Jelaskan document root ke folder `public/`.

## Praktik: SIMTIKUM

1. Tulis minimal tiga feature test: login, akses role, dan CRUD periode
2. Pastikan semua test lulus
3. Siapkan file `.env` produksi
4. Deploy SIMTIKUM ke server yang disediakan

## Latihan / tugas

- Siapkan proyek akhir untuk presentasi UAS

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Testing](https://laravel.com/docs/testing)
- [Deployment](https://laravel.com/docs/deployment)
