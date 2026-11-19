---
title: "Laravel 6: Laporan PDF dengan spipu/html2pdf"
description: "Membuat laporan data periode dalam format PDF lengkap dengan kop, nomor halaman, tanda tangan, dan QR code menggunakan spipu/html2pdf."
date: 2026-10-20
series: laravel-simtikum
pertemuan: 6
draft: false
comments: true
tags: [laravel, perkuliahan, laporan, pdf]
---

## Capaian pembelajaran

Setelah pertemuan ini, mahasiswa mampu:

- Menjelaskan pilihan pustaka laporan PDF di Laravel beserta kelebihan dan kekurangannya
- Memasang pustaka `spipu/html2pdf` dengan Composer
- Membuat view laporan dengan kop, nomor halaman, dan tanda tangan
- Menampilkan dan mengunduh laporan PDF dari controller

## Persiapan

- Proyek dari Pertemuan 5 sudah memiliki CRUD periode.
- Ekstensi PHP **gd** dan **mbstring** aktif. Periksa dengan:

```bash
php -m
```

Jika `gd` tidak ada di daftar (umum terjadi di XAMPP), buka `php.ini`, cari baris `;extension=gd`, hapus tanda `;` di depannya, simpan, lalu restart Apache dan terminal.

## Materi

### Mengapa spipu/html2pdf?

Ada beberapa cara membuat laporan PDF di Laravel. Semuanya mengubah sesuatu (biasanya HTML) menjadi PDF, tetapi cara kerjanya berbeda:

| Pustaka | Cara kerja | Kebutuhan tambahan |
|---|---|---|
| **spipu/html2pdf** | HTML + tag khusus → TCPDF, murni PHP | Tidak ada (cukup ekstensi `gd` & `mbstring`) |
| barryvdh/laravel-dompdf | HTML + CSS → Dompdf, murni PHP | Tidak ada |
| mPDF | HTML + CSS, murni PHP | Tidak ada, ukuran paket lebih besar |
| barryvdh/laravel-snappy | HTML → program `wkhtmltopdf` | Harus memasang program `wkhtmltopdf` di server; `wkhtmltopdf` sudah tidak dikembangkan lagi sejak 2023 |
| spatie/browsershot | HTML → Google Chrome (Puppeteer) | Harus ada Node.js dan Chrome di server |

Kelebihan **spipu/html2pdf** dibanding pilihan lain:

1. **Murni PHP, tanpa program tambahan.** Berbeda dengan Snappy dan Browsershot, tidak perlu memasang `wkhtmltopdf`, Node.js, atau Chrome. Karena itu bisa langsung berjalan di XAMPP/Laragon maupun shared hosting yang tidak mengizinkan program eksternal.
2. **Kop dan footer setiap halaman cukup dengan tag.** Tag `<page_header>` dan `<page_footer>` otomatis diulang di setiap halaman. Di Dompdf, hal yang sama harus diakali dengan CSS `position: fixed` atau script.
3. **Nomor halaman siap pakai.** Cukup tulis `[[page_cu]]` (halaman saat ini) dan `[[page_nb]]` (jumlah halaman), misalnya "Halaman 2 dari 3". Di Dompdf, nomor halaman saat ini bisa dibuat dengan CSS, tetapi **jumlah halaman total** harus ditambahkan lewat kode PHP setelah PDF dirender.
4. **QR code dan barcode bawaan.** Tag `<qrcode>` dan `<barcode>` langsung tersedia tanpa paket tambahan. Cocok untuk laporan yang perlu tautan verifikasi.
5. **Pengaturan kertas dalam milimeter.** Ukuran kertas, orientasi, dan margin diatur dalam satuan mm, sehingga mudah disesuaikan dengan format surat resmi.
6. **Berbasis TCPDF yang matang.** Mendukung UTF-8, gambar, dan tabel panjang yang otomatis berlanjut ke halaman berikutnya beserta judul kolomnya.

Kekurangannya juga perlu diketahui:

- Dukungan CSS **terbatas**. Bootstrap, `flex`, dan `grid` tidak bisa dipakai. Tata letak disusun dengan `<table>`.
- HTML harus **rapi dan tertutup** (setiap tag dibuka dan ditutup). HTML yang berantakan menyebabkan error.
- Untuk laporan yang sangat besar (ribuan baris), prosesnya lebih lambat dibanding pustaka berbasis browser.

