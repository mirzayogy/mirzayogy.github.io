---
title: "Pertemuan 12: Presensi Pengajar dan Mahasiswa"
description: "Mencatat kehadiran pengajar (diisi sendiri) dan kehadiran mahasiswa per pertemuan."
date: 2026-12-27
series: laravel-lanjutan
pertemuan: 12
draft: true
tags: [laravel, perkuliahan, presensi]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Merancang tabel presensi
- Membuat form input data massal (banyak baris sekaligus)
- Menggunakan transaksi database
- Menampilkan rekap kehadiran

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Rancangan tabel

Tabel `presensi_pengajar` dan `presensi_mahasiswa`: pertemuan ke-, tanggal, status (hadir, izin, sakit, alfa).

### Input massal

```blade
@foreach ($mahasiswa as $mhs)
    <select name="status[{{ $mhs->id }}]">
        <option>hadir</option><option>izin</option><option>sakit</option><option>alfa</option>
    </select>
@endforeach
```

### Transaksi

```php
DB::transaction(function () use ($request, $jadwal) {
    foreach ($request->status as $mahasiswaId => $status) {
        PresensiMahasiswa::updateOrCreate(
            ['jadwal_id' => $jadwal->id, 'mahasiswa_id' => $mahasiswaId, 'pertemuan' => $request->pertemuan],
            ['status' => $status],
        );
    }
});
```

### Rekap

Hitung persentase kehadiran dengan `groupBy` dan `count`.

## Praktik: SIMTIKUM

1. Buat fitur pengajar mengisi presensinya sendiri
2. Buat form presensi mahasiswa per pertemuan
3. Simpan dengan `updateOrCreate` agar bisa diubah
4. Tampilkan rekap kehadiran per praktikum

## Latihan / tugas

- Beri tanda merah pada mahasiswa dengan kehadiran di bawah 75%

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Database Transactions](https://laravel.com/docs/database#database-transactions)
