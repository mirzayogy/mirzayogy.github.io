import jigsaw from '@tighten/jigsaw-vite-plugin';
import { defineConfig } from 'vite';

// `npm run dev`: build Jigsaw otomatis setiap ada file yang berubah,
// lalu browser di-refresh sendiri. Buka http://localhost:5173
export default defineConfig({
    plugins: [
        jigsaw({
            input: ['source/assets/css/main.css'],
            refresh: {
                files: [
                    'config.php',
                    'config.production.php',
                    'bootstrap.php',
                    'listeners/**/*.php',
                    'source/**/*.md',
                    'source/**/*.php',
                    'source/**/*.html',
                    'source/**/*.css',
                    'source/**/*.js',
                ],
            },
        }),
    ],
});
