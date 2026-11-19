---
title: "Laravel 1: Pengenalan Laravel dan Rencana Perkuliahan"
description: "Pengantar perkuliahan: studi kasus SIMTIKUM, rencana materi setiap pertemuan, persiapan perangkat lunak, serta mengenal Laravel dan pola MVC."
date: 2026-09-15
series: laravel-simtikum
pertemuan: 1
draft: false
comments: true
tags: [laravel, perkuliahan, php, mvc]
featured: true
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menjelaskan gambaran aplikasi yang akan dibangun selama perkuliahan
- Menyebutkan urutan materi dan keterkaitan antarpertemuan
- Menyiapkan perangkat lunak yang dibutuhkan (XAMPP/Laragon, Composer, Node.js)
- Menjelaskan apa itu framework dan pola MVC pada Laravel

## Studi kasus: SIMTIKUM

Sepanjang perkuliahan Pemrograman Berorientasi Objek 2, kita membangun **satu aplikasi utuh** secara bertahap, yaitu **SIMTIKUM (Sistem Informasi Praktikum)** untuk mengelola data praktikum di laboratorium. Setiap pertemuan menambahkan satu bagian ke proyek yang sama, sehingga di akhir semester mahasiswa memiliki aplikasi lengkap, bukan potongan-potongan latihan yang terpisah.

Fitur yang akan dibangun:

| Fitur | Isi | Dibahas di |
|---|---|---|
| Tampilan admin | Template AdminLTE: sidebar, navbar, dashboard | Pertemuan 4 |
| Data periode | Tambah, ubah, hapus, dan daftar periode (GANJIL/GENAP) | Pertemuan 3, 5 |
| Laporan PDF | Laporan siap cetak dengan kop dan nomor halaman | Pertemuan 6 |
| Data program studi | CRUD dan laporan program studi | Pertemuan 7 |
| Data praktikum | Pilihan periode dari tabel lain, semester, dan sifat praktikum | Pertemuan 9 |
| Login dan hak akses | Login tanpa pendaftaran, level admin dan pengajar, kelola pengguna | Pertemuan 10 |

## Rencana perkuliahan

| Pertemuan | Topik | Hasil di proyek SIMTIKUM |
|---|---|---|
| 1 | Pengenalan Laravel dan rencana perkuliahan | Perangkat lunak siap dipakai |
| 2 | Instalasi dan struktur proyek | Proyek Laravel berjalan di browser |
| 3 | Koneksi database, migration, dan seeder | Tabel `periode` beserta data awal |
| 4 | Template AdminLTE | Layout aplikasi dengan sidebar dan navbar |
| 5 | CRUD periode | Halaman daftar, tambah, ubah, dan hapus periode |
| 6 | Laporan PDF dengan spipu/html2pdf | Laporan periode siap cetak |
| 7 | Rangkuman cepat CRUD dan cetak PDF | Menu program studi lengkap |
| 8 | **Ujian Tengah Semester (praktik)** | — |
| 9 | Combobox, combobox dari tabel lain, dan radiobutton | Menu praktikum yang terhubung ke periode |
| 10 | Login, level pengguna, dan halaman khusus admin | Aplikasi terlindungi login, menu kelola pengguna |

Materi pertemuan berikutnya akan ditambahkan menyusul. Tautan ke setiap pertemuan yang sudah terbit ada di kotak **Seri** di atas dan di bawah halaman ini.

Setiap pertemuan **melanjutkan proyek dari pertemuan sebelumnya**. Karena itu:

- simpan proyek di folder yang sama sepanjang semester, jangan membuat proyek baru setiap pertemuan;
- jika tertinggal satu pertemuan, kerjakan dulu materi yang terlewat sebelum melanjutkan;
- simpan cadangan proyek (misalnya di GitHub) setelah setiap pertemuan.

## Persiapan

Pasang perangkat lunak berikut **sesuai urutan**:

