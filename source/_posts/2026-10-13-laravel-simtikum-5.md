---
title: "Laravel 5: CRUD Periode dengan Template AdminLTE"
description: "Membuat halaman daftar, tambah, ubah, dan hapus data periode dengan resource controller, validasi, pesan sukses, konfirmasi hapus, dan menu sidebar."
date: 2026-10-13
series: laravel-simtikum
pertemuan: 5
draft: false
tags: [laravel, perkuliahan, crud, blade, adminlte]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menghubungkan resource route dengan resource controller
- Menampilkan data tabel `periode` pada halaman index
- Membuat form tambah dan ubah data dengan validasi
- Menghapus data dengan konfirmasi terlebih dahulu
- Menambahkan menu dan penanda menu aktif pada sidebar

## Persiapan

- Proyek dari Pertemuan 4 sudah memakai layout AdminLTE (`layouts/app.blade.php` dan folder `layouts/components`).
- Tabel `periode` dan `PeriodeSeeder` dari Pertemuan 3 sudah dijalankan (`php artisan migrate:fresh --seed`).

## Materi

### Gambaran CRUD

CRUD adalah singkatan dari **C**reate, **R**ead, **U**pdate, dan **D**elete. Dengan `Route::resource`, Laravel menyiapkan tujuh route sekaligus yang terhubung ke method di `PeriodeController`:

| Method HTTP | URL                       | Method controller | Nama route        | Fungsi                 |
| ----------- | ------------------------- | ----------------- | ----------------- | ---------------------- |
| GET         | `/periode`                | `index`           | `periode.index`   | Daftar periode         |
| GET         | `/periode/create`         | `create`          | `periode.create`  | Form tambah            |
| POST        | `/periode`                | `store`           | `periode.store`   | Simpan data baru       |
| GET         | `/periode/{periode}`      | `show`            | `periode.show`    | Detail (tidak dipakai) |
| GET         | `/periode/{periode}/edit` | `edit`            | `periode.edit`    | Form ubah              |
| PUT         | `/periode/{periode}`      | `update`          | `periode.update`  | Simpan perubahan       |
| DELETE      | `/periode/{periode}`      | `destroy`         | `periode.destroy` | Hapus data             |

File view yang akan dibuat:

```
resources/views/periode/
├── index.blade.php
├── create.blade.php
└── edit.blade.php
```

### Menambahkan route

Buka `routes/web.php`, tambahkan `use` di bagian atas dan resource route di bagian bawah:

```php
use App\Http\Controllers\PeriodeController;

// ...route yang sudah ada...

Route::resource('periode', PeriodeController::class)->except('show');
```

`->except('show')` membuang route detail karena tidak kita pakai. Periksa daftar route yang terbentuk dengan:

```bash
php artisan route:list --name=periode
```

### Halaman index

Buka `app/Http/Controllers/PeriodeController.php`, isi method `index`:

```php
public function index()
{
    $semua_periode = Periode::all();

    return view('periode.index', compact('semua_periode'));
}
```

Perintah `Periode::all();` berarti menampilkan seluruh data dari tabel periode, jika ingin mengubah urutannya bisa diganti dengan menggunakan perintah berikut `Periode::orderBy('id', 'desc')->get();` yang artinya menampilkan dengan urutan dari id yang terbesar terlebih dahulu.


Buat folder `resources/views/periode`, lalu buat file `index.blade.php`. Tampilan tabelnya diambil dari contoh pada `tables.blade.php`:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Periode</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Periode</li>
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
                            <h3 class="card-title">Periode</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('periode.create') }}" class="btn btn-sm btn-success">
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
                                <th>Periode</th>
                                <th>Singkatan</th>
                                <th>Status</th>
                                <th style="width: 40px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semua_periode as $periode)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $periode->periode }}</td>
                                    <td>{{ $periode->singkatan }}</td>
                                    <td>
                                        @if ($periode->status)
                                            <span class="badge text-bg-success">Aktif</span>
                                        @else
                                            <span class="badge text-bg-secondary">Tidak aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('periode.edit', $periode) }}"
                                                class="btn btn-outline-secondary" aria-label="Ubah {{ $periode->periode }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#modal-hapus-{{ $periode->id }}"
                                                aria-label="Hapus {{ $periode->periode }}">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        @include('periode.modal-hapus')
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary">Belum ada data periode.</td>
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

Hal yang perlu diperhatikan:

- `@forelse ... @empty ... @endforelse` menampilkan pesan jika tabel masih kosong.
- `$loop->iteration` menghasilkan nomor urut 1, 2, 3, dan seterusnya, tidak bergantung pada `id`.
- Kolom `status` bertipe boolean, jadi ditampilkan sebagai badge **Aktif** atau **Tidak aktif**, bukan angka `1` atau `0`.
- `route('periode.edit', $periode)` otomatis menghasilkan URL `/periode/{id}/edit`.
- Tombol **Tambah Data** sekarang berupa link biasa ke halaman create, **tanpa** `data-bs-toggle="modal"`.
- Blok `session('success')` menampilkan pesan setelah data berhasil ditambah, diubah, atau dihapus.

