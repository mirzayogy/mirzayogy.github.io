---
title: "Laravel 1: Pengenalan Laravel"
description: "Rencana Perkuliahan, Penilaian, dan Tugas Akhir. Mengenal Laravel, dan pola MVC."
date: 2026-09-15
series: laravel-simtikum
pertemuan: 1
draft: false
tags: [laravel, perkuliahan, php, instalasi]
featured: true
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menjelaskan apa itu framework dan pola MVC pada Laravel
- Menyiapkan PHP, Composer, dan database di komputer sendiri


## Persiapan

Matakuliah Pemrograman Berorientasi Objek 2 akan menggunakan Laravel, berikut adalah beberapa file yang perlu dipersiapkan:

- Download [XAMPP](https://www.apachefriends.org/download.html) atau [Laragon](https://laragon.org/download).
- Download [NodeJS](https://nodejs.org/en/download). Boleh yang current maupun yang LTS.
- Download [composer](https://getcomposer.org/download/).



## Materi

### Apa itu Laravel?

Framework PHP siap pakai yang menyediakan struktur proyek, kumpulan pustaka, dan aturan penulisan kode untuk membangun aplikasi web. Contohnya Laravel, Symfony, dan CodeIgniter. Di mata kuliah ini kita memakai Laravel, yang saat ini paling populer.

Ekosistem Laravel punya tiga bagian utama:

- Artisan adalah alat baris perintah bawaan untuk membuat dan mengelola proyek, misalnya `make:model`, `migrate`, dan `serve`.
- Eloquent adalah ORM yang memetakan tabel database menjadi class (Model), sehingga data diakses lewat objek tanpa menulis SQL manual.
- Blade adalah template engine untuk membuat tampilan, dengan dukungan layout, komponen, dan perintah seperti `@foreach` serta `@if`.

Alasan memakai framework dibanding PHP native:

- Struktur kode rapi dan terpisah (MVC), jadi lebih mudah dibaca dan dipelihara, terutama saat bekerja dalam tim.
- Fitur dasar sudah tersedia, seperti routing, validasi, autentikasi, dan migration, sehingga tidak perlu dibuat dari nol.
- Keamanan lebih terjaga karena ada proteksi bawaan terhadap CSRF, SQL injection, dan XSS.
- Prinsip OOP seperti inheritance, interface, dan dependency injection langsung terpakai dalam praktik.
- Dokumentasi dan komunitasnya besar, dan aplikasinya lebih mudah diuji dan dikembangkan.

Kekurangannya, framework butuh waktu belajar dan terasa berlebihan untuk skrip kecil. Untuk aplikasi yang akan terus berkembang, PHP native biasanya justru lebih sulit dipelihara.

### Pola MVC

Alur MVC yang dilalui bisa dilihat lewat pada gambar berikut:

![Alur MVC pada Laravel](/assets/image/mvc.png)

Langkah-langkahnya:

- Request masuk. Browser mengirim request ke server. Semua request diterima `public/index.php`, yang menyalakan framework Laravel.
- Route. Laravel mencocokkan URL dengan daftar rute di `routes/web.php`, lalu meneruskan request ke Controller yang sesuai. Misal: 
  
```php
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
```

- Jika ada middleware (misalnya `auth`), request diperiksa dulu di tahap ini.
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

## Referensi

- [Installation](https://laravel.com/docs/installation)
- [Directory Structure](https://laravel.com/docs/structure)
