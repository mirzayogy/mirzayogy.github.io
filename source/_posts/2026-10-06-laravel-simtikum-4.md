---
title: "Pertemuan 4: Menggunakan Template AdminLTE"
description: "Merancang tabel SIMTIKUM dengan migration dan mengisi data awal dengan seeder."
date: 2026-10-06
series: laravel-simtikum
pertemuan: 4
draft: false
tags: [laravel, perkuliahan, database]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menggunakan template [AdminLTE](https://github.com/ColorlibHQ/AdminLTE/archive/refs/tags/v4.10.1.zip)



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

```php
Route::get('/', function () {
    return view('dashboard');
});

Route::view('/tables', 'tables')->name('tables');
Route::view('/edit', 'edit')->name('edit');
Route::view('/datatables', 'datatables')->name('datatables');
```

![web1](/assets/image/web-1.png){.w-75 .bingkai .kiri}

Perhatikan isinya pada laman `/`

![awal-d](/assets/image/awal-dashboard.png)

Pada laman `/tables`

![awal-t1](/assets/image/awal-table.png)

Ketika pada `/tables` klik tombol `New User`:

![awal-t2](/assets/image/awal-table-modal.png)

Ketika pada `/tables` klik logo `pencil` paling atas

![awal-t3](/assets/image/awal-table-edit.png)

Pada laman `/datatables`

![awal-datatables](/assets/image/awal-datatables.png)

### Mengatur Layout

Buat folder `layouts` pada `resources/views`

![layouts](/assets/image/resource-views-layouts.png){.w-50 .bingkai .kiri}

Buat file `app.blade.php` dalam folder `resources/views/layouts`, kemudian salin isinya dari file `dashboard.blade.php`.

![File app.blade.php](/assets/image/file-app-1.png)

Pada file `app.blade.php`, <mark>cut</mark> dari baris `431-481`:

![File app.blade.php 431](/assets/image/scripts-431.png)

Buat folder `components` pada `resources/views/layouts` dan di dalamnya buat file `scripts.blade.php`, <mark>paste</mark> dari sebelumnya:

![File script.blade.php](/assets/image/file-scripts.png)

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `431`:

```php
@include('layouts.components.scripts')
```

![Scripts include](/assets/image/scripts-include.png){.w-70 .bingkai .kiri}

Masih file `app.blade.php`, <mark>cut</mark> dari baris `415-428`:

![Potong Footer Awal](/assets/image/potong-footer-awal.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `footer.blade.php`, <mark>paste</mark> dari sebelumnya:

![Potong Footer File](/assets/image/potong-footer-file.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `415`:

```php
@include('layouts.components.footer')
```

Hapus tanda `</div>` pada baris `411` dan juga baris `410` karena ini adalah kesalahan saat pembuatan template

![Perbaikan div 1](/assets/image/perbaikan-div-1.png){.w-50 .bingkai .kiri}
![Perbaikan div 2](/assets/image/perbaikan-div-2.png){.w-50 .bingkai .kiri}

Masih pada file `app.blade.php`, <mark>cut</mark> dari baris `336-406`:

![badge 1](/assets/image/badge-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `badges.blade.php`, <mark>paste</mark> dari sebelumnya.

![badge 2](/assets/image/badge-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `336`:

```php
@include('layouts.components.badges')
```

![badge 3](/assets/image/badge-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `314-327`:

![breadcrumb 1](/assets/image/breadcrumb-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `breadcrumb.blade.php`, <mark>paste</mark> dari sebelumnya.

![breadcrumb 2](/assets/image/breadcrumb-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `314`:

```php
@include('layouts.components.breadcrumb')
```

![breadcrumb 3](/assets/image/breadcrumb-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `223-307`:

![sidebar 1](/assets/image/sidebar-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `sidebar.blade.php`, <mark>paste</mark> dari sebelumnya.

![sidebar 2](/assets/image/sidebar-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `223`:

```php
@include('layouts.components.sidebar')
```

![sidebar 3](/assets/image/sidebar-3.png){.w-70 .bingkai .kiri}


Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `99-222`:

![nav 1](/assets/image/nav-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `nav.blade.php`, <mark>paste</mark> dari sebelumnya.

![nav 2](/assets/image/nav-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `99`:

```php
@include('layouts.components.nav')
```

![nav 3](/assets/image/nav-3.png){.w-70 .bingkai .kiri}


Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `3-93`:

![head 1](/assets/image/head-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/component` Tambahkan file `head.blade.php`, <mark>paste</mark> dari sebelumnya.

![head 2](/assets/image/head-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, Tambahkan perintah berikut pada baris `3`:

```php
@include('layouts.components.head')
```

![head 3](/assets/image/head-3.png){.w-70 .bingkai .kiri}

Hapus komentar jika ingin tidak ada komentar menjadi seperti berikut

![clean 1](/assets/image/clean-1.png){.w-70 .bingkai .kiri}

Ada beberapa hal yang perlu diperhatikan agar template berjalan normal terkait dengan <mark>css</mark> dan <mark>javascript</mark> yang digunakan. Pada file `resource/views/layouts/components/head.blade.php` ganti href dari link pada baris 67 dan 88

![head 4](/assets/image/head-4.png){.w-70 .bingkai .kiri}

Menjadi:

```php
{{ asset('dist/css/adminlte.css') }}
```

![head 5](/assets/image/head-5.png){.w-70 .bingkai .kiri}

Lakukan hal yang sama pada `resource/views/layouts/components/scripts.blade.php` baris `11`

![scripts 1](/assets/image/scripts-1.png){.w-70 .bingkai .kiri}

Menjadi:

```php
{{ asset('dist/js/adminlte.js') }}
```

![scripts 2](/assets/image/scripts-2.png){.w-70 .bingkai .kiri}

### Penggunaan Layout

Buka `resource/views/dashboard.blade.php` ganti isinya menjadi seperti berikut:

```php
@extends('layouts.app')
```

![dashboard 1](/assets/image/dashboard-1.png){.w-70 .bingkai .kiri}

Hal ini menandakan dashboard melakukan extends dari file `resource/views/layouts/app.blade.php`.

Buka `resource/views/layouts/app.blade.php` ganti baris `12` dan `17` menjadi seperti berikut:

![dashboard 2](/assets/image/dashboard-2.png){.w-70 .bingkai .kiri}

Sehingga dashboard perlu diperbarui menjadi:

![dashboard 3](/assets/image/dashboard-3.png){.w-70 .bingkai .kiri}

