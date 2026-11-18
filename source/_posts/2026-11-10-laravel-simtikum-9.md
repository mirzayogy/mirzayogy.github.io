---
title: "Laravel 9: Combobox, Combobox dari Tabel Lain, dan Radiobutton"
description: "Membuat halaman data praktikum dengan combobox berisi pilihan tetap, combobox yang datanya diambil dari tabel periode, dan radiobutton, lengkap dengan relasi tabel dan validasinya."
date: 2026-11-10
series: laravel-simtikum
pertemuan: 9
draft: true
comments: true
tags: [laravel, perkuliahan, form, relasi]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Membuat combobox (`<select>`) dengan pilihan tetap
- Membuat combobox yang pilihannya diambil dari tabel lain
- Membuat radiobutton dan menjaga pilihannya saat validasi gagal maupun saat mengubah data
- Membuat relasi antartabel dengan foreign key, `belongsTo`, dan `hasMany`
- Memvalidasi pilihan dengan aturan `exists` dan `Rule::in`

## Persiapan

- Proyek dari Pertemuan 7 (CRUD dan laporan program studi) sudah berjalan.
- Data periode dari seeder sudah ada.

## Materi

### Gambaran

Kita membuat halaman **Praktikum**. Setiap praktikum berada pada satu periode, sehingga tabel `praktikum` **berelasi** dengan tabel `periode`.

| Kolom | Tipe input di form | Sumber pilihan |
|---|---|---|
| `periode_id` | **Combobox dari tabel lain** | Tabel `periode` |
| `kode` | Teks | — |
| `nama` | Teks | — |
| `semester` | **Combobox** | Pilihan tetap: 1–8 |
| `sifat` | **Radiobutton** | Pilihan tetap: Wajib, Pilihan |

Kapan memakai combobox dan kapan radiobutton?

- **Radiobutton** cocok untuk pilihan yang sedikit (2–4) karena semua pilihan langsung terlihat dan cukup satu klik.
- **Combobox** cocok untuk pilihan yang banyak atau datanya bisa bertambah, seperti daftar periode, agar form tetap ringkas.

Relasinya:

```
periode                       praktikum
┌────┬──────────────────┐     ┌────┬────────────┬──────┬───────────────────┬──────────┬─────────┐
│ id │ periode          │     │ id │ periode_id │ kode │ nama              │ semester │ sifat   │
├────┼──────────────────┤     ├────┼────────────┼──────┼───────────────────┼──────────┼─────────┤
│ 1  │ GANJIL 2025/2026 │◄────│ 1  │ 1          │ PW1  │ Pemrograman Web 1 │ 3        │ Wajib   │
│ 2  │ GENAP 2025/2026  │  └──│ 2  │ 1          │ MM1  │ Multimedia        │ 5        │ Pilihan │
└────┴──────────────────┘     └────┴────────────┴──────┴───────────────────┴──────────┴─────────┘
       satu periode  ───────►  banyak praktikum
```

### 1. Model, migration, controller, dan seeder

Seperti pada Pertemuan 3, buat semuanya dengan satu perintah:

```bash
php artisan make:model Praktikum -mcrs
```

### 2. Migration

Buka `database/migrations/xxxx_create_praktikums_table.php`. Ganti nama tabel `praktikums` menjadi `praktikum` (di `up` dan `down`), lalu isi kolomnya:

```php
public function up(): void
{
    Schema::create('praktikum', function (Blueprint $table) {
        $table->id();
        $table->foreignId('periode_id')->constrained('periode')->restrictOnDelete();
        $table->string('kode', 10)->unique();
        $table->string('nama', 100);
        $table->unsignedTinyInteger('semester');
        $table->string('sifat', 10);
        $table->timestamps();
    });
}
```

| Kode | Arti |
|---|---|
| `foreignId('periode_id')` | Kolom angka untuk menyimpan `id` dari tabel periode |
| `->constrained('periode')` | Membuat **foreign key**: nilai `periode_id` harus ada di kolom `id` tabel `periode` |
| `->restrictOnDelete()` | Periode yang masih dipakai praktikum **tidak boleh dihapus** |
| `unsignedTinyInteger('semester')` | Angka kecil tanpa minus (0–255), cukup untuk semester |
| `string('sifat', 10)` | Menyimpan teks `Wajib` atau `Pilihan` |

