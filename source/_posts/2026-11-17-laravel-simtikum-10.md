---
title: "Laravel 10: Login, Level Pengguna, dan Halaman Khusus Admin"
description: "Menambah kolom level pada tabel users, membuat login tanpa pendaftaran, membatasi halaman dengan middleware, dan membuat halaman kelola pengguna khusus admin."
date: 2026-11-17
series: laravel-simtikum
pertemuan: 10
draft: true
comments: true
tags: [laravel, perkuliahan, autentikasi, middleware]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menambah kolom pada tabel yang sudah ada dengan migration baru
- Membuat halaman login dan logout dengan `Auth` bawaan Laravel, tanpa fitur pendaftaran
- Membatasi halaman agar hanya bisa dibuka setelah login (`auth`)
- Membuat middleware untuk membatasi halaman berdasarkan level pengguna
- Membuat halaman kelola pengguna yang hanya bisa diakses admin

## Persiapan

- Proyek dari Pertemuan 9 sudah berjalan: CRUD periode, program studi, dan praktikum, beserta laporan PDF.

## Materi

### Gambaran

Aplikasi SIMTIKUM memiliki dua level pengguna:

| Level | Hak akses |
|---|---|
| `admin` | Semua halaman, termasuk kelola pengguna |
| `pengajar` | Semua halaman **kecuali** kelola pengguna |

Pengguna **tidak bisa mendaftar sendiri**. Akun hanya dibuat oleh admin melalui halaman Pengguna (atau lewat seeder untuk akun pertama).

Alurnya:

```
Tamu  ──► /login ──► Auth::attempt() ──► berhasil ──► Dashboard
                                    └──► gagal ─────► kembali ke /login + pesan error

Login ──► /user ──► middleware level:admin ──► admin    ──► halaman Pengguna
                                           └──► pengajar ──► 403 Forbidden
```

### 1. Menambah kolom `level` pada tabel `users`

Tabel `users` sudah dibuat oleh migration bawaan Laravel. Untuk menambah kolom, **buat migration baru**, jangan mengubah migration lama. Dengan cara ini, data yang sudah ada tidak hilang, karena cukup menjalankan `php artisan migrate` tanpa `migrate:fresh`.

```bash
php artisan make:migration add_level_to_users_table --table=users
```

