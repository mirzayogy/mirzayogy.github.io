# Blog Jigsaw untuk GitHub Pages

## Menjalankan di lokal

```bash
composer install
composer serve        # build + server di http://localhost:8000
```

## Struktur

```
source/
├── _layouts/
│   ├── main.blade.php     # kerangka HTML (head, header, footer)
│   ├── post.blade.php     # tampilan satu tulisan
│   └── page.blade.php     # halaman statis Markdown (mis. Tentang)
├── _partials/
│   ├── header.blade.php   # navigasi
│   ├── footer.blade.php
│   └── post-card.blade.php
├── _posts/                # tulisan blog (.md) -> /blog/{nama-file}
├── assets/css/main.css    # gaya (otomatis mode gelap)
├── index.blade.php        # beranda: 5 tulisan terbaru
├── blog.blade.php         # daftar tulisan + paginasi (5 per halaman)
├── tentang.md             # halaman Tentang
└── 404.blade.php
```

## Menambah halaman statis baru

Buat `source/nama-halaman.md`:

```yaml
---
extends: _layouts.page
section: content
title: Judul Halaman
---
```

Lalu tambahkan tautannya di `source/_partials/header.blade.php`.

## Deploy

1. Ubah `baseUrl` di `config.production.php`.
2. Push ke branch `main`.
3. Di GitHub: **Settings → Pages → Source: GitHub Actions**.

Workflow `.github/workflows/deploy.yml` akan build dan deploy otomatis.

```bash
cd ~/JigsawProjects/Ujicoba

# vendor ikut ter-commit sebelumnya; keluarkan dari git (file lokal tetap ada)
git rm -r --cached vendor -q
git add -A
git commit -m "Blog Jigsaw + deploy GitHub Pages"

git remote add origin https://github.com/USER/REPO.git
git fetch origin

# (opsional) simpan isi lama di branch cadangan, untuk jaga-jaga
git push origin origin/main:refs/heads/backup-lama

# timpa isi lama dengan proyek ini
git push -f origin main
```