Jangan lupa ubah juga `down()` menjadi `Schema::dropIfExists('praktikum');`, lalu jalankan:

```bash
php artisan migrate
```

> Tabel `praktikum` harus dibuat **setelah** tabel `periode`, karena foreign key-nya menunjuk ke tabel `periode`. Laravel menjalankan migration berurutan sesuai tanggal pada nama file, jadi migration yang dibuat belakangan otomatis berjalan belakangan.

### 3. Model dan relasi

Isi `app/Models/Praktikum.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Praktikum extends Model
{
    protected $table = 'praktikum';
    protected $guarded = [];

    // Pilihan tetap untuk combobox semester dan radiobutton sifat
    public const SEMESTER = [1, 2, 3, 4, 5, 6, 7, 8];
    public const SIFAT = ['Wajib', 'Pilihan'];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
```

- `SEMESTER` dan `SIFAT` adalah **konstanta** berisi daftar pilihan. Daftar ini dipakai di dua tempat: untuk menampilkan pilihan di form dan untuk validasi di controller. Jika suatu saat pilihannya berubah, cukup diubah di sini.
- `belongsTo(Periode::class)` artinya setiap praktikum **dimiliki oleh** satu periode. Laravel otomatis memakai kolom `periode_id`.

Tambahkan relasi kebalikannya di `app/Models/Periode.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    protected $table = 'periode';
    protected $guarded = [];

    public function praktikum(): HasMany
    {
        return $this->hasMany(Praktikum::class);
    }
}
```

`hasMany` artinya satu periode **memiliki banyak** praktikum.

Dengan relasi ini:

```php
$praktikum->periode->periode;    // nama periode dari sebuah praktikum, misalnya "GANJIL 2025/2026"
$periode->praktikum;             // semua praktikum pada sebuah periode
```

### 4. Seeder

Isi `database/seeders/PraktikumSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Periode;
use App\Models\Praktikum;
use Illuminate\Database\Seeder;

class PraktikumSeeder extends Seeder
{
    public function run(): void
    {
        $periode = Periode::where('singkatan', '251')->first();

        Praktikum::create([
            'periode_id' => $periode->id,
            'kode' => 'PW1',
            'nama' => 'Pemrograman Web 1',
            'semester' => 3,
            'sifat' => 'Wajib',
        ]);

        Praktikum::create([
            'periode_id' => $periode->id,
            'kode' => 'BD1',
            'nama' => 'Basis Data 1',
            'semester' => 3,
            'sifat' => 'Wajib',
        ]);

        Praktikum::create([
            'periode_id' => $periode->id,
            'kode' => 'MM1',
            'nama' => 'Multimedia',
            'semester' => 5,
            'sifat' => 'Pilihan',
        ]);
    }
}
```

`periode_id` diambil dari periode yang sudah ada (`singkatan` 251), bukan ditulis angka langsung, agar seeder tetap benar walaupun `id` periode berbeda.

Daftarkan di `database/seeders/DatabaseSeeder.php`, **setelah** `PeriodeSeeder`:

```php
$this->call([
    PeriodeSeeder::class,
    ProdiSeeder::class,
    PraktikumSeeder::class,
]);
```

Jalankan hanya seeder praktikum:

```bash
php artisan db:seed --class=PraktikumSeeder
```

### 5. Route dan sidebar

Di `routes/web.php`, tambahkan `use` di bagian atas:

```php
use App\Http\Controllers\PraktikumController;
```

Lalu tambahkan resource route di bawah route prodi:

```php
Route::resource('praktikum', PraktikumController::class)->except('show');
```

Di `resources/views/layouts/components/sidebar.blade.php`, menu **Data Master** perlu terbuka untuk halaman periode, program studi, **dan** praktikum. Ubah kondisinya:

