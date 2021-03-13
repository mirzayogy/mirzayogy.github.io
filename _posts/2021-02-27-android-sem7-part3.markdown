---
layout: post
title:  "Praktikum Android Semester 7: Part 3"
date:   2021-02-27 09:00:00 +0800
categories: android
published: true
comments : true
description: Menampilkan gambar dari hasil request web service ke dalam circle imageview
tags: 
 - android
 - glide
 - circleimageview
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-02-13-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-02-14-android-sem7-part2 %})
Praktikum Android Semester 7: Part 3
[Praktikum Android Semester 7: Part 4]({% post_url 2021-03-06-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-03-08-android-sem7-part5 %})


Pada [Part 2]({% post_url 2021-02-14-android-sem7-part2 %}) aplikasi sudah dapat menampilkan data ke dalam recyclerview, namun belum menampilkan data avatar yang merupakan url gambar, untuk menampilkannya praktikum kali ini menggunakan glide dan circleimageview.

tambahkan pada `build.graddle.app`
{% highlight  xml %}
    ...
    dependencies{
        ...
        implementation 'de.hdodenhof:circleimageview:3.1.0'
        implementation 'com.github.bumptech.glide:glide:4.11.0'
    }
{% endhighlight %}

Pada layout `item_row_user` modifikasi menjadi

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:orientation="horizontal"
    android:padding="16dp">

    <de.hdodenhof.circleimageview.CircleImageView
        android:id="@+id/iv_item_avatar"
        android:layout_width="55dp"
        android:layout_height="55dp"
        android:layout_marginEnd="16dp"
        android:layout_marginRight="16dp"
        tools:src="@mipmap/ic_launcher_round"
         />

    <LinearLayout
        android:orientation="vertical"
        android:layout_width="match_parent"
        android:layout_height="wrap_content">

        <TextView
            android:id="@+id/tv_item_name"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:layout_marginBottom="4dp"
            android:textSize="16sp"
            android:textStyle="bold"
            tools:text="Name" />

        <TextView
            android:id="@+id/tv_item_email"
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:maxLines="2"
            tools:text="Email" />
    </LinearLayout>


</LinearLayout>
{% endhighlight %}

Pada method `bind` ubah lah isinya seperti berikut

{% highlight  kotlin %}
fun bind(userData: UserData) {
    with(binding){
        tvItemName.text = userData.first_name + " " + userData.last_name
        tvItemEmail.text = userData.email

        Glide.with(itemView.context)
            .load(userData.avatar)
            .apply(RequestOptions().override(55, 55))
            .into(ivItemAvatar)
    }
}
{% endhighlight %}

Okedeh ! jadilah tampil gambar dari url avatar

[Praktikum Android Semester 7: Part 1]({% post_url 2021-02-13-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-02-14-android-sem7-part2 %})
Praktikum Android Semester 7: Part 3
[Praktikum Android Semester 7: Part 4]({% post_url 2021-03-06-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-03-08-android-sem7-part5 %})
