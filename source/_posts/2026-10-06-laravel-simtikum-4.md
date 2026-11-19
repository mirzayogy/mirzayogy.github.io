---
title: "Laravel 4: Menggunakan Template AdminLTE"
description: "Memasang template AdminLTE dan memecahnya menjadi layout Blade dengan @extends, @section, @yield, dan @include."
date: 2026-10-06
series: laravel-simtikum
pertemuan: 4
draft: false
tags: [laravel, perkuliahan, blade, adminlte]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Memasang template AdminLTE ke proyek Laravel
- Memecah template menjadi komponen dengan `@include`
- Membuat layout utama dengan `@extends`, `@section`, dan `@yield`
- Memanggil file CSS/JS dari folder `public` dengan `asset()`

## Persiapan

- Proyek SIMTIKUM dari pertemuan sebelumnya sudah berjalan.

## Materi

### Mengunduh Template AdminLTE

Unduh template AdminLTE dari URL berikut:

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

> **Kerjakan langkah berikut secara berurutan.** Nomor baris mengacu pada isi `dashboard.blade.php` asli. Pemotongan dimulai dari bagian bawah file agar nomor baris di atasnya tidak bergeser.

![File app.blade.php](/assets/image/file-app-1.png)

Pada file `app.blade.php`, <mark>cut</mark> dari baris `431-481`:

![File app.blade.php 431](/assets/image/scripts-431.png)

Buat folder `components` pada `resources/views/layouts` dan di dalamnya buat file `scripts.blade.php`, <mark>paste</mark> dari sebelumnya:

![File script.blade.php](/assets/image/file-scripts.png)

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `431`:

```blade
@include('layouts.components.scripts')
```

![Scripts include](/assets/image/scripts-include.png){.w-70 .bingkai .kiri}

Masih file `app.blade.php`, <mark>cut</mark> dari baris `415-428`:

