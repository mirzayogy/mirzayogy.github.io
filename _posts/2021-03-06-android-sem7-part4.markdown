---
layout: post
title:  "Praktikum Android Semester 7: Part 4"
date:   2021-03-06 09:00:00 +0800
categories: android
published: true
comments : true
description: Membuat backend dengan PHP
tags: 
 - android
 - php
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-02-13-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-02-14-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-02-27-android-sem7-part3 %})
Praktikum Android Semester 7: Part 4

Pada [Praktikum Android Semester 7: Part 3]({% post_url 2021-02-27-android-sem7-part3 %}) aplikasi sudah dapat menampilkan gambar berdasarkan url yang terdapat pada data. Part ini akan dibangun web service menggunakan PHP yang akan berjalan secara local. Langkah pertama bisa diunduh dulu [Project Starter]({{ site.url }}/assets/praktikum-penjualan-api-starter.zip).

![StrukturProject]({{ site.url }}/assets/img/android-7-4/struktur-project.png)

Perhatikan file `database.php`, dimana terdapat nama database yang akan terhubung yaitu `praktikum_penjualan`, silahkan disesuaikan dengan nama database yang dibuat, jangan lupa untuk import file `sql` yang sudah disediakan.

Pada folder `api` buat sebuah folder `jenisbarang` kemudian buat didalamnya sebuah file dengan nama `jenisbarang.php`, isikan dengan

{% highlight  php %}
<?php
class Jenisbarang
{

  private $conn;
  public $id;
  public $namajenisbarang;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  function read()
  {
    $selectSQL = "SELECT * FROM jenisbarang";
    $stmt = $this->conn->prepare($selectSQL);
    $stmt->execute();

    return $stmt;
  }
}
?>
{% endhighlight %}

Masih pada folder yang sama buat file `read.php` dan isikan dengan

{% highlight  php %}
<?php
include_once "../../config/api-header.php";
include_once "jenisbarang.php";

$jenisbarang = new Jenisbarang($db);
$stmt = $jenisbarang->read();
$num = $stmt->rowCount();

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if ($num > 0) {
  $response["success"] = true;
  $response["message"] = "read data jenisbarang berhasil";
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $jenisbarang_item = array(
      "id" => $row["id"],
      "namajenisbarang" => $row["namajenisbarang"]
    );
    array_push($response["data"], $jenisbarang_item);
  }

  http_response_code(200);
  echo json_encode($response);

} else {
  http_response_code(404);
  $response["message"] = "data jenisbarang masih kosong";
  echo json_encode($response);
}
?>
{% endhighlight %}

Menggunakan <a href="https://www.postman.com/downloads/" target="_blank">Postman</a> atau browser, coba buka pada halaman
    
    http://localhost/praktikum-penjualan-api-starter/api/jenisbarang/read.php

![HasilApiRead]({{ site.url }}/assets/img/android-7-4/hasil-api-read.png)

Kembali ke Android Studio untuk menggunakannya bisa digunakan langkah yang serupa dengan [Part 2]({% post_url 2021-02-14-android-sem7-part2 %}).

Kembali ke package `model` kemudian didalamnya buat sebuah data class dengan nama `Jenisbarang` dan isikan dengan

{% highlight  kotlin %}
data class Jenisbarang(
        val success: Boolean,
        val data : List<JenisbarangData>,
        val message: String
)

data class JenisbarangData(
        @field:Json(name = "@id")
        val id: String,
        @field:Json(name = "@namajenisbarang")
        val namajenisbarang: String
)
{% endhighlight %}

Berikutnya buka `ApiService`, ganti BASE_URL dan tambahkan function getJenisbarang()

{% highlight  kotlin %}

private const val BASE_URL = "http://10.0.2.2/praktikum-penjualan-api-starter/api/"

private val moshi = Moshi.Builder()
    .add(KotlinJsonAdapterFactory())
    .build()

private val retrofit = Retrofit.Builder()
    .addConverterFactory(MoshiConverterFactory.create(moshi))
    .baseUrl(BASE_URL)
    .build()


interface ApiService {
    @GET("users?page=1")
    suspend fun getUsers(): User

    @GET("jenisbarang/read.php")
    suspend fun getJenisbarang(): Jenisbarang
}

object Api {
    val retrofitService : ApiService by lazy {
        retrofit.create(ApiService::class.java) }
}
{% endhighlight %}

Buat sebuah `Layout Resource File` dan berikan nama `item_row_jenisbarang` dan isikan dengan

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:orientation="vertical"
    android:padding="16dp">

    <TextView
        android:id="@+id/tvNamajenisbarang"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_marginBottom="4dp"
        android:textSize="16sp"
        android:textStyle="bold"
        tools:text="Name" />

</LinearLayout>
{% endhighlight %}

