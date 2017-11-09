---
layout: post
title:  "GitHub Sebagai Media Pengumpulan Tugas"
date:   2017-11-08 12:46:41 +0700
categories: perkuliahan
comments : true
description: GitHub merupakan wadah untuk berbagi source code, tapi kali ini saya gunakan sebagai media pengumpulan tugas yang berbentuk source code agar tidak memenuhi email dan lebih mudah dalam memantau perkembangannya, juga mengenalkan GitHub kepada mahasiswa
tags: 
 - github
---

GitHub merupakan wadah untuk berbagi source code, tapi kali ini saya gunakan sebagai media pengumpulan tugas yang berbentuk source code agar tidak memenuhi email saya dan lebih mudah dalam memantau perkembangan source code, juga mengenalkan GitHub kepada mahasiswa.

Tutorial ini akan menjelaskan cara penggunaan Git bash, dan tidak akan menyenggol masalah github desktop karena mudah saja, hal ini berdasarkan komplain beberapa mahasiswa yang kerepotan karena Windows 32bit tidak bisa menggunakan github desktop.

Untuk bisa menggunakan GitHub terlebih dahulu bisa mendaftar di [website](https://github.com/)-nya, install juga git dari [sini](https://git-scm.com/download/win), untuk yang menggunakan windows 64bit akan lebih mudah menggunakan github desktop yang bisa didownload di [sini](https://desktop.github.com/). Setelah mendaftar jangan lupa verifikasi di email.

Sign in pada GitHub kemudian buat repository baru dengan klik `New repository`. Berikan nama misalkan `repo_tugas` kemudian klik tombol `Create repository`

Untuk tugas Java Netbeans dan Android Studio bisa dibuat terlebih dahulu projectnya kemudian simpan, lalu buka foldernya, klik kanan tempat kosong pilih `Git bash here`. Pada tugas C++ bisa dibuat terlebih dahulu foldernya kemudian sama dengan sebelumnya, klik kanan. `Git bash here`

Ketikkan perintah berikut yang mana akan menandai bahwa folder tersebut merupakan sebuah repository

{% highlight bash%}
git init
{% endhighlight %}

Ketikkan perintah berikut yang menghubungkan antara repository local dengan repository github, copy dari `https` dari halaman github setelah berhasil membuat repository misalkan `https://github.com/username/repo_tugas.git`

{% highlight bash %}
git remote add origin "https://github.com/username/repo_tugas.git"
{% endhighlight %}

Buat file C++ dan simpan dalam folder repository local, kita bisa lihat status repository dengan mengetikkan

{% highlight bash%}
git status
{% endhighlight %}

Kita akan push file yang kita buat tadi, terlebih dahulu kita add dengan perintah 

{% highlight bash%}
git add -A
{% endhighlight %}

Kemudian kita commit dengan perintah

{% highlight bash%}
git commit -a -m "Commit Pertama"
{% endhighlight %}

Jika ada perubahan file maka lakukkan `add` lagi, dan `commit` lagi seperti pada 2 (dua) perintah sebelumnya. Untuk melihat riwayat commit bisa dengan perintah 

{% highlight bash%}
git log
{% endhighlight %}

Terakhir untuk mengupload semua ke repository GitHub, lakukan dengan perintah

{% highlight bash%}
git push -u origin master
{% endhighlight %}

Video bag.1
<iframe width="560" height="315" src="https://www.youtube.com/embed/Siem8GQ54No" frameborder="0" allowfullscreen></iframe>



Video bag.2
<iframe width="560" height="315" src="https://www.youtube.com/embed/ab370-bEWK8" frameborder="0" allowfullscreen></iframe>

Lebih lengkap di
<iframe width="560" height="315" src="https://www.youtube.com/embed/xuB1Id2Wxak" frameborder="0" allowfullscreen></iframe>