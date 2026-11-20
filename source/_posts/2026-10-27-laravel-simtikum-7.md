---
title: "Laravel 7: Rangkuman Cepat CRUD dan Cetak PDF"
description: "Rangkuman langkah cepat membuat halaman CRUD dan laporan PDF dari nol, dengan contoh tabel program studi."
date: 2026-10-27
series: laravel-simtikum
pertemuan: 7
draft: false
comments: true
tags: [laravel, perkuliahan, crud, pdf, rangkuman]
---

Post ini merangkum Pertemuan 3–6 menjadi **satu alur cepat**. Gunakan sebagai contekan setiap kali membuat menu data baru. Contohnya tabel **program studi** dengan kolom `id`, `kode_prodi`, `nama_prodi`, `nama_kaprodi`, dan timestamp bawaan Laravel.

## Daftar langkah

| No | Langkah | File |
|---|---|---|
| 1 | Buat paket model | (perintah Artisan) |
| 2 | Isi migration, jalankan | `database/migrations/xxxx_create_prodis_table.php` |
| 3 | Atur model | `app/Models/Prodi.php` |
| 4 | Isi seeder, jalankan | `database/seeders/ProdiSeeder.php` |
| 5 | Tambah route | `routes/web.php` |
| 6 | Isi controller | `app/Http/Controllers/ProdiController.php` |
| 7 | Buat 6 view | `resources/views/prodi/` |
| 8 | Tambah menu | `layouts/components/sidebar.blade.php` |

## 1. Buat paket model

```bash
php artisan make:model Prodi -mcrs
```

![01-artisan](/assets/image/laravel01-07/01-artisan.png){.w-90 .bingkai .kiri}


`-m` migration, `-c` controller, `-r` resource (7 method CRUD), `-s` seeder.

## 2. Migration

Ganti nama tabel `prodis` menjadi `prodi` di `up()` **dan** `down()`:

```php
public function up(): void
{
    Schema::create('prodi', function (Blueprint $table) {
        $table->id();
        $table->string('kode_prodi', 10)->unique();
        $table->string('nama_prodi', 100);
        $table->string('nama_kaprodi', 100);
        $table->timestamps();
    });
}
```

![02-migration](/assets/image/laravel01-07/02-migration.png){.bingkai .kiri}


```bash
php artisan migrate
```

![03-migrate](/assets/image/laravel01-07/03-migrate.png){.bingkai .kiri}


## 3. Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
    protected $guarded = [];
}
```

![04-model-prodi](/assets/image/laravel01-07/04-model-prodi.png){.bingkai .kiri}

## 4. Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'nama_kaprodi' => 'Nama Kaprodi TI']);
        Prodi::create(['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'nama_kaprodi' => 'Nama Kaprodi SI']);
        Prodi::create(['kode_prodi' => 'MI', 'nama_prodi' => 'Manajemen Informatika', 'nama_kaprodi' => 'Nama Kaprodi MI']);
    }
}
```

![05-seeder](/assets/image/laravel01-07/05-seeder.png){.bingkai .kiri}


Tambahkan `ProdiSeeder::class` ke daftar `$this->call([...])` di `DatabaseSeeder.php`

![06-database-seeder](/assets/image/laravel01-07/06-database-seeder.png){.bingkai .kiri}


Jalankan perintah:

```bash
php artisan db:seed --class=ProdiSeeder
```
![07-cmd-seed](/assets/image/laravel01-07/07-cmd-seed.png){.bingkai .kiri}


## 5. Route

Di `routes/web.php`, tambahkan `use App\Http\Controllers\ProdiController;` di atas, lalu tambahkan di bawah route periode:

```php
Route::resource('prodi', ProdiController::class)->except('show');
Route::get('/laporan/prodi', [ProdiController::class, 'cetak'])->name('laporan.prodi');
```

![08-route](/assets/image/laravel01-07/08-route.png){.bingkai .kiri}


Cek dengan `php artisan route:list --name=prodi`.

## 6. Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;

class ProdiController extends Controller
{
    private function aturan(?Prodi $prodi = null): array
    {
        return [
            'kode_prodi' => ['required', 'string', 'max:10', 'unique:prodi,kode_prodi,' . $prodi?->id],
            'nama_prodi' => ['required', 'string', 'max:100'],
            'nama_kaprodi' => ['required', 'string', 'max:100'],
        ];
    }

