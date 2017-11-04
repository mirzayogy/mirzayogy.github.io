---
layout: post
title:  "Android Basics - User Interface - Building Layouts: Part 2"
date:   2017-11-04 12:46:41 +0800
categories: android
comments : true
description: Bagian kedua dari Course Android Basics, ViewGroup, jenisnya, atributnya
tags: 
 - android
---

Pada bagian pertama kita sudah membahas tentang `View` dan Atributnya. Pada bagian kedua akan dibahas `ViewGroup` yang merupakan tempat meletakkan beberapa atau banyak View. 

![Miaww]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP2.001.png)

Gambar yang berwarna hijau merupakan `ImageView`, sedangkan yang biru adalah `TextView` yang keduanya sudah dibahas pada bagian pertama. Bagian yang berwarna jingga dan merah merupakan `ViewGroup` yang menampung `View`. Pada gambar sebelah kiri nampak sebuah `ViewGroup` menampung 1 (satu) `ImageView` dan 2 (dua) `TextView`, bagian tengah menampung masing-masing 1 (satu) `ImageView` dan `TextView` begitu pula pada bagian kanan.

`ViewGroup` yang menampung `View` disebut sebagai `parent` sedangkan View-nya disebut `child`, dan sesama view disebut sebagai `siblings` atau saudara. `Parent` dapat mengatur dimana letak `child` dan bagaimana susunannya tergantung pada jenis `parent` dan isian atributnya.

![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP2.002.png)

![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP2.003.png)

Contoh pertama adalah `LinearLayout` yang bisa mengatur `child` agar disusun secara `vertikal` dari atas ke bawah atau secara `horizontal` dari kiri ke kanan

Contoh kedua adalah `RelativeLayout` yang bisa mengatur posisi `child` berdasarkan posisi dari komponen lain. Gambar berikut menunjukkan bagaimana `TextView` dapat diletakkan pada bagian bawah `parent`nya sedangkan `ImageView` diletakkan pada bagian atas `parent`nya

![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP2.004.png)

Gambar berikut menunjukkan bagaimana `ImagetView` dapat diletakkan pada sebelah kanan dari `parent`nya, kemudian `TextView` Birthday diletakkan pada sebelah kanan dari `ImageView` dan terakhir `TextView` January 1 diletakkan pada sebelah kanan dari `ImageView` dan juga bagian bawah dari `TextView` Birthday


![Miaww1]({{ site.url }}/assets/01Beginner.01Inteface.01LayoutsP2.005.png)


Sekarang coba buka [LINK INI](http://labs.udacity.com/android-visualizer/#/android/linear-layout) untuk latihan menggunakan ViewGroup. Kemudian tambahkan 

    android:background="#f44336"

setelah coding `android:orientation="vertical"` supaya tampak ada dimana posisi sang `ViewGroup`.

Berikutnya tambahkan atau copy paste bagian

{% highlight xml %}
    <TextView
        android:text="Patel "
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        android:textSize="24sp"  />
{% endhighlight %}

yang berfungsi untuk menambahkan `TextView` baru

Anda bisa juga mencoba mengganti nilai atribut pada `ViewGroup`

    android:orientation="vertical"

menjadi `horizontal`