![Potong Footer Awal](/assets/image/potong-footer-awal.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `footer.blade.php`, <mark>paste</mark> dari sebelumnya:

![Potong Footer File](/assets/image/potong-footer-file.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `415`:

```blade
@include('layouts.components.footer')
```

Hapus tanda `</div>` pada baris `411` dan juga baris `410` karena ini adalah kesalahan saat pembuatan template

![Perbaikan div 1](/assets/image/perbaikan-div-1.png){.w-50 .bingkai .kiri}
![Perbaikan div 2](/assets/image/perbaikan-div-2.png){.w-50 .bingkai .kiri}

Masih pada file `app.blade.php`, <mark>cut</mark> dari baris `336-406`:

![badge 1](/assets/image/badge-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `badges.blade.php`, <mark>paste</mark> dari sebelumnya.

![badge 2](/assets/image/badge-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `336`:

```blade
@include('layouts.components.badges')
```

![badge 3](/assets/image/badge-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `314-327`:

![breadcrumb 1](/assets/image/breadcrumb-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `breadcrumb.blade.php`, <mark>paste</mark> dari sebelumnya.

![breadcrumb 2](/assets/image/breadcrumb-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `314`:

```blade
@include('layouts.components.breadcrumb')
```

![breadcrumb 3](/assets/image/breadcrumb-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `223-307`:

![sidebar 1](/assets/image/sidebar-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `sidebar.blade.php`, <mark>paste</mark> dari sebelumnya.

![sidebar 2](/assets/image/sidebar-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `223`:

```blade
@include('layouts.components.sidebar')
```

![sidebar 3](/assets/image/sidebar-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `99-222`:

![nav 1](/assets/image/nav-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `nav.blade.php`, <mark>paste</mark> dari sebelumnya.

![nav 2](/assets/image/nav-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `99`:

```blade
@include('layouts.components.nav')
```

![nav 3](/assets/image/nav-3.png){.w-70 .bingkai .kiri}

Kembali pada file `app.blade.php`, <mark>cut</mark> dari baris `3-93`:

![head 1](/assets/image/head-1.png){.w-70 .bingkai .kiri}

Pada folder `resources/views/layouts/components`, tambahkan file `head.blade.php`, <mark>paste</mark> dari sebelumnya.

![head 2](/assets/image/head-2.png){.w-70 .bingkai .kiri}

Kembali ke file `app.blade.php`, tambahkan perintah berikut pada baris `3`:

```blade
@include('layouts.components.head')
```

![head 3](/assets/image/head-3.png){.w-70 .bingkai .kiri}

Komentar HTML boleh dihapus agar file lebih ringkas (opsional), sehingga menjadi seperti berikut:

![clean 1](/assets/image/clean-1.png){.w-70 .bingkai .kiri}

Ada beberapa hal yang perlu diperhatikan agar template berjalan normal terkait dengan file **CSS** dan **JavaScript** yang digunakan. Pada file `resources/views/layouts/components/head.blade.php` ganti `href` pada baris `67` dan `88`

![head 4](/assets/image/head-4.png){.w-70 .bingkai .kiri}

Menjadi:

```blade
{{ asset('dist/css/adminlte.css') }}
```

![head 5](/assets/image/head-5.png){.w-70 .bingkai .kiri}



Lakukan hal yang sama pada `resources/views/layouts/components/nav.blade.php` baris `78` dan `85`

![nav 4](/assets/image/nav-4.png){.w-70 .bingkai .kiri}

Menjadi:

```blade
{{ asset('dist/assets/img/user2-160x160.jpg') }}
```

![nav 5](/assets/image/nav-5.png){.w-70 .bingkai .kiri}


Lakukan hal yang sama pada `resources/views/layouts/components/scripts.blade.php` baris `11`

![scripts 1](/assets/image/scripts-1.png){.w-70 .bingkai .kiri}

Menjadi:

```blade
{{ asset('dist/js/adminlte.js') }}
```

![scripts 2](/assets/image/scripts-2.png){.w-70 .bingkai .kiri}

Lakukan hal yang sama pada `resources/views/layouts/components/sidebar.blade.php` baris `11`

![sidebar 4](/assets/image/sidebar-4.png){.w-70 .bingkai .kiri}

Menjadi:

```blade
{{ asset('dist/assets/img/AdminLTELogo.png') }}
```

![sidebar 5](/assets/image/sidebar-5.png){.w-70 .bingkai .kiri}


### Penggunaan Layout

Buka `resources/views/dashboard.blade.php`, ganti isinya menjadi seperti berikut:

```blade
@extends('layouts.app')
```

![dashboard 1](/assets/image/dashboard-1.png){.w-70 .bingkai .kiri}

Hal ini menandakan dashboard melakukan extends dari file `resources/views/layouts/app.blade.php`.

Buka `resources/views/layouts/app.blade.php`, ganti baris `12` menjadi `@yield('breadcrumb')` dan baris `17` menjadi `@yield('content')`:

```blade
<div class="app-content-header">
    <div class="container-fluid">
        @yield('breadcrumb')
    </div>
</div>
<div class="app-content">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>
```

![dashboard 2](/assets/image/dashboard-2.png){.w-70 .bingkai .kiri}

`@yield` adalah "slot" kosong di layout. Halaman yang memakai layout mengisinya dengan `@section`. Karena itu `dashboard.blade.php` diperbarui menjadi:

```blade
@extends('layouts.app')

@section('breadcrumb')
    @include('layouts.components.breadcrumb')
@endsection

@section('content')
    @include('layouts.components.badges')
@endsection
```

![dashboard 3](/assets/image/dashboard-3.png){.w-70 .bingkai .kiri}

Buka kembali `http://localhost:8000`. Tampilan dashboard harus sama persis seperti sebelum dipecah. Bedanya, sekarang kerangkanya ada di satu layout yang bisa dipakai ulang oleh halaman lain.

## Struktur akhir

```
resources/views/
├── dashboard.blade.php          ← @extends('layouts.app')
├── layouts/
│   ├── app.blade.php            ← kerangka halaman + @yield
│   └── components/
│       ├── head.blade.php       ← <head>, CSS
│       ├── nav.blade.php        ← navbar atas
│       ├── sidebar.blade.php    ← menu kiri
│       ├── breadcrumb.blade.php ← judul halaman
│       ├── badges.blade.php     ← isi dashboard
│       ├── footer.blade.php     ← footer
│       └── scripts.blade.php    ← JavaScript
├── tables.blade.php             ← contoh tabel (dipakai Pertemuan 5)
├── edit.blade.php               ← contoh form (dipakai Pertemuan 5)
└── datatables.blade.php         ← contoh tabel lanjutan
```

## Ringkasan directive Blade

| Directive | Ditulis di | Fungsi |
|---|---|---|
| `@include('layouts.components.nav')` | layout atau halaman | Menyisipkan isi file lain di tempat itu |
| `@extends('layouts.app')` | halaman (baris pertama) | Memakai file layout sebagai kerangka |
| `@yield('content')` | layout | Menyediakan "slot" kosong |
| `@section('content') ... @endsection` | halaman | Mengisi slot dengan nama yang sama |
| `{{ asset('dist/css/adminlte.css') }}` | mana saja | Membuat alamat lengkap ke file di folder `public` |

Nama di `@yield` dan `@section` harus **sama persis**. Jika `@section('konten')` dipakai untuk mengisi `@yield('content')`, isinya tidak akan tampil dan tidak ada pesan error.

## Rangkuman

- File AdminLTE (`dist`) diletakkan di folder `public`, dan dipanggil dari Blade dengan `asset()`.
- Template HTML yang panjang dipecah menjadi komponen kecil di `layouts/components` lalu digabung dengan `@include`.
- `layouts/app.blade.php` menjadi kerangka bersama. Setiap halaman cukup `@extends('layouts.app')` dan mengisi `@section('breadcrumb')` serta `@section('content')`.
- Jika ingin mengubah navbar, sidebar, atau footer, cukup ubah satu file komponen, dan semua halaman ikut berubah.

## Latihan

1. Ganti tulisan **AdminLTE 4** di sidebar (`sidebar.blade.php`) menjadi **SIMTIKUM**.
2. Ubah teks di `footer.blade.php` menjadi nama dan NIM Anda.
3. Buat halaman baru `resources/views/tentang.blade.php` yang memakai `@extends('layouts.app')`, dengan breadcrumb bertuliskan **Tentang** dan isi satu paragraf deskripsi SIMTIKUM. Daftarkan dengan `Route::view('/tentang', 'tentang');` lalu buka `http://localhost:8000/tentang`.

## Persiapan Pertemuan 5

Pada Pertemuan 5 kita membuat halaman daftar, tambah, ubah, dan hapus data periode di dalam layout ini. Pastikan:

- dashboard tampil dengan layout baru (`@extends('layouts.app')`);
- halaman `/tables` dan `/edit` masih bisa dibuka, karena tampilan tabel dan form-nya akan dijadikan contoh;
- tabel `periode` masih berisi data dari seeder Pertemuan 3.

## Referensi

- [AdminLTE 4](https://adminlte.io)
- [Laravel: Blade Templates](https://laravel.com/docs/blade)
- [Laravel: Layouts Using Template Inheritance](https://laravel.com/docs/blade#layouts-using-template-inheritance)
- [Laravel: URL Generation (`asset`)](https://laravel.com/docs/helpers#method-asset)
