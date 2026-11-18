// Menambahkan tombol "Salin" pada setiap blok kode (```php, ```bash, ```blade, dll.)
(function () {
    'use strict';

    function salin(teks) {
        if (navigator.clipboard && window.isSecureContext) {
            return navigator.clipboard.writeText(teks);
        }

        // Cadangan untuk browser lama / halaman non-HTTPS
        return new Promise(function (resolve, reject) {
            const area = document.createElement('textarea');
            area.value = teks;
            area.setAttribute('readonly', '');
            area.style.position = 'fixed';
            area.style.opacity = '0';
            document.body.appendChild(area);
            area.select();
            try {
                document.execCommand('copy') ? resolve() : reject();
            } catch (e) {
                reject(e);
            } finally {
                document.body.removeChild(area);
            }
        });
    }

    function pasangTombol(pre) {
        const code = pre.querySelector('code');
        if (!code || pre.parentElement.classList.contains('code-wrap')) {
            return;
        }

        // Bungkus <pre> agar tombol tidak ikut tergulir saat kode panjang digeser
        const wrap = document.createElement('div');
        wrap.className = 'code-wrap';
        pre.parentNode.insertBefore(wrap, pre);
        wrap.appendChild(pre);

        const tombol = document.createElement('button');
        tombol.type = 'button';
        tombol.className = 'copy-btn';
        tombol.textContent = 'Salin';
        tombol.setAttribute('aria-label', 'Salin kode');
        wrap.appendChild(tombol);

        let timer;
        tombol.addEventListener('click', function () {
            salin(code.innerText.replace(/\n$/, ''))
                .then(function () {
                    tombol.textContent = 'Tersalin ✓';
                    tombol.classList.add('copied');
                })
                .catch(function () {
                    tombol.textContent = 'Gagal';
                })
                .finally(function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        tombol.textContent = 'Salin';
                        tombol.classList.remove('copied');
                    }, 2000);
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.prose pre').forEach(pasangTombol);
    });
})();
