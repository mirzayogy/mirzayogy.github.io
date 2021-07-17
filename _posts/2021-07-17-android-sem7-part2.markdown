---
layout: post
title:  "Praktikum Android Semester 7: Part 2"
date:   2021-07-17 09:00:00 +0800
categories: android
published: true
comments : true
description: Menampilkan data hasil request web service ke dalam item recyclerview
tags: 
 - android
 - recyclerview
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
Praktikum Android Semester 7: Part 2
[Praktikum Android Semester 7: Part 3]({% post_url 2021-02-27-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-03-06-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-03-08-android-sem7-part5 %})


Pada [Part 1]({% post_url 2021-02-13-android-sem7-part1 %}) aplikasi sudah dapat melakukan request data namun hanya  menampilkan 1 (satu) field ke TextView. Data dapat ditampilkan dalam bentuk list yang bisa diimplementasikan dalam Android Studio dengan menggunakan ListView atau RecyclerView. Pada part ini akan digunakan `RecyclerView` dengan layout sederhana.

tambahkan pada `build.graddle.app`
{% highlight  xml %}
    ...
    dependencies{
        ...
        implementation 'androidx.recyclerview:recyclerview:1.1.0'
    }
{% endhighlight %}

pada layout, buat sebuah `Layout Resource File` dan berikan nama `item_row_user` dan isikan dengan

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:orientation="vertical"
    android:padding="16dp">

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
{% endhighlight %}

Buat sebuah empty activity baru didalam package `ui.user` dengan nama `UserActivity`, buka file `activity_user.xml` dan isikan dengan

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:id="@+id/activity_user"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".ui.user.UserActivity">

    <ProgressBar
        android:id="@+id/progressBarUser"
        style="@style/Widget.AppCompat.ProgressBar.Horizontal"
        android:indeterminate="true"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

    <androidx.recyclerview.widget.RecyclerView
        android:id="@+id/rv_users"
        android:layout_width="match_parent"
        android:layout_height="fill_parent"
        tools:listitem="@layout/item_row_user" />

</LinearLayout>

{% endhighlight %}


Buat sebuah class baru didalam package `ui.user` dengan nama `ListUserAdapter` dan isikan dengan

{% highlight  kotlin %}
class ListUserAdapter(private val listUser: ArrayList<UserData>) : RecyclerView.Adapter<ListUserAdapter.ListViewHolder>() {
    class ListViewHolder(private val binding: ItemRowUserBinding) : RecyclerView.ViewHolder(binding.root) {

    }
}
{% endhighlight %}

Import dan implementasikan semua method yang diminta oleh interface Adapter RecyclerView. Masih pada file `ListUserAdapter`, tambahkan method `bind` di dalam inner class `ListViewHolder

{% highlight  kotlin %}
class ListViewHolder(private val binding: ItemRowUserBinding) : RecyclerView.ViewHolder(binding.root) {
        fun bind(userData: UserData) {
            with(binding){
                tvItemName.text = userData.first_name + " " + userData.last_name
                tvItemEmail.text = userData.email
            }
        }
    }
{% endhighlight %}

Masuk ke blok method `onCreateViewHolder` dan isikan dengan

{% highlight  kotlin %}
override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ListViewHolder {
    val binding = ItemRowUserBinding.inflate(LayoutInflater.from(parent.context), parent, false)
    return ListViewHolder(binding)
}
{% endhighlight %}

Masuk ke blok method `onBindViewHolder` dan isikan dengan

{% highlight  kotlin %}
override fun onBindViewHolder(holder: ListViewHolder, position: Int) {
        holder.bind(listUser[position])
}
{% endhighlight %}

Pada blok method `getItemCount` isikan dengan

{% highlight  kotlin %}
override fun getItemCount():Int = listUser.size
{% endhighlight %}

Sehingga hasil akhirnya ListUserAdapter berisikan

{% highlight  kotlin %}
class ListUserAdapter(private val listUser: ArrayList<UserData>) : RecyclerView.Adapter<ListUserAdapter.ListViewHolder>() {

    class ListViewHolder(private val binding: ItemRowUserBinding) : RecyclerView.ViewHolder(binding.root) {
        fun bind(userData: UserData) {
            with(binding){
                tvItemName.text = userData.first_name + " " + userData.last_name
                tvItemEmail.text = userData.email
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ListViewHolder {
        val binding = ItemRowUserBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ListViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ListViewHolder, position: Int) {
        holder.bind(listUser[position])
    }

    override fun getItemCount():Int = listUser.size

}
{% endhighlight %}

Ubah isi dari UserActivity dengan

{% highlight  kotlin %}
class UserActivity : AppCompatActivity() {

    private lateinit var binding:ActivityUserBinding
    private val list = ArrayList<UserData>()
    private val viewModel: UserViewModel by lazy {
        ViewModelProvider(this).get(UserViewModel::class.java)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityUserBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.rvUsers.setHasFixedSize(true)

        binding.progressBarUser.visibility = View.VISIBLE

        getListUser()

    }

    private fun getListUser() {
        viewModel.getUsers()
        viewModel.response.observe(this, {
            binding.progressBarUser.visibility = View.INVISIBLE
            list.addAll(it.data)
            binding.rvUsers.layoutManager = LinearLayoutManager(this)
            val listUserAdapter = ListUserAdapter(list)
            binding.rvUsers.adapter = listUserAdapter
        })
    }
}
{% endhighlight %}

Terakhir pada manifest kita ganti agar UserActivity menjadi Main Launcher

{% highlight  kotlin %}
<application ...
    <activity android:name=".ui.user.UserActivity">
        <intent-filter>
            <action android:name="android.intent.action.MAIN" />

            <category android:name="android.intent.category.LAUNCHER" />
        </intent-filter>
    </activity>
    <activity android:name=".MainActivity">

    </activity>
</application>
{% endhighlight %}


Voila ! jadilah data tampil di RecyclerView

[Praktikum Android Semester 7: Part 1]({% post_url 2021-02-13-android-sem7-part1 %})
Praktikum Android Semester 7: Part 2
[Praktikum Android Semester 7: Part 3]({% post_url 2021-02-27-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-03-06-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-03-08-android-sem7-part5 %})
