---
layout: post
title:  "Praktikum Android Semester 7: Part 1"
date:   2021-02-13 12:46:41 +0800
categories: android
comments : true
description: Langkah-langkah pengerjaan praktikum khusus untuk semester 7 Fakultas Teknologi Informasi, Universitas Islam Kalimantan Muhammad Arsyad Al Banjari Banjarmasin
tags: 
 - android
---

Pada praktikum khusus semester 7 ini diharapkan sudah menguasai dasar pemrograman android, bisa dipelajari salah satunya dari playlist berikut
<iframe width="560" height="315" src="https://www.youtube.com/watch?v=jlteXciXQJM&list=PLlBn2dsdFy7B3tXOrhBn7kfuWgSXDKXpk&index=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>


2. [GitHub](https://github.com/mirzayogyk/PraktekJavaWeb)

Course Android Basics - User Interface dari Udacity ini terdiri dari 4 bagian
1. Building Layouts Part 1
   Pengenalan terhadap eXtensible Markup Language (XML) yang menjadi layout untuk aplikasi Android
2. Building Layouts Part 2
    Mengajarkan tentang grouping layout dan desain
3. Practice Set: Building Layouts
    Mulai menggunakan android studio untuk praktek layout
4. Project
    Membuat project Sederhana

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