Untuk laporan administrasi kampus yang umumnya berupa tabel, kop, dan tanda tangan, keterbatasan ini jarang menjadi masalah.

### Memasang spipu/html2pdf

Jalankan di folder proyek:

```bash
composer require spipu/html2pdf
```

![composer-html2pdf](/assets/image/laravel01-06/composer-html2pdf.png){.bingkai .kiri}

Perintah ini juga otomatis memasang `tecnickcom/tcpdf` yang menjadi mesin pembuat PDF-nya.

### Menambahkan route

Buka `routes/web.php`, tambahkan route laporan:

```php
Route::get('/laporan/periode', [PeriodeController::class, 'cetak'])->name('laporan.periode');
```

![route-laporan](/assets/image/laravel01-06/route-laporan.png){.bingkai .kiri}

Nama route sengaja diawali `laporan.` (bukan `periode.`) agar nanti bisa dikelompokkan di menu **Laporan** pada sidebar.

### Method cetak di controller

Buka `app/Http/Controllers/PeriodeController.php`. Tambahkan `use` di bagian atas:

```php
use Spipu\Html2Pdf\Html2Pdf;
```

![controller-01](/assets/image/laravel01-06/controller-01.png){.w-50 .bingkai .kiri}

Lalu tambahkan method `cetak`:

```php
public function cetak()
{
    $semua_periode = Periode::orderBy('singkatan')->get();

    // 1. Ubah view Blade menjadi teks HTML
    $html = view('periode.cetak', compact('semua_periode'))->render();

    // 2. Siapkan PDF: orientasi, ukuran kertas, bahasa, unicode, encoding, margin (kiri, atas, kanan, bawah) dalam mm
    $pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [15, 10, 15, 10]);
    $pdf->pdf->SetTitle('Laporan Data Periode');

    // 3. Tulis HTML ke PDF
    $pdf->writeHTML($html);

    // 4. Kirim ke browser
    return response($pdf->output('laporan-periode.pdf', 'S'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="laporan-periode.pdf"');
}
```

![controller-02](/assets/image/laravel01-06/controller-02.png){.bingkai .kiri}

Penjelasan parameter `new Html2Pdf(...)`:

| Parameter | Nilai | Arti |
|---|---|---|
| Orientasi | `'P'` / `'L'` | Portrait (tegak) / Landscape (mendatar) |
| Ukuran | `'A4'`, `'F4'`, `'LETTER'` | Ukuran kertas |
| Bahasa | `'en'` | Bahasa pesan error pustaka (tidak memengaruhi isi laporan) |
| Unicode | `true` | Agar huruf non-ASCII tampil benar |
| Encoding | `'UTF-8'` | Encoding teks |
| Margin | `[15, 10, 15, 10]` | Kiri, atas, kanan, bawah (mm) |

Pada langkah 4, `output(..., 'S')` mengembalikan PDF sebagai **teks** (string), lalu dibungkus `response()` Laravel. Header `inline` membuat PDF tampil di tab browser. Jika ingin langsung **terunduh**, ganti `inline` menjadi `attachment`.

### View laporan

Buat file `resources/views/periode/cetak.blade.php`.

> **Penting:** view ini **tidak** memakai `@extends('layouts.app')`, karena layout AdminLTE memakai Bootstrap yang tidak didukung html2pdf. Tata letak disusun dengan `<table>` dan CSS sederhana.