Opsi `--table=users` membuat isi migration memakai `Schema::table` (mengubah tabel), bukan `Schema::create` (membuat tabel baru). Buka file `database/migrations/xxxx_add_level_to_users_table.php`, lalu isi:

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('level', 20)->default('pengajar')->after('password');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('level');
    });
}
```

- `default('pengajar')`: pengguna yang sudah ada otomatis berlevel `pengajar`.
- `after('password')`: kolom diletakkan setelah kolom `password` (hanya berpengaruh di MySQL).
- `down()` adalah kebalikan dari `up()`, dipakai saat `php artisan migrate:rollback`.

Jalankan:

```bash
php artisan migrate
```

Periksa di phpMyAdmin/HeidiSQL bahwa tabel `users` sekarang memiliki kolom `level`.

### 2. Mengubah model User

Buka `app/Models/User.php`. Tambahkan `'level'` ke daftar kolom yang boleh diisi, dan tambahkan method `isAdmin()`:

```php
#[Fillable(['name', 'email', 'password', 'level'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function isAdmin(): bool
    {
        return $this->level === 'admin';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

- Laravel versi terbaru menulis daftar kolom dengan **atribut** `#[Fillable([...])]`. Pada versi lama, isinya sama dengan `protected $fillable = ['name', 'email', 'password', 'level'];`. Jika `level` tidak ditambahkan di sini, nilainya **tidak akan tersimpan**.
- `isAdmin()` membuat pengecekan lebih mudah dibaca: `auth()->user()->isAdmin()`.
- Cast `'password' => 'hashed'` membuat password **otomatis di-hash** saat disimpan. Kita tidak perlu memanggil `Hash::make()` sendiri.

### 3. Akun awal dengan seeder

Karena tidak ada halaman pendaftaran, akun admin pertama dibuat lewat seeder.

```bash
php artisan make:seeder UserSeeder
```

Isi `database/seeders/UserSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simtikum.test',
            'password' => 'admin123',
            'level' => 'admin',
        ]);

        User::create([
            'name' => 'Budi Pengajar',
            'email' => 'budi@simtikum.test',
            'password' => 'budi123',
            'level' => 'pengajar',
        ]);
    }
}
```

Daftarkan di `database/seeders/DatabaseSeeder.php`. `UserSeeder` diletakkan pertama:

```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        PeriodeSeeder::class,
        ProdiSeeder::class,
        PraktikumSeeder::class,
    ]);
}
```

Jalankan **salah satu**:

```bash
# hanya menjalankan UserSeeder (data periode tetap ada)
php artisan db:seed --class=UserSeeder

# atau mengulang seluruh database dari awal (SEMUA data dihapus)
php artisan migrate:fresh --seed
```

Periksa tabel `users`: kolom `password` berisi teks acak diawali `$2y$`. Itu adalah hasil hash, bukan password aslinya.

### 4. Halaman login

#### Controller

```bash
php artisan make:controller AuthController
```

Isi `app/Http/Controllers/AuthController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

Penjelasan method `login`:

| Kode | Fungsi |
|---|---|
| `Auth::attempt($credentials, ...)` | Mencari user berdasarkan email, lalu mencocokkan password dengan hash di database. Hasilnya `true` jika cocok |
| `$request->boolean('remember')` | Jika "Ingat saya" dicentang, pengguna tetap login walaupun browser ditutup |
| `session()->regenerate()` | Membuat ID sesi baru setelah login, untuk mencegah serangan *session fixation* |
| `redirect()->intended(...)` | Kembali ke halaman yang tadinya ingin dibuka sebelum diminta login; jika tidak ada, ke dashboard |
| `withErrors(...)->onlyInput('email')` | Kembali ke form dengan pesan error; email tetap terisi, password dikosongkan |

Pesan error sengaja dibuat umum ("Email atau password salah") dan tidak menyebut mana yang salah, agar orang lain tidak bisa menebak email mana yang terdaftar.

Method `logout` mengeluarkan pengguna, menghapus sesi, dan membuat ulang token CSRF.

#### View

Tampilan login diambil dari template `public/dist/examples/login.html`. Bagian **Sign in using Facebook/Google**, **I forgot my password**, dan **Register a new membership** dihapus karena tidak dipakai.

Buat folder `resources/views/auth`, lalu buat file `login.blade.php`:

```blade
<!doctype html>
<html lang="id">
@include('layouts.components.head')

<body class="login-page bg-body-secondary">
    <main class="login-box">
        <h1 class="login-logo">
            <b>SIM</b>TIKUM
        </h1>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silakan masuk untuk memulai</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <label class="visually-hidden" for="email">Email</label>
                    <div class="input-group mb-3 has-validation">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Email" autofocus />
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="visually-hidden" for="password">Password</label>
                    <div class="input-group mb-3 has-validation">
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password" />
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" value="1"
                                    id="remember" />
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('layouts.components.scripts')
</body>

</html>
```

- Halaman login **tidak** memakai `@extends('layouts.app')`, karena tidak membutuhkan sidebar dan navbar. File `head` dan `scripts` tetap dipakai ulang dari Pertemuan 4.
- `name="remember"` pada checkbox **Ingat saya** dibaca oleh `$request->boolean('remember')`.

#### Route

Ganti isi `routes/web.php` menjadi seperti berikut. Semua route lama (periode, program studi, praktikum, dan laporan) dipindahkan ke dalam grup `auth`:

```php
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Harus login
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/tables', 'tables')->name('tables');
    Route::view('/edit', 'edit')->name('edit');
    Route::view('/datatables', 'datatables')->name('datatables');

    Route::resource('periode', PeriodeController::class)->except('show');
    Route::get('/laporan/periode', [PeriodeController::class, 'cetak'])->name('laporan.periode');

    Route::resource('prodi', ProdiController::class)->except('show');
    Route::get('/laporan/prodi', [ProdiController::class, 'cetak'])->name('laporan.prodi');

    Route::resource('praktikum', PraktikumController::class)->except('show');

    // Harus login DAN level admin
    Route::middleware('level:admin')->group(function () {
        Route::resource('user', UserController::class)->except('show');
    });
});
```

| Middleware | Arti |
|---|---|
| `guest` | Hanya untuk yang **belum** login. Jika sudah login lalu membuka `/login`, otomatis diarahkan ke dashboard |
| `auth` | Hanya untuk yang **sudah** login. Jika belum, otomatis diarahkan ke route bernama `login` |
| `level:admin` | Middleware buatan sendiri (langkah 6), hanya untuk level admin |

Hal penting:

- Route login **harus** bernama `login`, karena middleware `auth` mencari nama tersebut.
- Route dashboard diberi nama `dashboard`, karena dipakai oleh `redirect()->intended(route('dashboard'))` dan oleh middleware `guest`.
- **Tidak ada route `/register`.** Itulah cara paling sederhana agar tidak ada pendaftaran: halamannya memang tidak dibuat. Jika dibuka, hasilnya 404.
- Logout memakai `POST`, bukan link biasa, agar tidak bisa dipicu dari tautan di situs lain.

Coba buka `http://localhost:8000`. Anda akan diarahkan ke `/login`. Masuk dengan `admin@simtikum.test` / `admin123`.