```blade
<li class="nav-item {{ request()->routeIs('periode.*', 'prodi.*', 'praktikum.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('periode.*', 'prodi.*', 'praktikum.*') ? 'active' : '' }}">
```

Lalu tambahkan submenu **Praktikum** di bawah submenu **Program Studi**:

```blade
<li class="nav-item">
    <a href="{{ route('praktikum.index') }}"
        class="nav-link {{ request()->routeIs('praktikum.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-circle"></i>
        <p>Praktikum</p>
    </a>
</li>
```

### 6. Controller

Isi `app/Http/Controllers/PraktikumController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Praktikum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PraktikumController extends Controller
{
    private array $pesan = [
        'periode_id.required' => 'Periode wajib dipilih.',
        'periode_id.exists' => 'Periode yang dipilih tidak ditemukan.',
        'kode.required' => 'Kode wajib diisi.',
        'kode.unique' => 'Kode sudah dipakai praktikum lain.',
        'nama.required' => 'Nama praktikum wajib diisi.',
        'semester.required' => 'Semester wajib dipilih.',
        'semester.in' => 'Semester tidak valid.',
        'sifat.required' => 'Sifat wajib dipilih.',
        'sifat.in' => 'Sifat tidak valid.',
    ];

    public function index()
    {
        $semua_praktikum = Praktikum::with('periode')
            ->orderBy('semester')
            ->orderBy('nama')
            ->get();

        return view('praktikum.index', compact('semua_praktikum'));
    }

    public function create()
    {
        $semua_periode = Periode::orderBy('singkatan', 'desc')->get();

        return view('praktikum.create', compact('semua_periode'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'periode_id' => ['required', 'exists:periode,id'],
            'kode' => ['required', 'string', 'max:10', 'unique:praktikum,kode'],
            'nama' => ['required', 'string', 'max:100'],
            'semester' => ['required', Rule::in(Praktikum::SEMESTER)],
            'sifat' => ['required', Rule::in(Praktikum::SIFAT)],
        ], $this->pesan);

        Praktikum::create($data);

        return redirect()->route('praktikum.index')
            ->with('success', 'Praktikum berhasil ditambahkan.');
    }

    public function edit(Praktikum $praktikum)
    {
        $semua_periode = Periode::orderBy('singkatan', 'desc')->get();

        return view('praktikum.edit', compact('praktikum', 'semua_periode'));
    }

    public function update(Request $request, Praktikum $praktikum)
    {
        $data = $request->validate([
            'periode_id' => ['required', 'exists:periode,id'],
            'kode' => ['required', 'string', 'max:10', 'unique:praktikum,kode,' . $praktikum->id],
            'nama' => ['required', 'string', 'max:100'],
            'semester' => ['required', Rule::in(Praktikum::SEMESTER)],
            'sifat' => ['required', Rule::in(Praktikum::SIFAT)],
        ], $this->pesan);

        $praktikum->update($data);

        return redirect()->route('praktikum.index')
            ->with('success', 'Praktikum berhasil diubah.');
    }

    public function destroy(Praktikum $praktikum)
    {
        $praktikum->delete();

        return redirect()->route('praktikum.index')
            ->with('success', 'Praktikum berhasil dihapus.');
    }
}
```

Hal baru dibanding CRUD periode:

| Kode | Fungsi |
|---|---|
| `Praktikum::with('periode')` | Mengambil data periode **sekaligus** (eager loading). Tanpa `with`, setiap baris tabel menjalankan satu query tambahan untuk mengambil nama periodenya |
| `$semua_periode` di `create` dan `edit` | Data untuk **mengisi pilihan combobox** periode. Karena itu kedua method ini sekarang mengirim data ke view |
| `exists:periode,id` | Nilai yang dipilih harus benar-benar ada di kolom `id` tabel `periode` |
| `Rule::in(Praktikum::SEMESTER)` | Nilai harus salah satu dari daftar konstanta |

Validasi pada combobox dan radiobutton tetap **wajib**. Isi `<option>` atau `value` radio bisa diubah siapa saja lewat *inspect element* di browser, sehingga tanpa validasi, nilai sembarang (misalnya semester 99) bisa tersimpan.

