<?php

return [
    'production' => false,
    'baseUrl' => '',

    // Identitas situs (jangan pakai key "title"/"description" di sini,
    // karena keduanya dipakai oleh frontmatter tiap halaman/post)
    'siteName' => 'Biar Ingat',
    'siteDescription' => 'Catatan tentang pemrograman, riset, dan hal-hal lain.',
    'siteAuthor' => 'Mirza Y. Kurniawan',  // ganti sesuai nama Anda
    'locale' => 'id',

    // Tag yang ditampilkan di sidebar (urutan sesuai daftar ini).
    // Tag yang belum dipakai di post mana pun otomatis disembunyikan.
    // Kosongkan ([]) untuk menampilkan 8 tag yang paling sering dipakai.
    // 'recommendedTags' => ['laravel', 'perkuliahan', 'jigsaw', 'php', 'catatan'],
    'recommendedTags' => ['laravel'],

    // Info seri tulisan (dipakai post yang punya frontmatter "series: ...")
    'seriesInfo' => [
        'laravel-simtikum' => [
            'title' => 'Laravel: Membangun SIMTIKUM',
            'mataKuliah' => 'PBO 2',  // ganti sesuai nama mata kuliah
        ],
    ],

    'collections' => [
        'posts' => [
            'path' => 'blog/{filename}',
            'sort' => '-date',
            'extends' => '_layouts.post',
            'section' => 'content',
            'author' => 'Mirza Y. Kurniawan',  // ganti sesuai nama Anda
        ],
    ],

    // ---------------------------------------------------------------
    // Helper: dipanggil di template sebagai $page->namaHelper(...)
    // ---------------------------------------------------------------

    'getDate' => function ($page) {
        return \DateTime::createFromFormat('U', $page->date);
    },

    // Tanggal berformat Indonesia, mis. "11 Oktober 2026"
    'tanggal' => function ($page) {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];
        $d = $page->getDate();

        return $d->format('j') . ' ' . $bulan[(int) $d->format('n')] . ' ' . $d->format('Y');
    },

    'excerpt' => function ($page, $length = 180) {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($page->getContent())));

        return mb_strlen($text) > $length
            ? rtrim(mb_substr($text, 0, $length)) . '…'
            : $text;
    },

    'readingTime' => function ($page) {
        $words = str_word_count(strip_tags($page->getContent()));

        return max(1, (int) ceil($words / 200));
    },

    // Menandai menu aktif: '/' hanya aktif di beranda,
    // '/blog' aktif di daftar blog, halaman paginasi, dan semua post
    // Slug tag untuk anchor di halaman /tags, mis. "Machine Learning" -> "machine-learning"
    'tagSlug' => function ($page, $tag) {
        return \Illuminate\Support\Str::slug($tag);
    },

    'isActive' => function ($page, $path) {
        $current = trim($page->getPath(), '/');
        $path = trim($path, '/');

        return $path === '' ? $current === '' : str_starts_with($current, $path);
    },
];