### 5. Nama pengguna dan tombol keluar

Buka `resources/views/layouts/components/nav.blade.php`. Cari teks **Alexander Pierce** dan ganti dengan nama pengguna yang sedang login:

```blade
<span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
```

```blade
{{ auth()->user()->name }} - {{ ucfirst(auth()->user()->level) }}
```

Ganti tombol **Sign out** dengan form logout:

```blade
<form action="{{ route('logout') }}" method="POST" class="float-end">
    @csrf
    <button type="submit" class="btn btn-outline-danger">Keluar</button>
</form>
```

`auth()->user()` berisi data pengguna yang sedang login, sehingga kolom apa pun dari tabel `users` bisa ditampilkan.

### 6. Middleware pembatas level

```bash
php artisan make:middleware CekLevel
```

Isi `app/Http/Middleware/CekLevel.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekLevel
{
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        if (! in_array($request->user()->level, $levels)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
```

- `string ...$levels` menampung level yang ditulis di route. `level:admin` berarti `$levels = ['admin']`, dan `level:admin,pengajar` berarti `$levels = ['admin', 'pengajar']`.
- Jika level pengguna tidak ada di daftar, `abort(403)` menampilkan halaman **403 Forbidden**.
- Jika diizinkan, `$next($request)` meneruskan request ke controller.

Daftarkan nama pendek (alias) `level` di `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'level' => \App\Http\Middleware\CekLevel::class,
    ]);
})
```

Sekarang `Route::middleware('level:admin')` di `routes/web.php` sudah bisa dipakai.

### 7. Halaman kelola pengguna

```bash
php artisan make:controller UserController --resource --model=User
```

#### Controller

Isi `app/Http/Controllers/UserController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private array $pesan = [
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah dipakai pengguna lain.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sama.',
        'level.required' => 'Level wajib dipilih.',
        'level.in' => 'Level tidak valid.',
    ];

    public function index()
    {
        $semua_user = User::orderBy('name')->get();

        return view('user.index', compact('semua_user'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'level' => ['required', Rule::in(['admin', 'pengajar'])],
        ], $this->pesan);

        User::create($data);

        return redirect()->route('user.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'level' => ['required', Rule::in(['admin', 'pengajar'])],
        ], $this->pesan);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', 'Pengguna berhasil diubah.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
```

Perbedaan dengan CRUD periode di Pertemuan 5:

- **Pesan validasi** ditulis sekali di `$pesan`, lalu dipakai oleh `store` dan `update`.
- **`confirmed`** mewajibkan input `password_confirmation` bernilai sama dengan `password`.
- **`Rule::in(['admin', 'pengajar'])`** memastikan level hanya bisa salah satu dari dua nilai itu, walaupun seseorang mengubah isi dropdown lewat *inspect element*.
- **Password saat ubah data** bersifat `nullable`. Jika dikosongkan, `unset($data['password'])` membuat password lama tetap dipakai.
- **Tidak bisa menghapus diri sendiri.** `$user->is($request->user())` memeriksa apakah data yang akan dihapus adalah akun yang sedang login. Tanpa ini, admin bisa tidak sengaja mengunci dirinya sendiri.
- Password **tidak perlu** di-hash di controller, karena sudah ditangani cast `hashed` di model.

