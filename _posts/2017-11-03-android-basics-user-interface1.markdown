---
layout: post
title:  "Android Basics - User Interface - Building Layouts: Part 1"
date:   2017-11-03 12:46:41 +0800
categories: android
comments : true
description: Bagian pertama dari Course Android Basics, Pengenalan Views, XML, attribute, dan modifikasinya
tags: 
 - android
---

Course Android Basics - User Interface dari Udacity ini terdiri dari 4 bagian
1. Building Layouts Part 1
   Pengenalan terhadap eXtensible Markup Language (XML) yang menjadi layout untuk aplikasi Android
2. Building Layouts Part 2
    Mengajarkan tentang grouping layout dan desain
3. Practice Set: Building Layouts
    Mulai menggunakan android studio untuk praktek layout
4. Project
    Membuat project Sederhana

Bagian pertama mengenalkan kepada view yang dimiliki android diantaranya ada 3 (tiga) view yaitu TextView yang berfungsi untuk menampilkan tulisan, ImageView untuk menampilkan gambar, dan Button.. ya.. Tombol
![Miaww]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP1.001.png)

View pada Android dituliskan dengan CamelCase yang berarti Punuk Onta, karena setiap kata diawali dengan huruf besar makanya disebut mirip dengan punuk onta.       

Sintaks adalah aturan yang mendefinisikan bagaimana menulis bahasa dengan benar, dalam hal ini XML. Jika komponen yang ditulis tidak sesuai dengan sintaks, maka komponen tidak akan muncul pada layar. Berikut sintaks penulisan XML
![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP1.002.png)

Perhatikan tulisan berwarna hijau menunjukkan nama komponennya yang diawali oleh opening angle bracket (<) dan diakhiri dengan garis miring dan closing angle bracket (/>) yang mana disebut dengan Self Closing Tag. Tulisan berwarna biru adalah atribut dari komponen, dan yang berwarna merah dan selalu menggunakan tanda petik (") adalah nilai dari atributnya misalkan 
{% highlight xml %}
<TextView
    android:text="Ini Nilai Atribut"
/>
{% endhighlight %}

Untuk latihan bisa pada [LINK INI](http://labs.udacity.com/android-visualizer/#/android/text-view) yang perlu diperhatikan bahwa tools ini hanya untuk latihan, dan tidak memiliki fitur selengkap android studio. 

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

[LINK](https://drive.google.com/file/d/0B5XIkMkayHgRMVljUVIyZzNmQUU/view) Contoh Android Views

[LINK](https://material.io/guidelines/style/color.html#) Kombinasi Warna

[LINK](https://material.io/guidelines/style/typography.html#) Kombinasi ukuran Text


Nilai atribut android:layout_width dan android:layout_width dapat kita isi dengan "wrap_content" untuk membungkusnya sesuai dengan ukuran isi atau besarnya font

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

Untuk ImageView bisa dicoba source code berikut

{% highlight xml %}
<ImageView
    android:src="@drawable/cake"
    android:layout_width="wrap_content"
    android:layout_height="wrap_content"
    android:scaleType="center"/>
{% endhighlight %}

Gambar cake yang disediakan sangat besar sehingga yang tampak hanya bagian Agar gambar cake terlihat jelas nilai pada android:scaleType bisa diganti menjadi "centerCrop"

Untuk mengganti gambar bisa dilihat pada tombol "AVAILABLE IMAGES" pada bagian bawah kemudian ganti sesuai dengan nama gambarnya. Setelah gambar diganti, coba atur nilai atribut agar gambar selalu penuh (full bleed).

Happy Coding
