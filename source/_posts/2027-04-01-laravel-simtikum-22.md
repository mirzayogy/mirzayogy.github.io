---
title: "Pertemuan 6: CRUD Periode dan Validasi"
description: "Membangun fitur tambah, ubah, hapus data periode dengan resource controller dan Form Request."
date: 2026-11-15
series: laravel-lanjutan
pertemuan: 6
draft: true
tags: [laravel, perkuliahan, crud]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat resource controller dan resource route
- Membuat form tambah dan ubah data
- Memvalidasi input dengan Form Request
- Menampilkan pesan sukses dan error validasi

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Resource controller

```bash
php artisan make:controller PeriodeController --resource --model=Periode
php artisan make:request PeriodeRequest
```

### Resource route

```php
Route::resource('periode', PeriodeController::class);
```

### Validasi

```php
public function rules(): array
{
    return [
        'nama' => ['required', 'string', 'max:50'],
        'tanggal_mulai' => ['required', 'date'],
        'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
    ];
}
```

### Form dan CSRF

Jelaskan `@csrf`, `@method('PUT')`, `old()`, dan `@error`.

```blade
<input name="nama" value="{{ old('nama', $periode->nama ?? '') }}">
@error('nama') <small>{{ $message }}</small> @enderror
```

## Praktik: SIMTIKUM

1. Lengkapi method `index`, `create`, `store`, `edit`, `update`, `destroy`
2. Buat view `index`, `create`, dan `edit` untuk periode
3. Tambahkan konfirmasi sebelum menghapus
4. Tampilkan pesan sukses dengan `session('success')`

## Latihan / tugas

- Tambahkan aturan: hanya boleh ada satu periode yang berstatus aktif

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Validation](https://laravel.com/docs/validation)
- [Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
