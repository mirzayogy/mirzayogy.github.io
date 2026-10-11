<?php

// Nilai di sini menimpa config.php saat menjalankan:
//   vendor/bin/jigsaw build production
//
// - Repo bernama "username.github.io"  -> 'https://username.github.io'
// - Repo lain (project site)           -> 'https://username.github.io/nama-repo'

return [
    'production' => true,
    'baseUrl' => 'https://mirzayogy.github.io',

    // Gabungkan pengaturan collection di bawah dengan yang ada di config.php
    'merge_collection_configs' => true,

    'collections' => [
        'posts' => [
            // Post dengan "draft: true" tidak ikut di-build untuk produksi
            // (tetap terlihat saat preview lokal dengan `jigsaw serve`)
            'filter' => fn ($post) => ! $post->draft,
        ],
    ],
];
