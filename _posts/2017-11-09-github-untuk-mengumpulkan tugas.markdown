---
layout: post
title:  "GitHub Sebagai Media Pengumpulan Tugas"
date:   2017-11-09 12:46:41 +0700
categories: spk
published : true
comments : true
description: GitHub merupakan wadah untuk berbagi source code, tapi kali ini saya gunakan sebagai media pengumpulan tugas yang berbentuk source code agar tidak memenuhi email dan lebih mudah dalam memantau perkembangannya, juga mengenalkan GitHub kepada mahasiswa
tags: 
 - github
---

GitHub merupakan wadah untuk berbagi source code, tapi kali ini saya gunakan sebagai media pengumpulan tugas yang berbentuk source code agar tidak memenuhi email saya dan lebih mudah dalam memantau perkembangan source code, juga mengenalkan GitHub kepada mahasiswa.

Untuk bisa menggunakan GitHub terlebih dahulu bisa mendaftar di [website](https://github.com/)-nya, install juga git dari [sini](https://git-scm.com/download/win), untuk yang menggunakan windows 64bit akan lebih mudah menggunakan github desktop yang bisa didownload di [sini](https://desktop.github.com/). Tutorial ini akan menjelaskan cara penggunaan Git bash, dan tidak akan menyenggol masalah github desktop karena mudah saja, hal ini berdasarkan komplain beberapa mahasiswa yang kerepotan karena Windows 32bit tidak bisa menggunakan github desktop. Setelah mendaftar jangan lupa verifikasi di email

Sign in pada GitHub kemudian buat repository baru dengan klik `New repository`. Berikan nama misalkan `repo_tugas` kemudian klik tombol `Create repository`

Untuk tugas Java Netbeans dan Android Studio bisa dibuat terlebih dahulu projectnya kemudian simpan, lalu buka foldernya, klik kanan tempat kosong pilih `Git bash here`. Pada tugas C++ bisa dibuat terlebih dahulu foldernya kemudian sama dengan sebelumnya, klik kanan. `Git bash here`

Ketikkan perintah berikut yang mana akan menandai bahwa folder tersebut merupakan sebuah repository

{% highlight %}
git init
{% endhighlight %}

Ketikkan perintah berikut yang menghubungkan antara repository local dengan repository github
{% highlight %}
git remote add origin "https_dari_github"
{% endhighlight %}