#### View

Buat folder `resources/views/user`. Seperti halaman Program Studi di Pertemuan 7, isian form dipisah ke satu file `form.blade.php` lalu dipanggil dengan `@include`.

`resources/views/user/form.blade.php`:

```blade
<div class="mb-3">
    <label for="name" class="form-label">Nama</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $user->name ?? '') }}" />
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email', $user->email ?? '') }}" />
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="level" class="form-label">Level</label>
    <select id="level" name="level" class="form-select @error('level') is-invalid @enderror">
        <option value="pengajar" @selected(old('level', $user->level ?? 'pengajar') === 'pengajar')>Pengajar</option>
        <option value="admin" @selected(old('level', $user->level ?? '') === 'admin')>Admin</option>
    </select>
    @error('level')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" autocomplete="new-password" />
        @isset($user)
            <div class="form-text">Kosongkan jika tidak ingin mengganti password.</div>
        @endisset
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Ulangi Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
            autocomplete="new-password" />
    </div>
</div>
```

- `$user->name ?? ''`: di halaman tambah variabel `$user` belum ada, jadi dipakai string kosong. Di halaman ubah, isinya diambil dari database.
- `@selected(...)` memilih opsi level yang sesuai.
- `@isset($user)` menampilkan petunjuk "Kosongkan jika tidak ingin mengganti password" hanya di halaman ubah.
- Input password **tidak** diberi `value`, agar password tidak pernah ditampilkan kembali.

`resources/views/user/create.blade.php`:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
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
                            <h3 class="card-title">Tambah Pengguna</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        @include('user.form')

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
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

`resources/views/user/edit.blade.php`. Perbedaannya hanya breadcrumb, judul, `action`, `@method('PUT')`, dan teks tombol:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Pengguna</a></li>
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
                            <h3 class="card-title">Ubah Pengguna</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-left-circle me-1" aria-hidden="true"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('user.form')

                        <div class="d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">
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

`resources/views/user/index.blade.php`:

```blade
@extends('layouts.app')

@section('breadcrumb')
    <div class="row">
        <div class="col-sm-6">
            <h1 class="mb-0 fs-3">Pengguna</h1>
        </div>
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <h3 class="card-title">Pengguna</h3>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
                                    Tambah Pengguna
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
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th style="width: 40px">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($semua_user as $user)
                                <tr class="align-middle">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->isAdmin())
                                            <span class="badge text-bg-primary">Admin</span>
                                        @else
                                            <span class="badge text-bg-secondary">Pengajar</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('user.edit', $user) }}" class="btn btn-outline-secondary"
                                                aria-label="Ubah {{ $user->name }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </a>
                                            @unless ($user->is(auth()->user()))
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#modal-hapus-{{ $user->id }}"
                                                    aria-label="Hapus {{ $user->name }}">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                </button>
                                            @endunless
                                        </div>
                                        @unless ($user->is(auth()->user()))
                                            @include('user.modal-hapus')
                                        @endunless
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary">Belum ada pengguna.</td>
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

- `@section('breadcrumb')` mengisi judul dan breadcrumb di bagian atas halaman, sama seperti halaman periode.
- `session('error')` menampilkan pesan merah saat admin mencoba menghapus dirinya sendiri.
- `@unless ($user->is(auth()->user()))` menyembunyikan tombol hapus pada baris akun yang sedang login.

`resources/views/user/modal-hapus.blade.php`:

```blade
<div class="modal fade" id="modal-hapus-{{ $user->id }}" tabindex="-1"
    aria-labelledby="modal-hapus-label-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-start">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-hapus-label-{{ $user->id }}">Hapus pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?
                    Pengguna ini tidak akan bisa masuk lagi.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('user.destroy', $user) }}" method="POST">
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

### 8. Menu Pengguna di sidebar (khusus admin)

Buka `resources/views/layouts/components/sidebar.blade.php`. Tambahkan tepat **setelah** menu **Laporan Prodi** (sebelum `</ul>` penutup menu):

