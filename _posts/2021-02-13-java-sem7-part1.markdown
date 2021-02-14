---
layout: post
title:  "Praktikum Java Semester 7: Part 1"
date:   2021-02-13 12:46:41 +0800
categories: java
published : false
comments : true
description: Langkah-langkah pengerjaan praktikum Java khusus untuk semester 7 Fakultas Teknologi Informasi, Universitas Islam Kalimantan Muhammad Arsyad Al Banjari Banjarmasin
tags: 
 - java
---

Pada praktikum khusus semester 7 ini diharapkan sudah menguasai dasar pemrograman android, bisa dipelajari diantaranya melalui playlist berikut

<a href="https://www.youtube.com/watch?v=jlteXciXQJM&list=PLlBn2dsdFy7B3tXOrhBn7kfuWgSXDKXpk&index=1" target="_blank">Playlist Android untuk Pemula</a>

Mengelola data bisa dengan menggunakan beberapa cara diantaranya
1. SharedPreferences untuk data kecil
2. SQLite untuk penyimpanan data local
3. Menggunakan Web Service / Web API sebagai backend

Beberapa Web Service sederhana yang bisa digunakan sebagai latihan
1. <a href="https://reqres.in/api/users?page=1" target="_blank">https://reqres.in/api/users?page=1</a>
2. <a href="https://randomuser.me/api/" target="_blank">https://randomuser.me/api/</a>
3. <a href="https://quote-api.dicoding.dev/list" target="_blank">https://quote-api.dicoding.dev/list</a>

Web service kompleks yang gratis sampai pemakaian tertentu
1. <a href="https://www.football-data.org/" target="_blank">https://www.football-data.org/</a>
2. <a href="https://developers.themoviedb.org/3/getting-started/introduction" target="_blank">https://developers.themoviedb.org/3/getting-started/introduction</a>
3. <a href="https://openweathermap.org/api" target="_blank">https://openweathermap.org/api</a>

Dan tentu masih banyak Web Service lainnya yang bisa kita temui, seperti untuk pengiriman paket, pemesanan tiket, dan lainnya.

Pada part 1 ini akan digunakan web service yang sederhana dulu agar bisa dipraktekkan cara menghubungkan aplikasi android dengan web service yang sudah ada





Bagian pertama mengenalkan kepada view yang dimiliki android diantaranya ada 3 (tiga) view yaitu `TextView` yang berfungsi untuk menampilkan tulisan, `ImageView` untuk menampilkan gambar, dan `Button`.. ya.. Tombol

![Miaww]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP1.001.png)

View pada Android dituliskan dengan `CamelCase` yang berarti Punuk Onta, karena setiap kata diawali dengan huruf besar makanya disebut mirip dengan punuk onta.       

`Sintaks` adalah aturan yang mendefinisikan bagaimana menulis bahasa dengan benar, dalam hal ini XML. Jika komponen yang ditulis tidak sesuai dengan sintaks, maka komponen tidak akan muncul pada layar. Berikut sintaks penulisan XML

![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP1.002.png)

Perhatikan tulisan berwarna hijau menunjukkan nama komponennya yang diawali oleh `opening angle bracket (<)` dan diakhiri dengan `garis miring` dan `closing angle bracket (/>)` yang mana disebut dengan `Self Closing Tag`. Tulisan berwarna biru adalah atribut dari komponen, dan yang berwarna merah dan selalu menggunakan tanda petik (") adalah nilai dari atributnya misalkan 

{% highlight xml %}
<TextView
    android:text="Ini Nilai Atribut"
/>
{% endhighlight %}

Untuk latihan bisa dicoba pada [LINK INI](http://labs.udacity.com/android-visualizer/#/android/text-view) akan tetapi yang perlu diperhatikan bahwa tools ini hanya untuk latihan, dan tidak memiliki fitur selengkap android studio. 

Coba tuliskan perintah berikut kemudian ganti-ganti nilai atributnya
{% highlight xml %}
<TextView
    android:text="Happy Birthday!"
    android:background="#9C27B0"
    android:textColor="#FFFFFF"
    android:textSize="24sp"
    android:layout_width="150dp"
    android:layout_height="75dp" />
{% endhighlight %}

Jika ingin mencoba `Views` lainnya bisa dilihat contoh pada [LINK INI](https://drive.google.com/file/d/0B5XIkMkayHgRMVljUVIyZzNmQUU/view) 

Kombinasi warna pada menu maupun warna huruf sangat penting dalam perancangan tampilan aplikasi berikut [LINK](https://material.io/guidelines/style/color.html#) yang bisa dijadikan acuan untuk pemilihan warna 

Selain warna penentuan ukuran huruf juga penting untuk memperindah tampilan dan tidak mengganggu fokus pengguna, berikut 
[LINK](https://material.io/guidelines/style/typography.html#) yang membahas tentang ukuran huruf.


Nilai atribut `android:layout_width` dan `android:layout_height` dapat kita isi dengan `"wrap_content"` untuk membungkusnya sesuai dengan ukuran isi atau besarnya font

Coba tuliskan perintah berikut bandingkan dengan hasil source code sebelumnya
{% highlight xml %}
<TextView
    android:text="Happy Birthday!"
    android:background="#9C27B0"
    android:textColor="#FFFFFF"
    android:textSize="24sp"
    android:layout_width="wrap_content"
    android:layout_height="wrap_content" />
{% endhighlight %}

Views `ImageView` bisa digunakan untuk menampilkan gambar, untuk mencoba bisa ditulis source code berikut

{% highlight xml %}
<ImageView
    android:src="@drawable/cake"
    android:layout_width="wrap_content"
    android:layout_height="wrap_content"
    android:scaleType="center"/>
{% endhighlight %}

Gambar `cake` yang disediakan sangat besar sehingga yang tampak hanya bagian Agar gambar cake terlihat jelas nilai pada `android:scaleType` bisa diganti menjadi `"centerCrop"`

Untuk mengganti gambar bisa dilihat pada tombol `"AVAILABLE IMAGES"` pada bagian bawah kemudian ganti sesuai dengan nama gambarnya. Setelah gambar diganti, coba atur nilai atribut agar gambar selalu penuh (`full bleed`).

Happy Coding