Buat sebuah empty activity baru didalam package `ui.jenisbarang` dengan nama `JenisbarangActivity`, buka file `activity_jenisbarang.xml` dan isikan dengan

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:id="@+id/activity_jenisbarang"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    android:orientation="vertical"
    tools:context=".ui.jenisbarang.JenisbarangActivity">

    <ProgressBar
        android:id="@+id/progressBarJenisbarang"
        style="@style/Widget.AppCompat.ProgressBar.Horizontal"
        android:indeterminate="true"
        android:layout_width="match_parent"
        android:layout_height="wrap_content" />

    <androidx.recyclerview.widget.RecyclerView
        android:id="@+id/rvJenisbarang"
        android:layout_width="match_parent"
        android:layout_height="fill_parent"
        tools:listitem="@layout/item_row_jenisbarang" />

</LinearLayout>
{% endhighlight %}

Buat sebuah class baru didalam package `ui.jenisbarang` dengan nama `ListJenisbarangAdapter` dan isikan dengan

{% highlight  kotlin %}
class ListJenisbarangAdapter(private val listJenisbarang: ArrayList<JenisbarangData>) : RecyclerView.Adapter<ListJenisbarangAdapter.ListViewHolder>() {
    class ListViewHolder(private val binding: ItemRowJenisbarangBinding) : RecyclerView.ViewHolder(binding.root) {

    }
}
{% endhighlight %}

Import dan implementasikan semua method yang diminta oleh interface Adapter RecyclerView. Masih pada file `ListJenisbarangAdapter`, tambahkan method `bind` di dalam inner class `ListViewHolder

{% highlight  kotlin %}
class ListViewHolder(private val binding: ItemRowJenisbarangBinding) : RecyclerView.ViewHolder(binding.root) {
        fun bind(jenisbarangData: JenisbarangData) {
            with(binding){
                tvItemName.text = jenisbarangData.first_name + " " + jenisbarangData.last_name
                tvItemEmail.text = jenisbarangData.email
            }
        }
    }
{% endhighlight %}

Masuk ke blok method `onCreateViewHolder` dan isikan dengan

{% highlight  kotlin %}
override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ListViewHolder {
    val binding = ItemRowJenisbarangBinding.inflate(LayoutInflater.from(parent.context), parent, false)
    return ListViewHolder(binding)
}
{% endhighlight %}

Masuk ke blok method `onBindViewHolder` dan isikan dengan

{% highlight  kotlin %}
override fun onBindViewHolder(holder: ListViewHolder, position: Int) {
        holder.bind(listJenisbarang[position])
}
{% endhighlight %}

Pada blok method `getItemCount` isikan dengan

{% highlight  kotlin %}
override fun getItemCount():Int = listJenisbarang.size
{% endhighlight %}

Sehingga hasil akhirnya ListJenisbarangAdapter berisikan

{% highlight  kotlin %}
class ListJenisbarangAdapter(private val listJenisbarang: ArrayList<JenisbarangData>) : RecyclerView.Adapter<ListJenisbarangAdapter.ListViewHolder>() {

    class ListViewHolder(private val binding: ItemRowJenisbarangBinding) : RecyclerView.ViewHolder(binding.root) {
        fun bind(jenisbarangData: JenisbarangData) {
            with(binding){
                tvItemName.text = jenisbarangData.first_name + " " + jenisbarangData.last_name
                tvItemEmail.text = jenisbarangData.email
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ListViewHolder {
        val binding = ItemRowJenisbarangBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ListViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ListViewHolder, position: Int) {
        holder.bind(listJenisbarang[position])
    }

    override fun getItemCount():Int = listJenisbarang.size

}
{% endhighlight %}

Ubah isi dari JenisbarangActivity dengan

{% highlight  kotlin %}

class JenisbarangActivity : AppCompatActivity() {
    private lateinit var binding: ActivityJenisbarangBinding
    private val list = ArrayList<JenisbarangData>()
    private val viewModel: JenisbarangViewModel by lazy {
        ViewModelProvider(this).get(JenisbarangViewModel::class.java)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityJenisbarangBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.rvJenisbarang.setHasFixedSize(true)

        binding.progressBarJenisbarang.visibility = View.VISIBLE

        getListJenisbarang()

    }

    private fun getListJenisbarang() {
        viewModel.response.observe(this, {
            binding.progressBarJenisbarang.visibility = View.INVISIBLE
            list.addAll(it.data)
            binding.rvJenisbarang.layoutManager = LinearLayoutManager(this)
            val listJenisbarangAdapter = ListJenisbarangAdapter(list)
            binding.rvJenisbarang.adapter = listJenisbarangAdapter
        })
    }
}
{% endhighlight %}

Terakhir pada manifest kita ganti agar UserActivity menjadi Main Launcher, juga tambahkan `usesClearTextTraffic` pada tag application

{% highlight  kotlin %}
<application ...

    android:usesCleartextTraffic="true">
    <activity android:name=".ui.jenisbarang.JenisbarangActivity">
        <intent-filter>
            <action android:name="android.intent.action.MAIN" />

            <category android:name="android.intent.category.LAUNCHER" />
        </intent-filter>
    </activity>
    <activity android:name=".MainActivity">

    </activity>
</application>
{% endhighlight %}

[Praktikum Android Semester 7: Part 1]({% post_url 2021-02-13-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-02-14-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-02-27-android-sem7-part3 %})
Praktikum Android Semester 7: Part 4