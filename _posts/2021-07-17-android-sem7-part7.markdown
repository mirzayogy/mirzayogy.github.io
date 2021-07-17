---
layout: post
title:  "Praktikum Android Semester 7: Part 7"
date:   2021-07-17 09:50:00 +0800
categories: android
published: true
comments : true
description: Membuat delete
tags: 
 - android
 - php
 - onActivityResult
 - ActivityResultContracts
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
[Praktikum Android Semester 7: Part 6]({% post_url 2021-07-17-android-sem7-part6 %})
[Praktikum Android Semester 7: Part 7]({% post_url 2021-07-17-android-sem7-part7 %})

Pada button edit dan delete diperlukan gambar untuk memperkecil ukuran tombol, ketimbang harus menuliskan kata 'EDIT' dan 'DELETE' yang akan banyak menghabiskan ruang. Menambahkan gambar icon bisa menggunakan `Vector Asset` yang sudah disediakan Android Studio. 

Pilih package `res/drawable`, klik kanan `New` -> `Vector Asset`, klik tombol pada `Clip Art` dan cari icon untuk edit, klik OK -> Next -> Finish. Ulangi cara yang sama untuk icon delete.

Masuk pada layout `item_row_jenisbarang`, akan ditambahkan tombol pada layout ini

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<LinearLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="wrap_content"
    android:orientation="horizontal"
    android:padding="16dp">

    <TextView
        android:id="@+id/tvNamaJenisbarang"
        android:layout_width="0dp"
        android:layout_height="wrap_content"
        android:layout_weight="1"
        android:layout_marginBottom="4dp"
        android:textSize="16sp"
        android:textStyle="bold"
        tools:text="Name" />


        <ImageButton
            android:id="@+id/btnEdit"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:src="@drawable/ic_baseline_edit_24"
            android:backgroundTint="@android:color/holo_green_light"
            />
        <ImageButton
            android:id="@+id/btnDelete"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:src="@drawable/ic_baseline_delete_24"
            android:backgroundTint="@android:color/holo_red_light"
            />
</LinearLayout>
{% endhighlight %}

sesuaikan atribut `src` pada `ImageButton` dengan nama icon yang sudah dibuat sebelumnya.

Kembali ke PHP, buka file `jenisbarang.php` tambahkan method

{% highlight  php %}
function delete()
{
    $deleteSQL = "DELETE FROM jenisbarang WHERE id = ?";
    $stmt = $this->conn->prepare($deleteSQL);
    $stmt->bindParam(1, $this->id);
    return $stmt->execute() ? true : false;
}
{% endhighlight %}

Buat sebuah file `delete.php`  didalam folder `jenisbarang` dan isikan dengan

{% highlight  php %}

<?php
include_once "../../config/api-header.php";
include_once 'jenisbarang.php';

$jenisbarang = new Jenisbarang($db);
$data = json_decode(file_get_contents("php://input"));

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if (
    !empty($data->id)
) {
    $jenisbarang->id = $data->id;
    $stmt = $jenisbarang->find();
    $row = $stmt->fetch();
    $response["data"] = array(
        "id" => $row["id"],
        "namajenisbarang" => $row["namajenisbarang"]
    );
    if ($jenisbarang->delete()) {
        http_response_code(201);
        $response["success"] = true;
        $response["message"] = "delete data jenisbarang berhasil";
    } else {
        http_response_code(503);
        $response["message"] = "delete data jenisbarang gagal";
    }
} else {
    http_response_code(400);
    $response["message"] = "lengkapi jenisbarang";
}
echo json_encode($response);
{% endhighlight %}


Kembali ke Android Studio, tambahkan pada `ApiService` sebuah method

{% highlight  kotlin %}
@POST("jenisbarang/delete.php")
fun delete(@Body jenisbarangData: JenisbarangData): Call<JenisbarangResponse>
{% endhighlight %}

Buka ListJenisbarangAdapter, disini akan ditambahkan perintah untuk hapus data dengan didahului pertanyaan konfirmasi penghapusan data

{% highlight  kotlin %}
btnDelete.setOnClickListener {
    AlertDialog.Builder(binding.root.context)
        .setMessage("Data akan dihapus, lanjutkan?")
        .setCancelable(false)
        .setPositiveButton("Ya") { dialog, _ ->

            Api.retrofitService.delete(jenisbarangData)
                .enqueue(object :
                    Callback<JenisbarangResponse> {
                    override fun onResponse(
                        call: Call<JenisbarangResponse>,
                        response: Response<JenisbarangResponse>
                    ) {
                        Toast.makeText(
                            binding.root.context, response.body()?.message,
                            Toast.LENGTH_SHORT
                        ).show()
                        listJenisbarang.removeAt(bindingAdapterPosition)
                        bindingAdapter?.notifyItemRemoved(bindingAdapterPosition)
                    }

                    override fun onFailure(
                        call: Call<JenisbarangResponse>,
                        t: Throwable
                    ) {
                        Toast.makeText(
                            binding.root.context,
                            t.message,
                            Toast.LENGTH_SHORT
                        ).show()
                    }
                })
        }
        .setNegativeButton("Tidak") { dialog, _ ->
            dialog.dismiss()
        }
        .show()
}
{% endhighlight %}


[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
[Praktikum Android Semester 7: Part 6]({% post_url 2021-07-17-android-sem7-part6 %})
[Praktikum Android Semester 7: Part 7]({% post_url 2021-07-17-android-sem7-part7 %})