    private array $pesan = [
        'kode_prodi.required' => 'Kode prodi wajib diisi.',
        'kode_prodi.unique' => 'Kode prodi sudah dipakai.',
        'nama_prodi.required' => 'Nama prodi wajib diisi.',
        'nama_kaprodi.required' => 'Nama kaprodi wajib diisi.',
    ];

    public function index()
    {
        $semua_prodi = Prodi::orderBy('kode_prodi')->get();

        return view('prodi.index', compact('semua_prodi'));
    }

    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $request)
    {
        Prodi::create($request->validate($this->aturan(), $this->pesan));

        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        return view('prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $prodi->update($request->validate($this->aturan($prodi), $this->pesan));

        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil diubah.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('prodi.index')->with('success', 'Prodi berhasil dihapus.');
    }

    public function cetak()
    {
        $semua_prodi = Prodi::orderBy('kode_prodi')->get();

        $pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 10, 15, 10]);
        $pdf->pdf->SetTitle('Laporan Program Studi');
        $pdf->writeHTML(view('prodi.cetak', compact('semua_prodi'))->render());

        return response($pdf->output('laporan-prodi.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan-prodi.pdf"');
    }
}
```

Dua trik agar controller lebih ringkas:

- **`aturan()`** menyimpan aturan validasi di satu tempat untuk `store` dan `update`. Saat ubah data, `$prodi?->id` mengecualikan data itu sendiri dari aturan `unique`. Saat tambah, `$prodi` bernilai `null` sehingga tidak ada yang dikecualikan.
- **`$request->validate(...)` langsung dimasukkan ke `create()` / `update()`**, karena hasil validasi sudah berupa array kolom yang lolos.

## 7. View

Buat folder `resources/views/prodi` berisi 6 file berikut.

### `index.blade.php`: daftar data

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Program Studi</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <h3 class="card-title me-auto">Program Studi</h3>
            <a href="{{ route('laporan.prodi') }}" target="_blank" class="btn btn-sm btn-danger">
                <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Cetak PDF
            </a>
            <a href="{{ route('prodi.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> Tambah Data
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Kode</th>
                        <th>Nama Prodi</th>
                        <th>Nama Kaprodi</th>
                        <th style="width: 40px">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($semua_prodi as $prodi)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $prodi->kode_prodi }}</td>
                            <td>{{ $prodi->nama_prodi }}</td>
                            <td>{{ $prodi->nama_kaprodi }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('prodi.edit', $prodi) }}" class="btn btn-outline-secondary"
                                        aria-label="Ubah {{ $prodi->nama_prodi }}">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modal-hapus-{{ $prodi->id }}"
                                        aria-label="Hapus {{ $prodi->nama_prodi }}">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                                @include('prodi.modal-hapus')
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">Belum ada data prodi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
```

### `modal-hapus.blade.php`: konfirmasi hapus

