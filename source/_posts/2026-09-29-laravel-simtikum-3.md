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

![env](/assets/image/laravel01-03/env.png)

- Cari `DB_CONNECTION=sqlite` ganti dengan `DB_CONNECTION=mysql`
- Hapus tanda komentar `#` pada baris-baris konfigurasi database di bawahnya.
- Ganti `DB_DATABASE` menjadi `db_pbo2_5`, lalu simpan file.
  
![db_database](/assets/image/laravel01-03/db_database.png){.w-30 .bingkai .kiri}

- Jalankan:

```bash
php artisan migrate
```

- Konfirmasi ketika ditanyakan akan dibuat database

![confirmation_1](/assets/image/laravel01-03/confirmation_1.png){.w-70 .bingkai .kiri}

![confirmation_2](/assets/image/laravel01-03/confirmation_2.png){.bingkai .kiri}

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

![make_mcrs](/assets/image/laravel01-03/make_mcrs.png){.bingkai .kiri}

Resource di sini dimaksudkan untuk membuat method `resource` yang berisikan index, create, store, show, edit, update, dan destroy pada `controller`.

### Mengubah Model

Pada file model pada `app/Models/Periode.php` yang dihasilkan, tuliskan:

```php
protected $table = 'periode';
protected $guarded = [];
```

![model](/assets/image/laravel01-03/model.png){.w-60 .bingkai .kiri}

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

![migrations_periode](/assets/image/laravel01-03/migrations_periode.png){.bingkai .kiri}

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

![migrate_2](/assets/image/laravel01-03/migrate_2.png){.w-60 .bingkai .kiri}


### Perhatikan Controller

Perhatikan pada folder `app/Http/Controllers/PeriodeController.php`. Pada file tersebut sudah terdapat beberapa method untuk memanipulasi data yang merupakan bagian dari `resource`.

![controller_1](/assets/image/laravel01-03/controller_1.png){.w-70 .bingkai .kiri}

### Mengubah Seeder

Tambahkan pada file seeder `database/seeders/PeriodeSeeder.php`:

```php
use App\Models\Periode;
```

![seeder_1](/assets/image/laravel01-03/seeder_1.png){.w-70 .bingkai .kiri}

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

![seeder_2](/assets/image/laravel01-03/seeder_2.png){.w-50 .bingkai .kiri}

Panggil seeder yang sudah kita buat dalam function `run` pada file `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call(PeriodeSeeder::class);
}
```

![seeder_3](/assets/image/laravel01-03/seeder_3.png){.w-80 .bingkai .kiri}

Jalankan dengan perintah:

```bash
php artisan migrate:fresh --seed
```

![seeder_4](/assets/image/laravel01-03/seeder_4.png){ .bingkai .kiri}

alternatif migration untuk menyertakan seeder bisa menggunakan:

```bash
php artisan migrate:fresh --seed --seeder=PeriodeSeeder
```
atau jika satu seeder saja yang ingin dieksekusi bisa menggunakan:

```bash
php artisan db:seed --class=PeriodeSeeder
```

## Ringkasan perintah

| Perintah | Fungsi | Data lama |
|---|---|---|
| `php artisan migrate` | Membuat tabel dari migration yang **belum** pernah dijalankan | Tetap ada |
| `php artisan migrate:fresh` | Menghapus **semua** tabel, lalu membuat ulang dari awal | **Hilang** |
| `php artisan migrate:fresh --seed` | Seperti di atas, lalu menjalankan `DatabaseSeeder` | **Hilang**, diganti data seeder |
| `php artisan db:seed --class=PeriodeSeeder` | Hanya menjalankan satu seeder | Tetap ada, data seeder ditambahkan |

Gunakan `migrate:fresh` hanya selama pengembangan. Perintah ini menghapus seluruh data di database, termasuk data yang dimasukkan lewat aplikasi.

## Rangkuman

- Koneksi database diatur di `.env` (`DB_CONNECTION=mysql`, `DB_DATABASE`, dan seterusnya), lalu diuji dengan `php artisan migrate`.
- `php artisan make:model Periode -mcrs` membuat model, migration, resource controller, dan seeder sekaligus.
- Karena nama tabel memakai Bahasa Indonesia (`periode`, bukan `periodes`), nama tabel ditulis di migration dan di model dengan `protected $table`.
- `protected $guarded = []` membuat semua kolom boleh diisi lewat `create()` dan `update()`.
- Seeder mengisi data awal; `create()` mengisi `created_at` dan `updated_at` otomatis, sedangkan `insert()` tidak.

## Latihan

1. Tambahkan dua periode lagi di `PeriodeSeeder` (misalnya **GANJIL 2027/2028** dan **GENAP 2027/2028**), lalu jalankan `php artisan migrate:fresh --seed`. Periksa hasilnya di phpMyAdmin/HeidiSQL.
2. Ubah salah satu periode di seeder menjadi berstatus aktif dengan menambahkan `'status' => true`. Jalankan ulang seeder dan pastikan kolom `status` bernilai `1`. Petunjuk: gunakan cara pertama (`Periode::create`); pada `insert()`, setiap baris harus memiliki kolom yang sama.

## Persiapan Pertemuan 4

Pada Pertemuan 4, proyek akan diberi tampilan admin menggunakan template **AdminLTE**. Pastikan:

- tabel `periode` sudah terisi data dari seeder;
- koneksi internet tersedia untuk mengunduh template AdminLTE dan file proyek pendukung.

## Referensi

- [Laravel: Database Configuration](https://laravel.com/docs/database#configuration)
- [Laravel: Migrations](https://laravel.com/docs/migrations)
- [Laravel: Eloquent Model Conventions](https://laravel.com/docs/eloquent#eloquent-model-conventions)
- [Laravel: Seeding](https://laravel.com/docs/seeding)
