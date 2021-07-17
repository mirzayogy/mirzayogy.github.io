---
layout: post
title:  "Praktikum Android Semester 7: Part 5"
date:   2021-07-17 09:30:00 +0800
categories: android
published: true
comments : true
description: Membuat login backend, login android, dan simpan token pada SharedPreferences
tags: 
 - android
 - php
 - SharedPreferences
 - login
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
[Praktikum Android Semester 7: Part 6]({% post_url 2021-07-17-android-sem7-part6 %})
[Praktikum Android Semester 7: Part 7]({% post_url 2021-07-17-android-sem7-part7 %})

Pada [Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %}) aplikasi sudah dapat menampilkan data dari web service local yang dibangun sendiri menggunakan PHP dengan GET request, pada kali ini akan dijelaskan cara menggunakan `POST request` yang diimplementasikan dalam bentuk Tambah Data.

Pada project PHP file `jenisbarang.php` tambahkan function berikut

{% highlight  php %}
<?php
function find()
{
    $selectSQL = "SELECT * FROM jenisbarang WHERE id = ?";
    $stmt = $this->conn->prepare($selectSQL);
    $stmt->bindParam(1, $this->id);
    $stmt->execute();

    return $stmt;
}

function create()
{
    $insertSQL = "INSERT INTO jenisbarang VALUES (NULL, ?)";
    $stmt = $this->conn->prepare($insertSQL);
    $stmt->bindParam(1, $this->namajenisbarang);

    if ($stmt->execute()) {
        return $this->conn->lastInsertId();
    }

    return 0;
}

?>
{% endhighlight %}

Buat file `create.php` pada folder yang sama dengan `read.php` dan isikan dengan

{% highlight  php %}

<?php
include_once "../../config/api-header.php";
include_once "jenisbarang.php";

$jenisbarang = new Jenisbarang($db);
$data = json_decode(file_get_contents("php://input"));

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if (
    !empty($data->namajenisbarang)
) {

    $jenisbarang->namajenisbarang = $data->namajenisbarang;
    $jenisbarang->id = $jenisbarang->create();

    if ($jenisbarang->id != 0) {
        $stmt = $jenisbarang->find();
        $row = $stmt->fetch();
        $response["data"] = array(
            "id" => $row["id"],
            "namajenisbarang" => $row["namajenisbarang"]
        );

        http_response_code(201);
        $response["success"] = true;
        $response["message"] = "create data jenisbarang berhasil";
    } else {
        http_response_code(503);
        $response["message"] = "create data jenisbarang gagal";
    }
} else {
    http_response_code(400);
    $response["message"] = "lengkapi data jenisbarang";
}

echo json_encode($response);

{% endhighlight %}


Pada `Android Studio` buat sebuah activity baru, bisa diberikan nama `JenisbarangPostActivity` dan pada layout xml-nya isikan dengan


{% highlight  xml %}

<?xml version="1.0" encoding="utf-8"?>
<androidx.constraintlayout.widget.ConstraintLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    tools:context=".ui.jenisbarang.JenisbarangPostActivity">

    <ScrollView
        android:layout_width="match_parent"
        android:layout_height="match_parent"
        tools:layout_editor_absoluteX="188dp"
        tools:layout_editor_absoluteY="221dp">

        <LinearLayout
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:orientation="vertical" >

            <EditText
                android:id="@+id/etIdJenisbarang"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:text="0"
                android:hint="Id"
                android:ems="10"
                android:inputType="number" />

            <EditText
                android:id="@+id/etNamajenisbarang"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:hint="Nama Jenis Barang"
                android:ems="10"
                android:inputType="text" />

            <Button
                android:id="@+id/btSimpanJenisbarangPost"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:text="Simpan" />

            <ProgressBar
                android:id="@+id/progressBarJenisbarangPost"
                style="?android:attr/progressBarStyle"
                android:layout_width="match_parent"
                android:layout_height="wrap_content" />
        </LinearLayout>
    </ScrollView>
</androidx.constraintlayout.widget.ConstraintLayout>

{% endhighlight %}

Pada model `Jenisbarang` tambahkan

{% highlight  kotlin %}

data class JenisbarangResponse(
    val success: Boolean,
    val data : JenisbarangData,
    val message: String
)

{% endhighlight %}

Data class ini berfungsi untuk menampung response dari hasil post data.

Pada interface `ApiService` tambahkan function yang bernama create yang memiliki return value berupa `JenisbarangResponse`, bisa ditulis seperti berikut

{% highlight  kotlin %}

@POST("jenisbarang/create.php")
suspend fun create(@Body jenisbarangData: JenisbarangData): Response<JenisbarangResponse>

{% endhighlight %}

Pada `JenisbarangViewModel` tambahkan atribut
{% highlight  kotlin %}
val createResponse = MutableLiveData<Response<JenisbarangResponse>>()
{% endhighlight %}


Masih pada `JenisbarangViewModel` tambahkan function yang menggunakan `create` pada ApiService, bisa ditulis seperti berikut

{% highlight  kotlin %}
fun create(jenisbarangData: JenisbarangData) {
    viewModelScope.launch {
        val response = Api.retrofitService.create(jenisbarangData)
        createResponse.value = response
    }
}

{% endhighlight %}

Dengan function yang dibutuhkan sudah dibuat, bisa dilanjutkan dengan mengisi `JenisbarangPostActivity`, bisa ditulis seperti berikut

{% highlight  kotlin %}
class JenisbarangPostActivity : AppCompatActivity() {

    private lateinit var binding: ActivityJenisbarangPostBinding
    private val viewModel: JenisbarangViewModel by lazy {
        ViewModelProvider(this).get(JenisbarangViewModel::class.java)
    }
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityJenisbarangPostBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.progressBarJenisbarangPost.visibility = View.INVISIBLE
        binding.btSimpanJenisbarangPost.visibility = View.VISIBLE
        binding.btSimpanJenisbarangPost.setOnClickListener{

            val idJenisbarang = binding.etIdJenisbarang.text.toString()
            val namaJenisBarang = binding.etNamajenisbarang.text.toString()
            val jenisBarangData = JenisbarangData(idJenisbarang,namaJenisBarang)

            binding.progressBarJenisbarangPost.visibility = View.VISIBLE
            binding.btSimpanJenisbarangPost.visibility = View.INVISIBLE

            viewModel.create(jenisBarangData)
            viewModel.createResponse.observe(this,{
                binding.progressBarJenisbarangPost.visibility = View.INVISIBLE
                binding.btSimpanJenisbarangPost.visibility = View.VISIBLE
                Toast.makeText(this, it.body()?.message,Toast.LENGTH_SHORT).show()
            })
        }
    }
}
{% endhighlight %}

Selanjutnya dibuat sebuah tombol untuk dapat membuka `JenisbarangPostActivity`, bisa ditambahkan dengan membuka xml dari `activity_jenisbarang.xml` dan tambahkan

{% highlight  xml %}
<Button
    android:id="@+id/btTambahJenisbarang"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:text="Tambah"
    />
{% endhighlight %}

Buat method `setOnClickListener` untuk `btTambahJenisbarang` pada `JenisbarangActivity`

{% highlight  kotlin %}

binding.btTambahJenisbarang.setOnClickListener{
    val intent = Intent(this, JenisbarangPostActivity::class.java)
    startActivity(intent)
}
{% endhighlight %}



[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
[Praktikum Android Semester 7: Part 6]({% post_url 2021-07-17-android-sem7-part6 %})
[Praktikum Android Semester 7: Part 7]({% post_url 2021-07-17-android-sem7-part7 %})
