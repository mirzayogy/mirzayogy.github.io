---
title: "Pertemuan 13: Upload File: Laporan Kegiatan dengan Foto"
description: "Mengunggah dan menampilkan foto kegiatan pada laporan pengajar menggunakan Storage Laravel."
date: 2027-01-03
series: laravel-simtikum
pertemuan: 13
draft: true
tags: [laravel, perkuliahan, storage]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat form upload file
- Memvalidasi tipe dan ukuran file
- Menyimpan file dengan Storage dan membuat symbolic link
- Menghapus file lama saat data diubah atau dihapus

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Form upload

```blade
<form method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="foto[]" multiple accept="image/*">
</form>
```

### Validasi file

```php
'foto' => ['required', 'array', 'max:5'],
'foto.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
```

### Menyimpan file

```php
$path = $request->file('foto')[0]->store('laporan', 'public');
```

### Menampilkan file

```bash
php artisan storage:link
```

## Praktik: SIMTIKUM

1. Buat tabel `laporan_kegiatan` dan `laporan_foto`
2. Buat form laporan kegiatan dengan upload beberapa foto
3. Tampilkan galeri foto di halaman detail laporan
4. Hapus file dari storage saat laporan dihapus

## Latihan / tugas

- Kompres atau ubah ukuran foto sebelum disimpan (eksplorasi package pengolah gambar)

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [File Storage](https://laravel.com/docs/filesystem)
- [Requests: Files](https://laravel.com/docs/requests#files)