### 7. Form: tiga jenis input

Isi form untuk halaman tambah dan ubah sama, jadi dibuat satu file lalu dipanggil dengan `@include`, seperti halaman Program Studi di Pertemuan 7.

Buat `resources/views/praktikum/form.blade.php`:

```blade
{{-- 1. Combobox dari tabel lain: periode --}}
<div class="mb-3">
    <label for="periode_id" class="form-label">Periode</label>
    <select id="periode_id" name="periode_id" class="form-select @error('periode_id') is-invalid @enderror">
        <option value="">-- Pilih periode --</option>
        @foreach ($semua_periode as $periode)
            <option value="{{ $periode->id }}"
                @selected(old('periode_id', $praktikum->periode_id ?? '') == $periode->id)>
                {{ $periode->periode }}
            </option>
        @endforeach
    </select>
    @error('periode_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode" name="kode"
            value="{{ old('kode', $praktikum->kode ?? '') }}" placeholder="contoh: PW1" />
        @error('kode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-8 mb-3">
        <label for="nama" class="form-label">Nama Praktikum</label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
            value="{{ old('nama', $praktikum->nama ?? '') }}" placeholder="contoh: Pemrograman Web 1" />
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- 2. Combobox dengan pilihan tetap: semester --}}
<div class="mb-3">
    <label for="semester" class="form-label">Semester</label>
    <select id="semester" name="semester" class="form-select @error('semester') is-invalid @enderror">
        <option value="">-- Pilih semester --</option>
        @foreach (\App\Models\Praktikum::SEMESTER as $semester)
            <option value="{{ $semester }}" @selected(old('semester', $praktikum->semester ?? '') == $semester)>
                Semester {{ $semester }}
            </option>
        @endforeach
    </select>
    @error('semester')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- 3. Radiobutton: sifat --}}
<div class="mb-3">
    <label class="form-label d-block">Sifat</label>
    @foreach (\App\Models\Praktikum::SIFAT as $sifat)
        <div class="form-check form-check-inline">
            <input class="form-check-input @error('sifat') is-invalid @enderror" type="radio" name="sifat"
                id="sifat-{{ $loop->index }}" value="{{ $sifat }}"
                @checked(old('sifat', $praktikum->sifat ?? '') === $sifat) />
            <label class="form-check-label" for="sifat-{{ $loop->index }}">{{ $sifat }}</label>
        </div>
    @endforeach
    @error('sifat')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
```

#### Combobox dari tabel lain (periode)

- Pilihan dibuat dengan `@foreach ($semua_periode as $periode)` dari data yang dikirim controller.
- `value` berisi **`id`** periode (yang disimpan ke database), sedangkan teks yang terlihat adalah **nama** periodenya.
- Opsi pertama `<option value="">-- Pilih periode --</option>` bernilai kosong, sehingga jika tidak diganti, validasi `required` akan menolaknya.

#### Combobox dengan pilihan tetap (semester)

- Pilihan diambil dari konstanta `Praktikum::SEMESTER`, bukan ditulis satu per satu.
- Teks yang terlihat bisa berbeda dari nilainya: terlihat **Semester 3**, tersimpan `3`.

#### Radiobutton (sifat)

- Semua radio dalam satu kelompok **wajib memiliki `name` yang sama** (`name="sifat"`). Itulah yang membuat hanya satu yang bisa dipilih.
- Setiap radio membutuhkan `id` yang berbeda agar label bisa diklik. Dipakai `$loop->index` sehingga menjadi `sifat-0` dan `sifat-1`.
- `form-check-inline` membuat pilihan berjejer ke samping. Hapus kelas ini jika ingin bersusun ke bawah.
- Pesan error memakai `invalid-feedback d-block`. Pada radio, `invalid-feedback` tidak otomatis tampil karena letaknya tidak tepat setelah input, jadi dipaksa tampil dengan `d-block`.

#### Menjaga pilihan: `@selected` dan `@checked`

