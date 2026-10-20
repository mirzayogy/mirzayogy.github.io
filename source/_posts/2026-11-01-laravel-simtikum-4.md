---
title: "Pertemuan 4: Menggunakan Template AdminLTE"
description: "Merancang tabel SIMTIKUM dengan migration dan mengisi data awal dengan seeder."
date: 2026-11-01
series: laravel-simtikum
pertemuan: 4
draft: false
tags: [laravel, perkuliahan, database]
---

<!-- TEMPLATE: ganti bagian bertanda … dan lengkapi penjelasan sebelum dipublikasikan. -->
<!-- https://drive.google.com/file/d/1SqeJHV_pJI7tgzHPjNecmOypmH85sbWX/view?usp=share_link -->
## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menggunakan template [AdminLTE](https://github.com/ColorlibHQ/AdminLTE/archive/refs/tags/v4.10.1.zip)

<!-- - Menggunakan parameter route dan named route
- Membuat controller dengan Artisan
- Mengelompokkan route dengan prefix dan name
- Mengirim data dari controller ke view
- Membuat layout utama dan memakainya di banyak halaman
- Menggunakan direktif Blade (`@if`, `@foreach`, `@include`)
- Membuat komponen Blade sederhana
- Menjelaskan fungsi migration sebagai version control database
- Membuat dan menjalankan migration
- Mendefinisikan foreign key
- Mengisi data awal dengan seeder dan factory -->

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.

## Materi


### Mendownload Template AdminLTE

Unduh template AdminLTE deri URL berikut:

[Template AdminLTE](https://github.com/ColorlibHQ/AdminLTE/archive/refs/tags/v4.10.1.zip)

Letakkan folder `dist` ke dalam folder `public/`. 

![Folder dist](/assets/image/folder-dist.png){.w-50 .bingkai .kiri}

Unduh file berikut:

[File Project](https://drive.google.com/file/d/1SqeJHV_pJI7tgzHPjNecmOypmH85sbWX/view?usp=share_link)

Letakkan file-file tersebut ke dalam folder `resources/views`

![File Template](/assets/image/file-template.png){.w-50 .bingkai .kiri}

File-file ini digunakan sebagai template hasil kustomisasi dari AdminLTE yang sudah disesuaikan untuk pelajaran ini.

Buka file `routes/web.php` dan ganti isinya menjadi:

![web1](/assets/image/web-1.png){.w-75 .bingkai .kiri}

### Mengatur Layout

Buat folder `layouts` pada `resources/views`

![layouts](/assets/image/resource-views-layouts.png){.w-50 .bingkai .kiri}

Buat file `app.blade.php` dalam folder `resources/views/layouts`, kemudian salin isinya dari file `dashboard.blade.php`.

![File app.blade.php](/assets/image/file-app-1.png)

Pada file `dashboard.blade.php`, cut dari baris 431-481:

![File app.blade.php 431](/assets/image/scripts-431.png)

Buat folder `components` pada `resources/views/layouts` dan di dalamnya buat file `scripts.blade.php`, Tempelkan dari sebelumnya:

![File script.blade.php](/assets/image/file-scripts.png)

Kembali ke file `dashboard.blade.php`, Tambahkan perintah berikut pada baris 431:

```php
@include('layouts.components.scripts')
```

![Scripts include](/assets/image/scripts-include.png)

Masih file `dashboard.blade.php`, cut dari baris 416-428: