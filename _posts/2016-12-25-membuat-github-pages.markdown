---
layout: post
title:  Membuat GitHub Pages
categories: Blogging
date:   2016-12-25 23:00:41 +0800
comments: true
---
Untuk membuat blog dengan Jekyll kita bisa menggunakan github sebagai host gratisnya. GitHub adalah layanan web untuk proyek-proyek yang bersifat open source, gampangnya ini sosmednya software developer buat bertukar coding gitu lah. Saya juga bikin supaya kekinian ihik ihik. Untuk pembuatan blognya yang dalam hal ini disebut sebagai github pages, repository harus dalam nama username.github.io username-nya ya diganti username akunnya lah. Langkah-langkahnya bisa dijelaskan sebagai berikut:

1.	Daftar GitHub di [https://github.com/](https://github.com/)
2.	Buat repository baru dengan nama `username.github.io`
![Create Repo]({{ site.url }}/assets/create-repo.png)
 

3.	Download GitHub for windows di [https://pages.github.com/](https://pages.github.com/)
4.	Kembali ke halaman GitHub klik Set up in Desktop
![Setup Desktop]({{ site.url }}/assets/setup-desktop.png) 
5.	Pilih folder yang diinginkan
6.	Hasil pada GitHub 
![GitHub Desktop]({{ site.url }}/assets/github-desktop.png)  
7.	Buat sebuah file index.html dan simpan dalam folder repository yang sudah kita buat 


 {% highlight html %}

<!DOCTYPE html>
<html>
<head>
	<title>Halo Dunia</title>
</head>
<body>
	<h1>Hello World</h1>
	<p>Halaman ini menggunakan GitHub Pages</p>
</body>
</html>

 {%endhighlight%}


8.	Isi summary dengan `commit pertama` klik `commit to master` kemudian `publish`
9.	Buka pada browser `username.github.io`

![Hasil html]({{ site.url }}/assets/hasil-html.png)  


10. Selanjutnya untuk menginstal Jekyll bisa dibaca di [SINI](http://mirzayogy.com/blogging/2016/12/25/install-jekyll.html)
