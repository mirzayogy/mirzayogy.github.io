---
title: "Laravel 3: Koneksi Database, Migration, dan Seeder"
description: "Menghubungkan Laravel ke MySQL, membuat model, migration, controller, dan seeder sekaligus untuk tabel periode, lalu mengisi data awal."
tags: [laravel, perkuliahan, database, migration]
date: 2026-09-29
series: laravel-simtikum
pertemuan: 3
draft: false
comments: true
---


## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Mengatur koneksi database di file `.env`
- Membuat model, migration, controller, dan seeder dengan satu perintah Artisan
- Membuat tabel dengan migration
- Mengisi data awal dengan seeder

## Persiapan

- Proyek dari pertemuan sebelumnya sudah berjalan.

## Materi

- Atur koneksi database MySQL di file `.env`
- Jalankan `php artisan migrate` untuk memastikan koneksi berhasil

## Pengaturan .env

- Buka file `.env` pada root proyek Laravel.
- Cari `DB_CONNECTION=sqlite` ganti dengan `DB_CONNECTION=mysql`
- Hapus tanda komentar `#` pada baris-baris konfigurasi database di bawahnya.
- Ganti `DB_DATABASE` menjadi `db_pbo2_5A`, lalu simpan file.
- Jalankan:

```bash
php artisan migrate
```

- Konfirmasi ketika ditanyakan akan dibuat database
- Periksa pada `phpMyAdmin` atau `HeidiSQL`

atau jika ingin menghapus semua tabel berisinya dan mengulang pembuatan database, gunakan:

```bash
php artisan migrate:fresh
```

## Membuat Paket untuk Tabel

Jalankan perintah berikut untuk membuat `model`, `migration`, `controller` (`resource`), dan `seeder`

```bash
php artisan make:model Periode -mcrs
```

Resource di sini dimaksudkan untuk membuat method `resource` yang berisikan index, create, store, show, edit, update, dan destroy pada `controller`.

### Mengubah Model

Pada file model pada `app/Models/Periode.php` yang dihasilkan, tuliskan:

```php
protected $table = 'periode';
protected $guarded = [];
```

Di sini dinyatakan nama tabel karena kita menggunakan tabel dengan Bahasa Indonesia. Sedangkan perintah `guarded = []` digunakan agar seluruh kolom bisa kita kelola.

### Mengubah Migration

Pada file `database/migrations/~_create_periodes_table.php` yang dihasilkan, perhatikan function `up`:

```php
public function up(): void
{
    Schema::create('periode', function (Blueprint $table) {
        $table->id();
        $table->string('periode');
        $table->string('singkatan');
        $table->boolean('status')->default(false);
        $table->timestamps();
    });
}
```

Pastikan mengganti `periodes` menjadi `periode` dan tambahkan field yang akan digunakan.

Ganti juga pada function `down`: 

```php
public function down(): void
{
    Schema::dropIfExists('periode');
}
```

Jalankan kembali perintah berikut agar tabel dibuat pada database

```bash
php artisan migrate
```

### Perhatikan Controller

Perhatikan pada folder `app/Http/Controllers/PeriodeController.php`. Pada file tersebut sudah terdapat beberapa method untuk memanipulasi data yang merupakan bagian dari `resource`.


### Mengubah Seeder

Tambahkan pada file seeder `database/seeders/PeriodeSeeder.php`:

```php
use App\Models\Periode;
```

Isi function `run`. Ada beberapa contoh pembuatan seeder diantaranya yang `pertama`:

```php
Periode::create([
    'periode' => 'GANJIL 2025/2026',
    'singkatan' => '251',
]);
Periode::create([
    'periode' => 'GENAP 2025/2026',
    'singkatan' => '252',
]);
Periode::create([
    'periode' => 'GANJIL 2026/2027',
    'singkatan' => '261',
]);
Periode::create([
    'periode' => 'GENAP 2026/2027',
    'singkatan' => '262',
]);
```

`atau` yang kedua:

```php
$now = now();

Periode::insert([
    ['periode' => 'GANJIL 2025/2026', 'singkatan' => '251', 'created_at' => $now, 'updated_at' => $now],
    ['periode' => 'GENAP 2025/2026', 'singkatan' => '252', 'created_at' => $now, 'updated_at' => $now],
    ['periode' => 'GANJIL 2026/2027', 'singkatan' => '261', 'created_at' => $now, 'updated_at' => $now],
    ['periode' => 'GENAP 2026/2027', 'singkatan' => '262', 'created_at' => $now, 'updated_at' => $now],
]);
```

`atau` yang ketiga:

```php
$now = now();

$data = collect([
    ['GANJIL 2025/2026', '251'],
    ['GENAP 2025/2026', '252'],
    ['GANJIL 2026/2027', '261'],
    ['GENAP 2026/2027', '262'],
])->map(fn($item) => [
    'periode' => $item[0],
    'singkatan' => $item[1],
    'created_at' => $now,
    'updated_at' => $now,
])->toArray();

Periode::insert($data);
```

Pilih yang menurut anda paling mudah.

Panggil seeder yang sudah kita buat dalam function `run` pada file `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call(PeriodeSeeder::class);
}
```

Jalankan dengan perintah:

```bash
php artisan migrate:fresh --seed
```

alternatif migration untuk menyertakan seeder bisa menggunakan:

```bash
php artisan migrate:fresh --seed --seeder=PeriodeSeeder
```
atau jika satu seeder saja yang ingin dieksekusi bisa menggunakan:

```bash
php artisan db:seed --class=PeriodeSeeder
```
