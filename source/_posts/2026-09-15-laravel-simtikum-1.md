---
title: "Pertemuan 1: Pengenalan Laravel dan Instalasi"
description: "Mengenal Laravel, pola MVC, menyiapkan lingkungan kerja, dan membuat proyek SIMTIKUM pertama."
date: 2026-10-11
series: laravel-simtikum
pertemuan: 1
draft: false
tags: [laravel, perkuliahan, php, instalasi]
featured: true
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menjelaskan apa itu framework dan pola MVC pada Laravel
- Menyiapkan PHP, Composer, dan database di komputer sendiri
- Membuat proyek Laravel baru dan menjalankannya di browser
- Mengenali struktur folder utama proyek Laravel

## Persiapan

- PHP dasar dan HTML/CSS dasar.
- …

## Materi

### Apa itu Laravel?

Tulis penjelasan singkat: framework PHP, ekosistem (Artisan, Eloquent, Blade), dan alasan memakai framework dibanding PHP native.

### Pola MVC

Jelaskan alur request → route → controller → model → view. Sertakan diagram sederhana bila perlu.

### Menyiapkan lingkungan

Kebutuhan: PHP 8.2+, Composer, MySQL/MariaDB, dan editor (VS Code). Alternatif praktis: Laravel Herd atau XAMPP.

```bash
php -v
composer -V
composer global require laravel/installer
```

### Membuat proyek SIMTIKUM

Buat proyek baru lalu jalankan server pengembangan.

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

## Referensi

- [Installation](https://laravel.com/docs/installation)
- [Directory Structure](https://laravel.com/docs/structure)