Buka `http://localhost:8000/periode` dan pastikan data hasil seeder tampil.

### Konfirmasi hapus

Modal hapus diambil dari contoh `#modal-delete-user` pada `tables.blade.php`. Karena setiap baris membutuhkan modal sendiri, `id` modal diberi akhiran `id` periode, misalnya `modal-hapus-3`.

Buat file `resources/views/periode/modal-hapus.blade.php`:

```blade
<div class="modal fade" id="modal-hapus-{{ $periode->id }}" tabindex="-1"
    aria-labelledby="modal-hapus-label-{{ $periode->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-hapus-label-{{ $periode->id }}">Hapus periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Yakin ingin menghapus periode <strong>{{ $periode->periode }}</strong>?
                    Data yang sudah dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('periode.destroy', $periode) }}" method="POST">
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

- Form HTML hanya mengenal method `GET` dan `POST`. `@method('DELETE')` menambahkan input tersembunyi agar Laravel memperlakukannya sebagai request `DELETE`.
- `@csrf` wajib ada di setiap form `POST` agar tidak ditolak dengan error **419 Page Expired**.

Isi method `destroy` di `PeriodeController`:

```php
public function destroy(Periode $periode)
{
    $periode->delete();

    return redirect()->route('periode.index')
        ->with('success', 'Periode berhasil dihapus.');
}
```

`Periode $periode` adalah **route model binding**: Laravel otomatis mencari data periode berdasarkan `{periode}` di URL. Jika tidak ditemukan, halaman 404 ditampilkan.

### Halaman tambah (create)

Halaman tambah **tidak memakai modal**, melainkan halaman sendiri. Tampilannya diambil dari contoh form pada `edit.blade.php`.

Isi method `create` dan `store`:

```php
public function create()
{
    return view('periode.create');
}

public function store(Request $request)
{
    $data = $request->validate([
        'periode' => ['required', 'string', 'max:50'],
        'singkatan' => ['required', 'string', 'max:10', 'unique:periode,singkatan'],
    ], [
        'periode.required' => 'Nama periode wajib diisi.',
        'singkatan.required' => 'Singkatan wajib diisi.',
        'singkatan.unique' => 'Singkatan sudah dipakai periode lain.',
    ]);

    $data['status'] = $request->boolean('status');

    Periode::create($data);

    return redirect()->route('periode.index')
        ->with('success', 'Periode berhasil ditambahkan.');
}
```

- `$request->validate()` memeriksa input. Jika gagal, pengguna otomatis dikembalikan ke form beserta pesan error dan isian sebelumnya.
- `unique:periode,singkatan` artinya singkatan tidak boleh sama dengan data lain di tabel `periode`.
- Checkbox yang tidak dicentang tidak ikut terkirim, karena itu nilai `status` diambil dengan `$request->boolean('status')` yang menghasilkan `true` atau `false`.

Buat file `resources/views/periode/create.blade.php`:

```blade
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Tambah Periode</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('periode.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('periode.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="periode" class="form-label">Periode</label>
                            <input type="text" class="form-control @error('periode') is-invalid @enderror"
                                id="periode" name="periode" value="{{ old('periode') }}"
                                placeholder="contoh: GANJIL 2027/2028" />
                            @error('periode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="singkatan" class="form-label">Singkatan</label>
                            <input type="text" class="form-control @error('singkatan') is-invalid @enderror"
                                id="singkatan" name="singkatan" value="{{ old('singkatan') }}"
                                placeholder="contoh: 271" />
                            @error('singkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                name="status" value="1" @checked(old('status')) />
                            <label class="form-check-label" for="status">Aktif</label>
                        </div>

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('periode.index') }}" class="btn btn-secondary">
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

Perbedaan dengan contoh pada `edit.blade.php`:

- `<form>` diberi `action="{{ route('periode.store') }}"` dan `method="POST"`, ditambah `@csrf`.
- Setiap input diberi atribut `name`. Tanpa `name`, nilai input tidak terkirim ke controller.
- `old('periode')` mengisi kembali input dengan nilai sebelumnya jika validasi gagal.
- `@error('periode') is-invalid @enderror` memberi garis merah pada input yang salah, dan `invalid-feedback` menampilkan pesan error-nya.
- Tombol **Batal** berupa link ke halaman index, bukan `data-bs-dismiss="modal"`, karena halaman ini bukan modal.
- Atribut `required` pada input dihapus agar validasi Laravel yang bekerja. Cara ini lebih aman karena validasi di browser bisa dilewati.

Coba tambahkan data baru, lalu coba juga kirim form dalam keadaan kosong untuk melihat pesan validasinya.

### Halaman ubah (edit)

Isi method `edit` dan `update`:

```php
public function edit(Periode $periode)
{
    return view('periode.edit', compact('periode'));
}

public function update(Request $request, Periode $periode)
{
    $data = $request->validate([
        'periode' => ['required', 'string', 'max:50'],
        'singkatan' => ['required', 'string', 'max:10', 'unique:periode,singkatan,' . $periode->id],
    ], [
        'periode.required' => 'Nama periode wajib diisi.',
        'singkatan.required' => 'Singkatan wajib diisi.',
        'singkatan.unique' => 'Singkatan sudah dipakai periode lain.',
    ]);

    $data['status'] = $request->boolean('status');

    $periode->update($data);

    return redirect()->route('periode.index')
        ->with('success', 'Periode berhasil diubah.');
}
```

Perhatikan aturan `unique:periode,singkatan,' . $periode->id`. Angka `id` di akhir membuat data yang sedang diubah **dikecualikan** dari pengecekan, sehingga menyimpan tanpa mengganti singkatan tidak dianggap duplikat.

Buat file `resources/views/periode/edit.blade.php`. Isinya hampir sama dengan `create.blade.php`, dengan perbedaan yang ditandai komentar:

```blade
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Ubah Periode</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('periode.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- action ke periode.update dan membawa data $periode --}}
                    <form action="{{ route('periode.update', $periode) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- form HTML tidak mengenal PUT --}}

                        <div class="mb-3">
                            <label for="periode" class="form-label">Periode</label>
                            {{-- old('periode', $periode->periode): pakai isian sebelumnya, jika tidak ada pakai data di database --}}
                            <input type="text" class="form-control @error('periode') is-invalid @enderror"
                                id="periode" name="periode" value="{{ old('periode', $periode->periode) }}" />
                            @error('periode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="singkatan" class="form-label">Singkatan</label>
                            <input type="text" class="form-control @error('singkatan') is-invalid @enderror"
                                id="singkatan" name="singkatan" value="{{ old('singkatan', $periode->singkatan) }}" />
                            @error('singkatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                name="status" value="1" @checked(old('status', $periode->status)) />
                            <label class="form-check-label" for="status">Aktif</label>
                        </div>

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('periode.index') }}" class="btn btn-secondary">
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

Klik ikon pensil pada salah satu baris di halaman index, ubah datanya, lalu simpan.

### Menu sidebar

Buka `resources/views/layouts/components/sidebar.blade.php`. Cari bagian `<ul class="nav sidebar-menu ...">`, lalu tambahkan menu **Data Master** berisi **Periode**:

```blade
<li class="nav-item {{ request()->routeIs('periode.*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->routeIs('periode.*') ? 'active' : '' }}">
        <i class="nav-icon bi bi-box-seam-fill"></i>
        <p>
            Data Master
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('periode.index') }}"
                class="nav-link {{ request()->routeIs('periode.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-calendar3"></i>
                <p>Periode</p>
            </a>
        </li>
    </ul>
