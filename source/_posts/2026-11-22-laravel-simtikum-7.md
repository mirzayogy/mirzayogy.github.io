---
title: "Pertemuan 7: CRUD Praktikum, Pencarian, dan Paginasi"
description: "CRUD data praktikum yang berelasi dengan periode, dilengkapi pencarian dan paginasi."
date: 2026-11-22
series: laravel-simtikum
pertemuan: 7
draft: true
tags: [laravel, perkuliahan, crud]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat CRUD untuk data yang memiliki relasi
- Membuat dropdown dari data tabel lain
- Menambahkan pencarian dan filter
- Menampilkan data dengan paginasi

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Dropdown relasi

```blade
<select name="periode_id">
    @foreach ($periode as $p)
        <option value="{{ $p->id }}" @selected(old('periode_id', $praktikum->periode_id ?? null) == $p->id)>{{ $p->nama }}</option>
    @endforeach
</select>
```

### Pencarian dan filter

```php
$praktikum = Praktikum::with('periode')
    ->when($request->q, fn ($q, $kata) => $q->where('nama', 'like', "%{$kata}%"))
    ->when($request->periode_id, fn ($q, $id) => $q->where('periode_id', $id))
    ->paginate(10)
    ->withQueryString();
```

### Paginasi

Tampilkan link halaman dengan `{{ $praktikum->links() }}`.

## Praktik: SIMTIKUM

1. Buat resource controller `PraktikumController`
2. Form praktikum memakai dropdown periode
3. Tambahkan kotak pencarian nama dan filter periode di halaman index
4. Aktifkan paginasi 10 data per halaman

## Latihan / tugas

- Tambahkan validasi `kode` unik saat menambah dan mengubah data (abaikan data itu sendiri saat edit)

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Pagination](https://laravel.com/docs/pagination)
- [Query Builder](https://laravel.com/docs/queries)
