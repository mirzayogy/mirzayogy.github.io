---
layout: post
title:  Install Jekyll di Windows
categories: Blogging
date:   2016-12-26 23:01:41 +0800
comments: true
---
Install jekyll
Install chocolatey dengan buka cmd dengan run as administrator

	@powershell -NoProfile -ExecutionPolicy Bypass -Command "iex ((New-Object System.Net.WebClient).DownloadString('https://chocolatey.org/install.ps1'))" && SET "PATH=%PATH%;%ALLUSERSPROFILE%\chocolatey\bin"
	
Tutup cmd, buka ulang as administrator

	choco install ruby –y
	
Tutup cmd, buka ulang as administrator

	gem install bundler
	
Jika tidak ada masalah ketika menginstall bundler maka langsung install bundler, jika ada masalah maka Install RailsInstaller dari website berikut
	
	http://railsinstaller.org/en

Download file `cacert.pem` dari website berikut

	http://curl.haxx.se/ca/cacert.pem

Letakkan di folder 

	C:\RailsInstaller\
	
Atur environment variable, bisa dengan perintah

	set SSL_CERT_FILE=C:\RailsInstaller\cacert.pem 
	
Install bundler dengan perintah

	gem install bundler
	
Install Jekyll dengan perintah

	gem install jekyll
	
Pindah ke folder yang ingin disimpan

	jekyll new username.github.io
	cd username.github.io
	
Hapus file index.html jika sebelumnya anda membuat file tersebut

Untuk menjalankannya secara local jalankan

	jekyll serve
	
Buka dengan browser

	http://127.0.0.1:4000
	
Untuk deploynya jalankan GitHub for desktop Tuliskan summary commit misalkan `commit pertama`, kemudian tekan tombol `commit to master` 

Tekan tombol `sync`
Buka di halaman

	https://username.github.io/
	
<div id="disqus_thread"></div>
<script>
    /**
     *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
     *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables
     */
    /*
    var disqus_config = function () {
        this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
        this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };
    */
    (function() {  // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        
        s.src = 'https://mirzayogy.disqus.com/embed.js';
        
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>