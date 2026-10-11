<?php

// Nilai di sini menimpa config.php saat menjalankan:
//   vendor/bin/jigsaw build production
//
// - Repo bernama "username.github.io"  -> 'https://username.github.io'
// - Repo lain (project site)           -> 'https://username.github.io/nama-repo'

return [
    'production' => true,
    'baseUrl' => 'https://mirzayogy.github.io',
];