| Directive | Dipakai pada | Hasil jika kondisinya benar |
|---|---|---|
| `@selected(kondisi)` | `<option>` | menambahkan atribut `selected` |
| `@checked(kondisi)` | radio / checkbox | menambahkan atribut `checked` |

Kondisinya memakai `old('kolom', $praktikum->kolom ?? '')`:

- saat validasi gagal, pilihan yang tadi dipilih tetap terpilih (`old`);
- saat membuka halaman ubah, pilihan sesuai data di database;
- saat membuka halaman tambah, tidak ada yang terpilih (`''`).

Perhatikan perbandingan `==` (dua tanda sama dengan) pada periode dan semester. Nilai dari form selalu berupa **teks** (`"3"`), sedangkan dari database berupa **angka** (`3`). Dengan `===`, keduanya dianggap berbeda sehingga pilihan tidak akan pernah terpilih. Pada `sifat`, keduanya sama-sama teks, jadi `===` aman dipakai.

### 8. Halaman tambah, ubah, dan daftar

`resources/views/praktikum/create.blade.php`:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Praktikum</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('praktikum.index') }}">Praktikum</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Tambah Praktikum</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('praktikum.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('praktikum.store') }}" method="POST">
                        @csrf

                        @include('praktikum.form')

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('praktikum.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
```

`resources/views/praktikum/edit.blade.php`. Perbedaannya hanya breadcrumb, judul, `action`, `@method('PUT')`, dan teks tombol:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Praktikum</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('praktikum.index') }}">Praktikum</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Ubah Praktikum</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('praktikum.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('praktikum.update', $praktikum) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('praktikum.form')

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('praktikum.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1" aria-hidden="true"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1" aria-hidden="true"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
```

`resources/views/praktikum/index.blade.php`:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Praktikum</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Praktikum</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Praktikum</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('praktikum.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                                    Tambah Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Kode</th>
                                <th>Nama Praktikum</th>
                                <th>Periode</th>
                                <th>Semester</th>
                                <th>Sifat</th>
                                <th style="width: 40px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semua_praktikum as $praktikum)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $praktikum->kode }}</td>
                                    <td>{{ $praktikum->nama }}</td>
                                    <td>{{ $praktikum->periode->periode }}</td>
                                    <td>{{ $praktikum->semester }}</td>
                                    <td>
                                        @if ($praktikum->sifat === 'Wajib')
                                            <span class="badge text-bg-primary">Wajib</span>
                                        @else
                                            <span class="badge text-bg-info">Pilihan</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('praktikum.edit', $praktikum) }}"
                                                class="btn btn-outline-secondary" aria-label="Ubah {{ $praktikum->nama }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#modal-hapus-{{ $praktikum->id }}"
                                                aria-label="Hapus {{ $praktikum->nama }}">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        @include('praktikum.modal-hapus')
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-secondary">Belum ada data praktikum.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
```

`{{ $praktikum->periode->periode }}` menampilkan **nama** periode melalui relasi, bukan angka `periode_id`.

`resources/views/praktikum/modal-hapus.blade.php`:

```blade
<div class="modal fade" id="modal-hapus-{{ $praktikum->id }}" tabindex="-1"
    aria-labelledby="modal-hapus-label-{{ $praktikum->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-hapus-label-{{ $praktikum->id }}">Hapus praktikum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Yakin ingin menghapus praktikum <strong>{{ $praktikum->nama }}</strong>?
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('praktikum.destroy', $praktikum) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1" aria-hidden="true"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### 9. Menghapus periode yang masih dipakai

Karena `restrictOnDelete()`, database **menolak** penghapusan periode yang masih dipakai praktikum. Datanya aman, tetapi pengguna akan melihat halaman error 500. Agar lebih ramah, periksa dulu di `PeriodeController`:

```php
public function destroy(Periode $periode)
{
    if ($periode->praktikum()->exists()) {
        return back()->with('error', 'Periode tidak dapat dihapus karena masih dipakai oleh data praktikum.');
    }

    $periode->delete();

    return redirect()->route('periode.index')
        ->with('success', 'Periode berhasil dihapus.');
}
```

Lalu tambahkan alert merah di `resources/views/periode/index.blade.php`, tepat di bawah alert `session('success')`:

