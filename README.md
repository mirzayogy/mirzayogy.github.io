# mirzayogy.github.io

Blog pribadi dan materi perkuliahan, dibangun dengan [Jigsaw](https://jigsaw.tighten.com) (static site generator berbasis PHP/Blade) dan di-host di GitHub Pages.

Situs: **https://mirzayogy.github.io**

---

## Daftar isi

- [mirzayogy.github.io](#mirzayogygithubio)
  - [Daftar isi](#daftar-isi)
  - [Kebutuhan](#kebutuhan)
  - [Menjalankan di komputer sendiri](#menjalankan-di-komputer-sendiri)
    - [Persiapan (sekali saja)](#persiapan-sekali-saja)
    - [Preview dengan auto-refresh (disarankan)](#preview-dengan-auto-refresh-disarankan)
    - [Alternatif tanpa Node.js](#alternatif-tanpa-nodejs)
    - [Cara kerjanya](#cara-kerjanya)
    - [Perintah lain](#perintah-lain)
  - [Struktur folder](#struktur-folder)
  - [Menulis post baru](#menulis-post-baru)
    - [Frontmatter yang tersedia](#frontmatter-yang-tersedia)
    - [Gambar dan file unduhan](#gambar-dan-file-unduhan)
    - [Warna teks](#warna-teks)
  - [Draft: menyembunyikan post](#draft-menyembunyikan-post)
  - [Post pilihan (featured)](#post-pilihan-featured)
  - [Tag dan tag rekomendasi](#tag-dan-tag-rekomendasi)
  - [Seri tulisan (materi perkuliahan)](#seri-tulisan-materi-perkuliahan)
    - [Seri `laravel-simtikum`](#seri-laravel-simtikum)
  - [Halaman statis](#halaman-statis)
  - [Pengaturan situs (`config.php`)](#pengaturan-situs-configphp)
  - [Publikasi dengan GitHub Desktop](#publikasi-dengan-github-desktop)
  - [Pemecahan masalah](#pemecahan-masalah)

---

## Kebutuhan

- PHP **8.4** atau lebih baru (sesuai `composer.lock`)
- Composer
- Node.js + npm (opsional, untuk auto-refresh saat preview lokal)
- GitHub Desktop (atau git) untuk publikasi

CSS ditulis langsung di `source/assets/css/main.css` (tanpa proses build CSS).

## Menjalankan di komputer sendiri

### Persiapan (sekali saja)

```bash
cd ~/LaravelProjects/mirzayogy.github.io
composer install
npm install
```

### Preview dengan auto-refresh (disarankan)

Jalankan **dua terminal** di folder proyek:

```bash
# Terminal 1: server situs
vendor/bin/jigsaw serve

# Terminal 2: pemantau perubahan + auto-refresh
npm run dev
```

Buka situs di **http://localhost:8000**. Selama kedua terminal berjalan:

1. Setiap kali file disimpan, `npm run dev` otomatis menjalankan `jigsaw build` ke folder `build_local/`.
2. `jigsaw serve` menyajikan isi `build_local/` yang sudah diperbarui.
3. Browser di :8000 **me-refresh sendiri** (tidak perlu tekan F5).
4. Setelah selesai, hentikan keduanya dengan **Ctrl + C**.

> **Jangan buka http://localhost:5173.** Alamat itu milik server Vite yang hanya bertugas memantau file dan mengirim sinyal refresh. Jika dibuka langsung, yang tampil hanya halaman keterangan "Jigsaw Vite", bukan situs Anda.

File yang dipantau: `config.php`, `config.production.php`, `bootstrap.php`, `listeners/**/*.php`, serta semua `.md`, `.php`, `.html`, `.css`, dan `.js` di dalam `source/`.

> **Kenapa `jigsaw serve` saja tidak cukup?** `jigsaw serve` hanya mem-build **sekali** saat dijalankan, lalu menyajikan hasil build itu. Perubahan file setelahnya tidak akan terlihat sampai Anda build ulang. Itulah tugas `npm run dev`.

### Alternatif tanpa Node.js

```bash
vendor/bin/jigsaw serve      # terminal 1: server di http://localhost:8000
vendor/bin/jigsaw build      # terminal 2: jalankan setiap selesai mengubah file
```

Setelah `build` selesai, refresh browser secara manual. Cara ini aman dan hasilnya sama, hanya tidak otomatis.

### Cara kerjanya

| Bagian | Fungsi |
|---|---|
| `vite.config.js` | Menjalankan plugin `@tighten/jigsaw-vite-plugin` di port 5173: memantau file, menjalankan `jigsaw build`, dan mengirim sinyal reload ke halaman yang dibuka di :8000. Daftar file yang dipantau ada di `refresh.files`. |
| `@viteRefresh()` di `source/_layouts/main.blade.php` | Menyisipkan script reload ke halaman **hanya saat `npm run dev` berjalan** (ditandai file `source/hot`). Di build biasa dan produksi tidak menghasilkan apa-apa. |
| `main.css?v={{ time() }}` di `main.blade.php` | Angka `v` berubah setiap build sehingga browser selalu mengambil CSS terbaru, tidak memakai versi lama dari cache. Berlaku juga di situs online. |
| `source/hot` | File sementara berisi alamat server Vite, dibuat saat `npm run dev` dan diabaikan git (`.gitignore`). |

Menambah jenis file yang dipantau (misalnya gambar `.png`): tambahkan polanya di `refresh.files` pada `vite.config.js`, lalu jalankan ulang `npm run dev`.

### Perintah lain

| Perintah | Fungsi |
|---|---|
| `npm run dev` | Pemantau perubahan: auto-build + auto-refresh. Dipakai **bersama** `jigsaw serve`; situs tetap dibuka di http://localhost:8000 |
| `vendor/bin/jigsaw build` | Build versi lokal ke `build_local/` (draft ikut tampil) |
| `vendor/bin/jigsaw build production` | Build versi produksi ke `build_production/` (draft disembunyikan), sama seperti di GitHub Actions |
| `vendor/bin/jigsaw serve` | Build lokal sekali lalu jalankan server di http://localhost:8000 (tanpa memantau perubahan jika dipakai sendirian) |

## Struktur folder

```
.
├── config.php                 # pengaturan utama + helper
├── config.production.php      # pengaturan khusus produksi (baseUrl, filter draft)
├── bootstrap.php              # event build (membuat sitemap.xml)
├── listeners/                 # kode yang dijalankan setelah build
├── vite.config.js             # auto-build + auto-refresh saat `npm run dev`
├── package.json               # dependensi Node.js (Vite + plugin Jigsaw)
├── .github/workflows/deploy.yml   # build & deploy otomatis ke GitHub Pages
└── source/
    ├── _layouts/
    │   ├── main.blade.php     # kerangka HTML: head, header, sidebar, footer
    │   ├── post.blade.php     # tampilan satu post
    │   └── page.blade.php     # halaman statis Markdown (mis. Tentang)
    ├── _partials/
    │   ├── header.blade.php   # menu navigasi
    │   ├── footer.blade.php
    │   ├── sidebar.blade.php  # tag rekomendasi, tulisan pilihan, tentang
    │   ├── post-card.blade.php    # kartu post di beranda/daftar blog
    │   └── series-nav.blade.php   # kotak navigasi seri (pertemuan 1..n)
    ├── _posts/                # semua post (.md)
    ├── assets/
    │   ├── css/main.css       # gaya situs (mode gelap otomatis)
    │   ├── image/             # gambar untuk post
    │   ├── docs/              # dataset / dokumen unduhan
    │   └── zip/               # file zip / SQL unduhan
    ├── index.blade.php        # beranda: 5 post terbaru
    ├── blog.blade.php         # daftar post + paginasi (5 per halaman)
    ├── tags.blade.php         # semua tag dan post-nya
    ├── tentang.md             # halaman Tentang
    └── 404.blade.php
```

Folder/file berawalan `_` (misalnya `_layouts`, `_posts`) tidak menjadi halaman sendiri.

## Menulis post baru

1. Buat file di `source/_posts/` dengan pola nama `YYYY-MM-DD-judul-singkat.md`, misalnya `2026-10-20-belajar-eloquent.md`.
2. Isi frontmatter di bagian atas, lalu tulis isi dengan Markdown:

~~~markdown
---
title: "Belajar Eloquent"
description: "Ringkasan satu kalimat yang tampil di daftar post."
date: 2026-10-20
tags: [laravel, eloquent]
---

Isi tulisan di sini. **Tebal**, *miring*, [tautan](https://laravel.com).

## Subjudul

```php
$user = User::find(1);
```
~~~

URL post mengikuti nama file: `https://mirzayogy.github.io/blog/2026-10-20-belajar-eloquent`.

### Frontmatter yang tersedia

| Kunci | Wajib | Keterangan |
|---|---|---|
| `title` | ya | Judul post. Pakai tanda kutip jika ada titik dua (`:`). |
| `date` | ya | Format `YYYY-MM-DD`. Post diurutkan dari tanggal terbaru. |
| `description` | tidak | Ringkasan di daftar post dan sidebar. Jika kosong, diambil otomatis dari awal isi. |
| `tags` | tidak | Daftar tag, mis. `[laravel, php]`. |
| `draft` | tidak | `true` = tidak dipublikasikan. Lihat [Draft](#draft-menyembunyikan-post). |
| `featured` | tidak | `true` = tampil di "Tulisan pilihan" dan diberi badge ★. |
| `series` | tidak | Kode seri, mis. `laravel-simtikum`. |
| `pertemuan` | tidak | Nomor urut di dalam seri (1, 2, 3, ...). |

### Gambar dan file unduhan

Semua file yang ingin tampil atau diunduh dari situs **harus berada di dalam `source/`**. Folder di luar `source/` (misalnya `assets/` di akar proyek) tidak ikut di-build.

| Jenis | Simpan di | Alamat di situs |
|---|---|---|
| Gambar | `source/assets/image/` | `/assets/image/nama.png` |
| Dataset / dokumen | `source/assets/docs/` | `/assets/docs/nama.csv` |
| File zip / SQL | `source/assets/zip/` | `/assets/zip/nama.zip` |

Di Markdown, selalu pakai path yang diawali `/` (bukan `../../`):

```markdown
![Alur MVC pada Laravel](/assets/image/mvc.png)

[Unduh starter project](/assets/zip/praktikum-web-2-starter.zip)
```

Teks di dalam `![...]` adalah teks alternatif (tampil jika gambar gagal dimuat dan dibaca oleh pembaca layar). Ukuran file per file di GitHub maksimal 100 MB; sebaiknya kompres foto besar sebelum dimasukkan.

### Warna teks

| Penulisan di Markdown | Hasil |
|---|---|
| `` `kode` `` | Kode inline berwarna **hijau** (warna tema) |
| `<code class="alt">kode</code>` | Kode inline berwarna **oranye bata** |
| `<span class="alt">teks</span>` | Teks biasa berwarna oranye bata |
| `<mark>teks</mark>` | Teks disorot dengan latar oranye muda |

Contoh:

```markdown
Jalankan `php artisan serve`, lalu <mark>jangan lupa</mark> mengatur file <code class="alt">.env</code>.
```

Warna diatur di bagian atas `source/assets/css/main.css`: `--accent` / `--accent-soft` (hijau) dan `--accent-2` / `--accent-2-soft` (oranye). Keduanya punya versi mode gelap di blok `@media (prefers-color-scheme: dark)`.

## Draft: menyembunyikan post

Tambahkan `draft: true` di frontmatter:

```yaml
---
title: "Pertemuan 5: Eloquent ORM dan Relasi"
date: 2026-11-08
draft: true
---
```

| Saat | Post draft |
|---|---|
| Preview lokal (`jigsaw serve` / `jigsaw build`) | Tampil, dengan label kuning **DRAFT** |
| Situs online (`jigsaw build production`, GitHub Actions) | Tidak dibuat sama sekali: tidak ada di beranda, blog, sidebar, tag, navigasi seri, dan URL-nya tidak ada |

Untuk menerbitkan, hapus baris `draft: true` (atau ubah menjadi `draft: false`), lalu commit dan push.

> **Perhatian:** repo ini publik. Draft hanya tersembunyi dari **situs**, file `.md`-nya tetap bisa dibaca di GitHub. Jika belum boleh dilihat sama sekali, jangan commit file tersebut dulu (hilangkan centangnya di GitHub Desktop).

Pengaturannya ada di `config.production.php` (bagian `filter`).

## Post pilihan (featured)

Tambahkan `featured: true` di frontmatter. Post akan:

- muncul di widget **★ Tulisan pilihan** di sidebar (maksimal 4 post, yang terbaru dulu);
- diberi badge **★ Pilihan** di daftar post.

Post yang sedang dibuka tidak ditampilkan di daftar pilihannya sendiri.

## Tag dan tag rekomendasi

- Tag ditulis di frontmatter: `tags: [laravel, perkuliahan]`.
- Semua tag dan post-nya terkumpul di halaman **/tags**. Klik tag akan langsung melompat ke bagiannya (`/tags#laravel`).
- Tag di halaman post dapat diklik.

Tag yang muncul di widget **# Tag rekomendasi** (sidebar) diatur di `config.php`:

```php
'recommendedTags' => ['laravel', 'perkuliahan', 'jigsaw', 'php', 'catatan'],
```

- Urutan tampil mengikuti daftar ini, lengkap dengan jumlah post-nya.
- Tag yang belum dipakai post mana pun otomatis disembunyikan.
- Kosongkan (`[]`) untuk menampilkan 8 tag yang paling sering dipakai.

## Seri tulisan (materi perkuliahan)

Post yang memiliki `series` yang sama dikelompokkan menjadi satu seri. Setiap post seri menampilkan kotak "Seri: … · Pertemuan X dari N" berisi daftar semua pertemuan, di atas dan di bawah artikel.

```yaml
---
title: "Pertemuan 2: Routing dan Controller"
date: 2026-10-18
series: laravel-simtikum
pertemuan: 2
tags: [laravel, perkuliahan, routing]
---
```

Judul seri dan nama mata kuliah diatur di `config.php`:

```php
'seriesInfo' => [
    'laravel-simtikum' => [
        'title' => 'Laravel: Membangun SIMTIKUM',
        'mataKuliah' => 'PBO 2',
    ],
],
```

Untuk membuat seri baru, tambahkan entri baru di `seriesInfo` (mis. `'basis-data' => [...]`) dan pakai kode yang sama di frontmatter `series`.

Catatan:

- Urutan di kotak seri mengikuti `pertemuan`, bukan tanggal.
- Jumlah "dari N" hanya menghitung pertemuan yang **sudah terbit** (draft tidak dihitung di situs online).

### Seri `laravel-simtikum`

16 post di `source/_posts/*-laravel-simtikum-{1..16}.md`, dengan studi kasus SIMTIKUM (Sistem Informasi Praktikum):

| # | Topik | # | Topik |
|---|---|---|---|
| 1 | Pengenalan Laravel | 9 | Autentikasi |
| 2 | Routing dan controller | 10 | Otorisasi: role, middleware, Gate, Policy |
| 3 | Blade templating dan layout | 11 | Penugasan pengajar dan jadwal |
| 4 | Migration dan seeder | 12 | Presensi pengajar dan mahasiswa |
| 5 | Eloquent ORM dan relasi | 13 | Upload file: laporan kegiatan dengan foto |
| 6 | CRUD periode dan validasi | 14 | Laporan dan ekspor PDF/Excel |
| 7 | CRUD praktikum, pencarian, paginasi | 15 | Testing dan deployment |
| 8 | **UTS** | 16 | **UAS: presentasi proyek** |

Setiap post materi berisi: capaian pembelajaran, persiapan, materi (dengan contoh kode), praktik SIMTIKUM, latihan/tugas, rangkuman, dan referensi. Post UTS/UAS berisi cakupan, ketentuan, soal, rubrik penilaian, dan pengumpulan.

Bagian yang masih perlu diisi ditandai `…`. Komentar `<!-- TEMPLATE: ... -->` di awal setiap post tidak tampil di situs dan boleh dihapus setelah post selesai.

Alur yang disarankan per pertemuan:

1. Lengkapi isi post (ganti semua `…`).
2. Cek di lokal dengan `vendor/bin/jigsaw serve`.
3. Ubah `draft: true` menjadi `draft: false` (atau hapus barisnya).
4. Commit dan push.

## Halaman statis

Buat file `.md` langsung di `source/`, misalnya `source/kontak.md`:

```markdown
---
extends: _layouts.page
section: content
title: Kontak
---

# Kontak

Isi halaman di sini.
```

Halaman akan tersedia di `/kontak`. Tambahkan tautannya ke menu di `source/_partials/header.blade.php`:

```blade
<a href="{{ $page->baseUrl }}/kontak" @class(['active' => $page->isActive('/kontak')])>Kontak</a>
```

Untuk halaman tanpa sidebar, tambahkan `sidebar: false` di frontmatter (seperti `404.blade.php`).

## Pengaturan situs (`config.php`)

| Kunci | Fungsi |
|---|---|
| `siteName` | Nama situs di header dan judul tab |
| `siteDescription` | Deskripsi situs di beranda, meta SEO, dan sidebar |
| `siteAuthor` | Nama penulis di footer dan meta |
| `collections.posts.author` | Nama penulis default setiap post |
| `recommendedTags` | Tag di widget sidebar |
| `seriesInfo` | Judul seri dan nama mata kuliah |

Helper yang bisa dipakai di template Blade:

| Helper | Hasil |
|---|---|
| `$page->tanggal()` | Tanggal berformat Indonesia, mis. "11 Oktober 2026" |
| `$page->readingTime()` | Perkiraan menit baca |
| `$page->excerpt(180)` | Ringkasan otomatis dari isi post |
| `$page->tagSlug('Machine Learning')` | `machine-learning` |
| `$page->isActive('/blog')` | `true` jika halaman sedang berada di `/blog...` |

`config.production.php` hanya berisi pengaturan yang berbeda saat produksi: `baseUrl` (`https://mirzayogy.github.io`) dan filter draft.

## Publikasi dengan GitHub Desktop

Situs di-build dan di-deploy otomatis oleh GitHub Actions setiap ada push ke branch `master` (atau `main`).

1. Buka GitHub Desktop dan pilih repo **mirzayogy.github.io**.
2. Periksa daftar file di tab **Changes**. Hilangkan centang file yang belum ingin dipublikasikan.
3. Isi **Summary** (mis. `Terbitkan pertemuan 3`) lalu klik **Commit to master**.
4. Klik **Push origin**.
5. Pantau di tab **Actions** pada repo GitHub. Setelah workflow **Deploy ke GitHub Pages** bercentang hijau (sekitar 1–2 menit), buka https://mirzayogy.github.io dan refresh dengan **Cmd + Shift + R**.

Pengaturan yang harus tetap seperti ini di GitHub: **Settings → Pages → Build and deployment → Source: GitHub Actions**.

Folder yang **tidak** ikut di-commit (sudah di `.gitignore`): `vendor/`, `node_modules/`, `build_local/`, `build_production/`.

## Pemecahan masalah

| Masalah | Penyebab dan solusi |
|---|---|
| Situs menampilkan isi README | Source GitHub Pages masih "Deploy from a branch". Ubah ke **GitHub Actions**. |
| CSS tidak muncul / link rusak di situs | `baseUrl` di `config.production.php` salah. Harus `https://mirzayogy.github.io`. |
| Workflow tidak berjalan setelah push | Pastikan branch yang di-push adalah `master` atau `main` (lihat `branches` di `deploy.yml`). |
| Workflow gagal di langkah "Install dependensi" | Versi PHP di `deploy.yml` harus sesuai `composer.lock` (saat ini `8.4`). |
| Post tidak muncul di situs | Cek apakah masih `draft: true`, dan apakah format `date` benar (`YYYY-MM-DD`). |
| Gambar tidak tampil | File harus di `source/assets/...` dan dipanggil dengan path diawali `/`, mis. `/assets/image/mvc.png`. Build ulang setelah menambah gambar. Nama file peka huruf besar/kecil di situs online (`MVC.png` ≠ `mvc.png`). |
| Error YAML saat build | Judul/deskripsi yang mengandung `:` harus diberi tanda kutip: `title: "Pertemuan 1: Pengenalan"`. |
| Perubahan tidak terlihat di situs online | Tunggu workflow selesai, lalu refresh tanpa cache (**Cmd + Shift + R**). |
| Perubahan tidak terlihat di lokal | Pastikan `npm run dev` berjalan di samping `jigsaw serve`. Tanpa `npm run dev`, jalankan `vendor/bin/jigsaw build` dulu lalu refresh. |
| Yang tampil halaman "Jigsaw Vite" | Anda membuka http://localhost:5173. Buka **http://localhost:8000** (server `jigsaw serve`). |
| Browser tidak refresh otomatis | Cek terminal `npm run dev`: jika build error (salah ketik Blade atau YAML frontmatter), halaman tidak diperbarui sampai error diperbaiki. |
| Masih tampil versi lama setelah refresh | Refresh tanpa cache (**Cmd + Shift + R**). |
| `npm run dev` error "vendor/bin/jigsaw not found" | Jalankan `composer install` dulu. |
| `npm run dev` error "vite: command not found" | Jalankan `npm install` dulu. |
| Port 8000 sudah dipakai | Jalankan `vendor/bin/jigsaw serve --port=8080` lalu buka http://localhost:8080. |
| Console browser berisi error koneksi ke `localhost:5173` padahal `npm run dev` tidak berjalan | File `source/hot` tertinggal karena Vite berhenti tidak normal. Hapus file `source/hot`, lalu build ulang. |