```blade
@if (auth()->user()->isAdmin())
    <li class="nav-header">PENGATURAN</li>
    <li class="nav-item">
        <a href="{{ route('user.index') }}"
            class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-people-fill"></i>
            <p>Pengguna</p>
        </a>
    </li>
@endif
```

Menu ini hanya **disembunyikan** dari pengajar. Yang benar-benar **melindungi** halaman tetap middleware `level:admin`. Tanpa middleware, pengajar masih bisa membuka `/user` dengan mengetik alamatnya langsung.

### Uji coba

1. Dalam keadaan belum login, buka `/`, `/periode`, `/prodi`, `/praktikum`, `/laporan/periode`, dan `/user`. Semuanya diarahkan ke `/login`.
2. Buka `/register`. Hasilnya **404 Not Found**.
3. Login dengan password salah. Muncul pesan **Email atau password salah.** dan email tetap terisi.
4. Login sebagai `budi@simtikum.test` / `budi123` (pengajar):
   - menu **Pengguna** tidak tampil di sidebar;
   - `/periode` bisa dibuka;
   - ketik `/user` di address bar, hasilnya **403 Forbidden**.
5. Keluar, lalu login sebagai `admin@simtikum.test` / `admin123`:
   - menu **Pengguna** tampil;
   - tambah pengguna baru, coba juga dengan email yang sudah ada dan konfirmasi password yang berbeda;
   - ubah pengguna tanpa mengisi password, lalu login dengan akun itu memakai password lamanya;
   - pada baris akun admin sendiri, tombol hapus tidak tampil.
6. Saat sudah login, buka `/login`. Otomatis diarahkan ke dashboard.

### Jika terjadi error

| Gejala | Penyebab dan solusi |
|---|---|
| `Route [login] not defined` | Route login belum diberi `->name('login')` |
| Level tidak tersimpan, selalu `pengajar` | `'level'` belum ditambahkan ke `#[Fillable([...])]` di model User |
| `Target class [level] does not exist` | Alias middleware belum didaftarkan di `bootstrap/app.php` |
| Login selalu gagal walaupun password benar | Password di database tidak dalam bentuk hash (misalnya diisi manual lewat phpMyAdmin). Buat ulang akun lewat seeder atau halaman Pengguna |
| `419 Page Expired` saat login/keluar | Lupa `@csrf` di form |
| Setelah login kembali ke halaman login terus | Route dashboard tidak berada di dalam grup `auth`, atau `SESSION_DRIVER` di `.env` bermasalah; coba `php artisan config:clear` |

## Latihan

1. Batasi agar **tambah, ubah, dan hapus periode** hanya bisa dilakukan admin, sedangkan pengajar hanya bisa melihat daftar periode. Petunjuk: pisahkan route `index` dari resource, lalu sembunyikan tombol dengan `@if (auth()->user()->isAdmin())`.
2. Buat halaman **Ganti Password** yang bisa dipakai semua pengguna untuk mengganti password-nya sendiri, dengan input password lama, password baru, dan konfirmasi.
3. Tambahkan kolom pencarian nama/email di halaman Pengguna.

## Rangkuman

- Kolom baru pada tabel yang sudah ada ditambahkan dengan migration baru (`Schema::table`) agar data lama tidak hilang.
- `Auth::attempt()` mencocokkan email dan password; `Auth::logout()` mengeluarkan pengguna.
- Tidak ada pendaftaran karena route `/register` memang tidak dibuat; akun dibuat oleh admin.
- Middleware `auth` dan `guest` bawaan Laravel membedakan pengguna yang sudah dan belum login.
- Middleware buatan sendiri (`level:admin`) membatasi halaman berdasarkan kolom `level`.
- Menyembunyikan menu hanya soal tampilan; perlindungan sebenarnya ada di middleware.

## Referensi

- [Authentication: Manually Authenticating Users](https://laravel.com/docs/authentication#authenticating-users)
- [Middleware](https://laravel.com/docs/middleware)
- [Migrations: Updating Tables](https://laravel.com/docs/migrations#updating-tables)
- [Validation: `confirmed`, `Rule::in`](https://laravel.com/docs/validation#available-validation-rules)