```blade
<style>
    .kop td { vertical-align: middle; }
    .judul-instansi { font-size: 16pt; font-weight: bold; }
    .data { width: 100%; border-collapse: collapse; }
    .data th { background-color: #0b6e69; color: #ffffff; padding: 2mm; border: 0.2mm solid #0b6e69; }
    .data td { padding: 2mm; border: 0.2mm solid #999999; }
    .tengah { text-align: center; }
    .aktif { color: #1a7f37; font-weight: bold; }
</style>

<page backtop="32mm" backbottom="14mm" backleft="0mm" backright="0mm">
    <page_header>
        <table class="kop" style="width: 100%; border-bottom: 0.6mm solid #000000;">
            <tr>
                <td style="width: 15%;">
                    <img src="{{ public_path('dist/assets/img/AdminLTELogo.png') }}" style="width: 20mm;">
                </td>
                <td style="width: 85%;">
                    <span class="judul-instansi">LABORATORIUM KOMPUTER</span><br>
                    Sistem Informasi Praktikum (SIMTIKUM)<br>
                    Jl. Contoh No. 1, Banjarbaru
                </td>
            </tr>
        </table>
    </page_header>

    <page_footer>
        <table style="width: 100%; font-size: 9pt; color: #666666;">
            <tr>
                <td style="width: 50%;">Dicetak: {{ now()->format('d-m-Y H:i') }}</td>
                <td style="width: 50%; text-align: right;">Halaman [[page_cu]] dari [[page_nb]]</td>
            </tr>
        </table>
    </page_footer>

    <h3 class="tengah">LAPORAN DATA PERIODE</h3>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 47%;">Periode</th>
                <th style="width: 20%;">Singkatan</th>
                <th style="width: 25%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semua_periode as $periode)
                <tr>
                    <td class="tengah">{{ $loop->iteration }}</td>
                    <td>{{ $periode->periode }}</td>
                    <td class="tengah">{{ $periode->singkatan }}</td>
                    <td class="tengah">
                        @if ($periode->status)
                            <span class="aktif">Aktif</span>
                        @else
                            Tidak aktif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <table style="width: 100%;">
        <tr>
            <td style="width: 60%;">
                <qrcode value="{{ route('periode.index') }}" ec="M" style="width: 25mm; border: none;"></qrcode>
            </td>
            <td style="width: 40%;">
                Banjarbaru, {{ now()->translatedFormat('d F Y') }}<br>
                Koordinator Laboratorium,<br><br><br><br>
                <b>Nama Koordinator</b>
            </td>
        </tr>
    </table>
</page>
```

![cetak](/assets/image/laravel01-06/cetak.png){.bingkai .kiri}

Bagian-bagian khusus html2pdf:

| Tag / kode | Fungsi |
|---|---|
| `<page>` | Satu “dokumen halaman”. `backtop` dan `backbottom` memberi ruang untuk kop dan footer agar tidak tertimpa isi |
| `<page_header>` | Kop yang **otomatis diulang** di setiap halaman |
| `<page_footer>` | Footer yang otomatis diulang di setiap halaman |
| `[[page_cu]]` / `[[page_nb]]` | Nomor halaman saat ini / jumlah seluruh halaman |
| `<thead>` | Judul kolom tabel otomatis diulang jika tabel berlanjut ke halaman berikutnya |
| `<qrcode value="...">` | QR code; `ec` adalah tingkat koreksi kesalahan (`L`, `M`, `Q`, `H`) |
| `public_path(...)` | Gambar memakai **path file di komputer**, bukan `asset()` (URL) |

Catatan tentang lebar kolom: beri `width` dalam persen pada setiap kolom judul (`<th>`) dan pastikan totalnya 100%. Tanpa itu, html2pdf menyesuaikan lebar kolom dengan isinya sehingga tabel tidak selebar halaman.

Tanggal pada tanda tangan memakai `translatedFormat`, sehingga nama bulan mengikuti bahasa aplikasi. Agar tampil dalam Bahasa Indonesia (misalnya **November**, **Agustus**), ubah `APP_LOCALE=id` di file `.env`.

### Tombol cetak di halaman index

Buka `resources/views/periode/index.blade.php`. Di bagian card header, tambahkan tombol **Cetak PDF** di samping tombol **Tambah Data**:

```blade
<div class="d-flex flex-wrap justify-content-md-end gap-2">
    <a href="{{ route('laporan.periode') }}" target="_blank" class="btn btn-sm btn-danger">
        <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i>
        Cetak PDF
    </a>
    <a href="{{ route('periode.create') }}" class="btn btn-sm btn-success">
        <i class="bi bi-plus-circle me-1" aria-hidden="true"></i>
        Tambah Data
    </a>
</div>
```

![tombol-cetak](/assets/image/laravel01-06/tombol-cetak.png){.bingkai .kiri}

