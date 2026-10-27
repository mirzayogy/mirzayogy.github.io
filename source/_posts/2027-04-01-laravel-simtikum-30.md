---
title: "Pertemuan 14: Laporan dan Ekspor PDF/Excel"
description: "Menyusun laporan rekap dan mengekspornya ke PDF dan Excel."
date: 2027-01-10
series: laravel-lanjutan
pertemuan: 14
draft: true
tags: [laravel, perkuliahan, laporan]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menyusun query laporan dengan agregasi
- Membuat filter laporan berdasarkan periode dan praktikum
- Mengekspor laporan ke PDF
- Mengekspor laporan ke Excel

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Query laporan

Rekap jumlah pertemuan, kehadiran pengajar, dan kehadiran mahasiswa per praktikum.

### Ekspor PDF

Contoh menggunakan package `barryvdh/laravel-dompdf`.

```php
$pdf = Pdf::loadView('laporan.pdf', compact('data'));

return $pdf->download('laporan-praktikum.pdf');
```

### Ekspor Excel

Contoh menggunakan package `maatwebsite/excel` (cek kompatibilitas versi).

```bash
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
```

## Praktik: SIMTIKUM

1. Buat halaman laporan dengan filter periode dan praktikum
2. Buat view khusus PDF (tanpa navbar, dengan kop)
3. Tambahkan tombol Unduh PDF dan Unduh Excel
4. Buat laporan kegiatan per pengajar beserta foto

## Latihan / tugas

- Tambahkan tanda tangan koordinator dan tanggal cetak di laporan PDF

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Collections](https://laravel.com/docs/collections)
- Laravel DomPDF