</li>
```

Ubah juga menu **Dashboard** agar tidak selalu aktif:

```blade
<a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
```

- `request()->routeIs('periode.*')` bernilai `true` di semua halaman yang nama route-nya diawali `periode.`, yaitu index, create, dan edit.
- `menu-open` membuat menu Data Master terbuka otomatis, dan `active` memberi warna sorot pada menu yang sedang dibuka.
- Menu lain (misalnya Praktikum di pertemuan berikutnya) cukup ditambahkan sebagai `<li class="nav-item">` baru di dalam `<ul class="nav nav-treeview">`, dan kondisinya diganti menjadi `request()->routeIs('periode.*', 'praktikum.*')`.

### Uji coba

1. Buka `/periode`, pastikan data tampil dan menu **Data Master → Periode** tersorot.
2. Tambah periode baru, pastikan muncul pesan **Periode berhasil ditambahkan.**
3. Kirim form tambah dalam keadaan kosong, pastikan pesan validasi muncul di bawah input.
4. Tambah periode dengan singkatan yang sudah ada, pastikan ditolak.
5. Ubah salah satu periode tanpa mengganti singkatannya, pastikan tetap tersimpan.
6. Klik ikon tempat sampah, klik **Batal**, pastikan data tidak terhapus. Ulangi dan klik **Hapus**.

## Latihan

- Tampilkan jumlah data periode di samping judul **Periode** pada card header, misalnya `Periode (4)`.
- Tambahkan aturan validasi agar `singkatan` hanya boleh berisi angka (petunjuk: aturan `digits:3`).

## Rangkuman

- `Route::resource` menyiapkan route CRUD lengkap yang terhubung ke resource controller.
- Form tambah dan ubah membutuhkan `@csrf`; form ubah dan hapus juga membutuhkan `@method('PUT')` atau `@method('DELETE')`.
- `$request->validate()`, `old()`, dan `@error` bekerja sama untuk validasi dan menampilkan pesan error.
- `redirect()->with()` mengirim pesan sekali tampil yang dibaca dengan `session('success')`.
- `request()->routeIs()` dipakai untuk menandai menu sidebar yang sedang aktif.

## Referensi

- [Resource Controllers](https://laravel.com/docs/controllers#resource-controllers)
- [Validation](https://laravel.com/docs/validation)
- [Route Model Binding](https://laravel.com/docs/routing#route-model-binding)
- [Blade: Forms (`@csrf`, `@method`, `@error`)](https://laravel.com/docs/blade#forms)