`target="_blank"` membuka PDF di tab baru, sehingga halaman aplikasi tetap terbuka.

### Menu Laporan di sidebar

Buka `resources/views/layouts/components/sidebar.blade.php`, tambahkan menu **Laporan** di bawah menu **Data Master**:

```blade
<li class="nav-header">LAPORAN</li>
<li class="nav-item">
    <a href="{{ route('laporan.periode') }}" target="_blank" class="nav-link">
        <i class="nav-icon bi bi-file-earmark-pdf"></i>
        <p>Laporan Periode</p>
    </a>
</li>
```

![menu-cetak](/assets/image/laravel01-06/menu-cetak.png){.bingkai .kiri}

`nav-header` adalah judul kelompok menu pada AdminLTE. Menu ini tidak diberi penanda aktif karena laporan selalu dibuka di tab baru.

### Uji coba

1. Buka `/periode`, klik **Cetak PDF**. PDF tampil di tab baru dengan kop, tabel, tanda tangan, dan QR code.
2. Tambahkan minimal 30 data periode agar laporan lebih dari satu halaman. Cara cepatnya lewat tinker:

   ```bash
   php artisan tinker
   ```

   ```php
   for ($i = 1; $i <= 30; $i++) { \App\Models\Periode::create(['periode' => 'GANJIL ' . (2030 + $i) . '/' . (2031 + $i), 'singkatan' => 300 + $i]); }
   ```

   Ketik `exit` untuk keluar dari tinker, lalu cetak ulang laporan. Pastikan:
   - kop dan judul kolom tabel muncul di **setiap** halaman;
   - footer menunjukkan "Halaman 1 dari 2", "Halaman 2 dari 2", dan seterusnya.

   Data uji ini akan hilang saat menjalankan `php artisan migrate:fresh --seed`.
3. Pindai QR code dengan HP, pastikan mengarah ke halaman periode.
4. Ganti `inline` menjadi `attachment` di controller, pastikan PDF terunduh.

### Jika terjadi error

| Gejala | Penyebab dan solusi |
|---|---|
| `Class "Spipu\Html2Pdf\Html2Pdf" not found` | Belum `composer require spipu/html2pdf`, atau lupa `use` di controller |
| Error tentang `gd` atau `imagecreate` | Ekstensi `gd` belum aktif; lihat bagian Persiapan |
| `Html2PdfException` ... `tag ... is not closed` | Ada tag HTML yang tidak ditutup di view laporan |
| Gambar tidak tampil | Pakai `public_path(...)`, bukan `asset(...)`, dan pastikan file benar-benar ada |
| Tabel tidak selebar halaman | Beri `width` dalam persen pada setiap `<th>` dengan total 100% |

## Latihan

1. Ubah laporan menjadi **landscape** (mendatar).
2. Tambahkan filter agar URL `/laporan/periode?status=1` hanya mencetak periode yang aktif. Petunjuk: gunakan `request('status')` dan `->when(...)` pada query.
3. Tampilkan jumlah total periode di bawah tabel, misalnya **Jumlah: 4 periode**.

## Rangkuman

- spipu/html2pdf mengubah HTML menjadi PDF dengan murni PHP, tanpa program tambahan di server.
- `<page_header>`, `<page_footer>`, `[[page_cu]]`, dan `[[page_nb]]` memudahkan kop dan nomor halaman.
- View laporan disusun dengan `<table>` dan CSS sederhana, tanpa layout Bootstrap.
- Controller merender view menjadi HTML, menuliskannya ke PDF, lalu mengirimnya dengan `response()`.

## Persiapan Pertemuan 7

Pertemuan 7 merangkum Pertemuan 3–6 menjadi satu alur cepat sambil membuat menu **Program Studi**. Pastikan CRUD periode dan laporan PDF periode sudah berjalan, karena keduanya menjadi contoh yang diikuti.

## Referensi

- [spipu/html2pdf di GitHub](https://github.com/spipu/html2pdf)
- [Dokumentasi html2pdf: page, header, footer](https://github.com/spipu/html2pdf/blob/master/doc/page.md)
- [spipu/html2pdf di Packagist](https://packagist.org/packages/spipu/html2pdf)
- [Laravel: Views](https://laravel.com/docs/views)
