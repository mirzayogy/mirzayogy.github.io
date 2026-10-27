---
title: "Pertemuan 11: Penugasan Pengajar dan Jadwal Praktikum"
description: "Relasi many-to-many untuk menugaskan pengajar ke praktikum, lalu menyusun jadwalnya."
date: 2026-12-20
series: laravel-lanjutan
pertemuan: 11
draft: true
tags: [laravel, perkuliahan, relasi]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat relasi many-to-many dengan tabel pivot
- Menyimpan dan mengubah relasi dengan `attach`, `detach`, dan `sync`
- Menyimpan data tambahan pada tabel pivot
- Mencegah jadwal yang bentrok

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.
- …

## Materi

### Tabel pivot

```php
Schema::create('praktikum_pengajar', function (Blueprint $table) {
    $table->foreignId('praktikum_id')->constrained('praktikum')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('peran')->default('pengajar');
    $table->primary(['praktikum_id', 'user_id']);
});
```

### Relasi belongsToMany

```php
public function pengajar(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'praktikum_pengajar')->withPivot('peran');
}
```

### Menyimpan penugasan

```php
$praktikum->pengajar()->sync($request->pengajar_ids);
```

### Validasi jadwal bentrok

Jelaskan logika pengecekan: hari sama, ruangan sama, jam saling tumpang tindih.

## Praktik: SIMTIKUM

1. Buat tabel pivot `praktikum_pengajar` dan relasinya
2. Buat form penugasan dengan checkbox daftar pengajar
3. Buat CRUD jadwal (hari, jam, ruangan) per praktikum
4. Tampilkan jadwal mengajar di dashboard pengajar

## Latihan / tugas

- Tolak penyimpanan jadwal jika ruangan sudah terpakai di jam yang sama

Batas pengumpulan: …

## Rangkuman

- …

## Referensi

- [Many to Many](https://laravel.com/docs/eloquent-relationships#many-to-many)