```blade
<div class="modal fade" id="modal-hapus-{{ $prodi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title">Hapus prodi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Yakin ingin menghapus <strong>{{ $prodi->nama_prodi }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('prodi.destroy', $prodi) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### `form.blade.php`: isian form (dipakai tambah & ubah)

Isian form tambah dan ubah sama, jadi cukup ditulis sekali lalu dipanggil dengan `@include('prodi.form')`. `$prodi->kode_prodi ?? ''` bernilai kosong di halaman tambah (variabel `$prodi` belum ada) dan berisi data di halaman ubah.

```blade
<div class="mb-3">
    <label for="kode_prodi" class="form-label">Kode Prodi</label>
    <input type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" name="kode_prodi"
        value="{{ old('kode_prodi', $prodi->kode_prodi ?? '') }}" placeholder="contoh: TI" />
    @error('kode_prodi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nama_prodi" class="form-label">Nama Prodi</label>
    <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" name="nama_prodi"
        value="{{ old('nama_prodi', $prodi->nama_prodi ?? '') }}" placeholder="contoh: Teknik Informatika" />
    @error('nama_prodi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="nama_kaprodi" class="form-label">Nama Kaprodi</label>
    <input type="text" class="form-control @error('nama_kaprodi') is-invalid @enderror" id="nama_kaprodi" name="nama_kaprodi"
        value="{{ old('nama_kaprodi', $prodi->nama_kaprodi ?? '') }}" placeholder="nama lengkap beserta gelar" />
    @error('nama_kaprodi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

### `create.blade.php`: tambah data

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Program Studi</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('prodi.index') }}">Program Studi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Tambah Prodi</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('prodi.store') }}" method="POST">
                @csrf
                @include('prodi.form')

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
```

### `edit.blade.php`: ubah data

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Program Studi</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('prodi.index') }}">Program Studi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Ubah Prodi</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('prodi.update', $prodi) }}" method="POST">
                @csrf
                @method('PUT')
                @include('prodi.form')

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
```

### `cetak.blade.php`: laporan PDF

```blade
<style>
    .data { width: 100%; border-collapse: collapse; }
    .data th { background-color: #0b6e69; color: #ffffff; padding: 2mm; border: 0.2mm solid #0b6e69; }
    .data td { padding: 2mm; border: 0.2mm solid #999999; }
    .tengah { text-align: center; }
</style>

<page backtop="10mm" backbottom="14mm">
    <page_footer>
        <table style="width: 100%; font-size: 9pt; color: #666666;">
            <tr>
                <td style="width: 50%;">Dicetak: {{ now()->format('d-m-Y H:i') }}</td>
                <td style="width: 50%; text-align: right;">Halaman [[page_cu]] dari [[page_nb]]</td>
            </tr>
        </table>
    </page_footer>

    <h3 class="tengah">LAPORAN DATA PROGRAM STUDI</h3>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 15%;">Kode</th>
                <th style="width: 40%;">Nama Prodi</th>
                <th style="width: 37%;">Nama Kaprodi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semua_prodi as $prodi)
                <tr>
                    <td class="tengah">{{ $loop->iteration }}</td>
                    <td class="tengah">{{ $prodi->kode_prodi }}</td>
                    <td>{{ $prodi->nama_prodi }}</td>
                    <td>{{ $prodi->nama_kaprodi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</page>
```

Untuk menambah kop dan tanda tangan, salin bagian `<page_header>` dan tanda tangan dari `periode/cetak.blade.php` (Pertemuan 6), lalu naikkan `backtop` menjadi `32mm`.

## 8. Menu sidebar

Di `sidebar.blade.php`, tambahkan `'prodi.*'` ke kondisi menu **Data Master**:

```blade
request()->routeIs('periode.*', 'prodi.*')
```

![09-sidebar](/assets/image/laravel01-07/09-sidebar.png){.bingkai .kiri}


Tambahkan submenu di dalam **Data Master**, di bawah submenu **Periode**:

```blade
<li class="nav-item">
    <a href="{{ route('prodi.index') }}"
        class="nav-link {{ request()->routeIs('prodi.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-circle"></i>
        <p>Program Studi</p>
    </a>
</li>
```

![10-sidebar](/assets/image/laravel01-07/10-sidebar.png){.bingkai .kiri}


Dan di bawah menu **Laporan Periode**:

```blade
<li class="nav-item">
    <a href="{{ route('laporan.prodi') }}" target="_blank" class="nav-link">
        <i class="nav-icon bi bi-file-earmark-pdf"></i>
        <p>Laporan Prodi</p>
    </a>
</li>
```

![11-sidebar](/assets/image/laravel01-07/11-sidebar.png){.bingkai .kiri}


## Daftar periksa

- [ ] `/prodi` menampilkan 3 data dari seeder, menu **Data Master → Program Studi** tersorot
- [ ] Simpan form kosong → tiga pesan error muncul
- [ ] Tambah dengan kode `TI` → ditolak "Kode prodi sudah dipakai."
- [ ] Ubah data tanpa mengganti kode → tersimpan
- [ ] Hapus → muncul konfirmasi, lalu data hilang
- [ ] **Cetak PDF** → laporan terbuka di tab baru dengan nomor halaman

## Pola yang sama untuk tabel lain

Untuk membuat menu baru, ulangi 8 langkah di atas dengan mengganti:

| Bagian | Prodi | Tabel baru |
|---|---|---|
| Model | `Prodi` | nama model (huruf besar di awal) |
| Nama tabel | `prodi` | nama tabel |
| Route & folder view | `prodi` | nama tabel |
| Variabel daftar | `$semua_prodi` | `$semua_...` |
| Variabel satu data | `$prodi` | sama dengan nama route (route model binding) |
| Kolom | `kode_prodi`, `nama_prodi`, `nama_kaprodi` | kolom tabel baru |

Untuk kolom berupa pilihan atau relasi ke tabel lain, kita akan memakai combobox dan radiobutton di Pertemuan 9.

## Latihan

Buat menu **Ruangan** (`kode_ruangan`, `nama_ruangan`, `kapasitas`) lengkap dengan CRUD dan cetak PDF dalam waktu **30 menit**, hanya dengan mengikuti rangkuman ini.
