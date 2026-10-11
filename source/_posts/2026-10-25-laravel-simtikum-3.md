---
title: "Pertemuan 3: Blade Templating dan Layout"
description: "Membuat tampilan dengan Blade: layout utama, komponen, dan menampilkan data dari controller."
date: 2026-10-25
series: laravel-simtikum
pertemuan: 3
draft: true
tags: [laravel, perkuliahan, blade]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Mengirim data dari controller ke view
- Membuat layout utama dan memakainya di banyak halaman
- Menggunakan direktif Blade (`@if`, `@foreach`, `@include`)
- Membuat komponen Blade sederhana

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

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