1. **[XAMPP](https://www.apachefriends.org/download.html)** atau **[Laragon](https://laragon.org/download)**, untuk PHP dan MySQL. Pastikan versi PHP-nya **8.3 atau lebih baru**, karena Laravel versi terbaru tidak berjalan di PHP lama.
2. **[Composer](https://getcomposer.org/download/)**, untuk mengunduh Laravel dan pustaka PHP lain. Pasang setelah XAMPP/Laragon, karena installer Composer akan meminta lokasi `php.exe`.
3. **[Node.js](https://nodejs.org/en/download)**, versi LTS maupun Current, untuk Vite (kompilasi CSS dan JS).
4. **[Visual Studio Code](https://code.visualstudio.com)** sebagai editor kode.

Setelah terpasang, buka terminal baru dan periksa:

```bash
php -v
composer -V
node -v
```

Ketiga perintah harus menampilkan nomor versi. Jika muncul pesan *not recognized*, tutup lalu buka ulang terminal, atau ulangi pemasangan.

## Materi

### Apa itu Laravel?

**Framework** adalah kerangka kerja siap pakai yang menyediakan struktur proyek, kumpulan pustaka, dan aturan penulisan kode untuk membangun aplikasi web. Contohnya Laravel, Symfony, dan CodeIgniter. **Laravel** adalah framework PHP yang paling populer saat ini, dan dipakai di mata kuliah ini.

Ekosistem Laravel punya tiga bagian utama yang akan terus dipakai di setiap pertemuan:

- **Artisan** adalah alat baris perintah bawaan untuk membuat dan mengelola proyek, misalnya `make:model`, `migrate`, dan `serve`. Mulai dipakai di Pertemuan 2.
- **Eloquent** adalah ORM yang memetakan tabel database menjadi class (Model), sehingga data diakses lewat objek tanpa menulis SQL manual. Mulai dipakai di Pertemuan 3.
- **Blade** adalah template engine untuk membuat tampilan, dengan dukungan layout, komponen, dan perintah seperti `@foreach` serta `@if`. Mulai dipakai di Pertemuan 4.

Alasan memakai framework dibanding PHP native:

- Struktur kode rapi dan terpisah (MVC), jadi lebih mudah dibaca dan dipelihara, terutama saat bekerja dalam tim.
- Fitur dasar sudah tersedia, seperti routing, validasi, autentikasi, dan migration, sehingga tidak perlu dibuat dari nol.
- Keamanan lebih terjaga karena ada proteksi bawaan terhadap CSRF, SQL injection, dan XSS.
- Prinsip OOP seperti inheritance, interface, dan dependency injection langsung terpakai dalam praktik.
- Dokumentasi dan komunitasnya besar, dan aplikasinya lebih mudah diuji dan dikembangkan.

Kekurangannya, framework butuh waktu belajar dan terasa berlebihan untuk skrip kecil. Untuk aplikasi yang akan terus berkembang, PHP native biasanya justru lebih sulit dipelihara.

### Pola MVC

Alur MVC yang dilalui dapat dilihat pada gambar berikut:

![Alur MVC pada Laravel](/assets/image/laravel01-01/mvc.png)

Langkah-langkahnya:

- Request masuk. Browser mengirim request ke server. Semua request diterima `public/index.php`, yang menyalakan framework Laravel.
- Route. Laravel mencocokkan URL dengan daftar rute di `routes/web.php`, lalu meneruskan request ke Controller yang sesuai. Misal: 
  
```php
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
```

- Jika ada middleware (misalnya `auth` untuk memastikan pengguna sudah login, yang akan kita pasang di Pertemuan 10), request diperiksa dulu di tahap ini.
- Controller. Method `index()` menerima request dan mengatur prosesnya. Ia tidak mengambil data sendiri, melainkan meminta Model, misal:

```php
$data = Mahasiswa::all();
```

- Model. Model `Mahasiswa` memakai Eloquent untuk membuat query ke tabel `mahasiswa`, lalu mengembalikan hasilnya ke Controller sebagai objek.
- View. Controller mengirim data ke tampilan Blade, yang mengolahnya menjadi HTML:

```php
return view('mahasiswa.index', compact('data'));
```

- Response. HTML hasil render dikirim kembali ke browser dan ditampilkan ke pengguna.

Pembagian tugasnya: Route menentukan arah, Controller mengatur alur, Model mengurus data, dan View mengurus tampilan. Pemisahan ini yang membuat kode lebih rapi dan mudah dipelihara.

Untuk request yang mengirim data (seperti form tambah), alurnya sama. Bedanya ada validasi di Controller, dan Model menyimpan data ke database. Setelah itu Controller biasanya mengarahkan pengguna ke halaman lain (redirect) ketimbang menampilkan view langsung.

Di SIMTIKUM, alur ini akan langsung dipraktikkan mulai Pertemuan 5. Contohnya saat membuka halaman daftar periode: route `periode.index` → method `index()` di `PeriodeController` → model `Periode` mengambil data dari tabel `periode` → view `periode/index.blade.php` menampilkannya dalam tabel.

## Rangkuman

- Selama satu semester kita membangun satu aplikasi, SIMTIKUM, secara bertahap; setiap pertemuan melanjutkan proyek dari pertemuan sebelumnya.
- Perangkat lunak yang dibutuhkan: XAMPP/Laragon (PHP 8.3+), Composer, Node.js, dan Visual Studio Code.
- Laravel adalah framework PHP dengan tiga alat utama: Artisan, Eloquent, dan Blade.
- Pola MVC membagi tugas: Route menentukan arah, Controller mengatur alur, Model mengurus data, View mengurus tampilan.

## Persiapan Pertemuan 2

Pastikan `php -v`, `composer -V`, dan `node -v` sudah berhasil di komputer masing-masing. Pada Pertemuan 2 kita langsung membuat proyek Laravel pertama.

## Referensi

- [Laravel: Installation](https://laravel.com/docs/installation)
- [Laravel: Request Lifecycle](https://laravel.com/docs/lifecycle)