```blade
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
```

`$periode->praktikum()->exists()` memakai relasi `hasMany` dari langkah 3 untuk memeriksa apakah ada praktikum pada periode tersebut.

### Uji coba

1. Buka `/praktikum`. Data dari seeder tampil dengan **nama periode**, bukan angka, dan menu **Data Master → Praktikum** tersorot.
2. Klik **Tambah Data**. Combobox periode berisi semua periode dari database, combobox semester berisi Semester 1–8, dan ada dua radiobutton.
3. Klik **Simpan** tanpa mengisi apa pun. Kelima kolom menampilkan pesan error, termasuk radiobutton **Sifat**.
4. Pilih periode, semester, dan sifat, tetapi kosongkan **Kode**, lalu simpan. Setelah pesan error muncul, pilihan periode, semester, dan sifat **tetap terpilih**.
5. Simpan data dengan lengkap, lalu buka halaman ubahnya. Semua combobox dan radiobutton terpilih sesuai data.
6. Tambahkan periode baru di halaman Periode, lalu buka kembali form praktikum. Periode baru langsung muncul di combobox.
7. Coba hapus periode **GANJIL 2025/2026**. Muncul pesan bahwa periode masih dipakai dan datanya tidak terhapus.

### Jika terjadi error

| Gejala | Penyebab dan solusi |
|---|---|
| `Undefined variable $semua_periode` | Method `create` atau `edit` belum mengirim `$semua_periode` ke view |
| `Table 'praktikums' doesn't exist` | Lupa `protected $table = 'praktikum';` di model |
| Migration gagal: *foreign key constraint* | Tabel `periode` belum ada, atau nama di `constrained('periode')` salah |
| Pilihan combobox tidak tetap terpilih saat ubah | Memakai `===` untuk membandingkan teks dengan angka; gunakan `==` |
| Bisa memilih dua radiobutton sekaligus | Atribut `name` pada radio berbeda; harus sama semua |
| `Attempt to read property "periode" on null` di halaman daftar | Ada praktikum dengan `periode_id` yang tidak ada di tabel periode (biasanya dari data yang dimasukkan manual) |

## Latihan

1. Tambahkan kolom **hari** (Senin–Sabtu) memakai combobox dengan pilihan tetap.
2. Ubah combobox periode agar hanya menampilkan periode yang **aktif** (`status = true`).
3. Tambahkan **filter periode** di atas tabel halaman daftar praktikum: combobox berisi semua periode dan tombol **Tampilkan**. Petunjuk: kirim dengan form `GET`, lalu di controller gunakan `->when(request('periode_id'), ...)`.
4. Di halaman daftar periode, tambahkan kolom **Jumlah Praktikum**. Petunjuk: `Periode::withCount('praktikum')` lalu tampilkan `$periode->praktikum_count`.

## Rangkuman

- Combobox dengan pilihan tetap dan radiobutton dapat mengambil daftar pilihannya dari konstanta di model, agar sama dengan aturan validasinya.
- Combobox dari tabel lain diisi dari data yang dikirim controller; `value` berisi `id`, teks berisi nama.
- `@selected` dan `@checked` bersama `old()` menjaga pilihan saat validasi gagal dan saat mengubah data.
- Foreign key (`constrained`) menjaga data tetap konsisten; relasi `belongsTo` dan `hasMany` memudahkan mengambil data terkait.
- Validasi `exists` dan `Rule::in` tetap wajib walaupun pilihan sudah dibatasi di form.

## Referensi

- [Blade: Additional Attributes (`@selected`, `@checked`)](https://laravel.com/docs/blade#additional-attributes)
- [Eloquent Relationships: One to Many](https://laravel.com/docs/eloquent-relationships#one-to-many)
- [Migrations: Foreign Key Constraints](https://laravel.com/docs/migrations#foreign-key-constraints)
- [Validation: `exists`, `in`](https://laravel.com/docs/validation#available-validation-rules)
- [Eloquent: Eager Loading](https://laravel.com/docs/eloquent-relationships#eager-loading)
