---
layout: post
title:  "Praktikum Android Semester 7: Part 6"
date:   2021-07-17 09:40:00 +0800
categories: android
published: true
comments : true
description: Membuat login backend, login android, dan simpan token pada SharedPreferences
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


Pada [Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %}) aplikasi sudah mampu menyimpan data dengan method POST, tapi recyclerview belum direfresh secara otomatis. Selain itu activity JenisbarangPost hanya bisa menyimpan untuk proses simpan tambah data, sedangkan nantinya akan digunakan juga untuk proses simpan ubah data, sehingga perlu dipisahkan fungsinya.

Agar bisa direfresh dengan baik maka method `getListJenisbarang()` pada `JenisbarangActivity` perlu ditambahkan method `list.clear()` sebelum `list.addAll(it.data)`
{% highlight  kotlin %}
private fun getListJenisbarang() {
    viewModel.getJenisbarang()
    viewModel.response.observe(this, {
        binding.progressBarJenisbarang.visibility = View.INVISIBLE
        list.clear()
        list.addAll(it.data)
        binding.rvJenisbarang.layoutManager = LinearLayoutManager(this)
        val listJenisbarangAdapter = ListJenisbarangAdapter(list)
        binding.rvJenisbarang.adapter = listJenisbarangAdapter
    })
}
{% endhighlight %}

Berikutnya, karena onActivityResult sudah deprecated, maka kita gunakan penggantinya dengan menggunakan  `ActivityResultContracts`. Tambahkan perintah berikut pada JenisbarangActivity

{% highlight  kotlin %}
private val getContract = registerForActivityResult(ActivityResultContracts.StartActivityForResult()){
    if(it.resultCode == RESULT_OK) {
        getListJenisbarang()
    }
}
{% endhighlight %}

pada `btTambahJenisbarang.setOnClickListener` yang berfungsi untuk memanggil JenisbarangPostActivity ditambahkan extra STATUS yang berisikan TAMBAH dan `startActivity` diganti dengan `getContract.launch(intent)`

{% highlight  kotlin %}
binding.btTambahJenisbarang.setOnClickListener{
    val intent = Intent(this, JenisbarangPostActivity::class.java)
    intent.putExtra("STATUS","TAMBAH")
    getContract.launch(intent)
}
{% endhighlight %}

Pada `JenisbarangPostActivity` ditambahkan perintah untuk mengambil extra status, dan dengan extra tersebut bisa ditentukan jika statusnya adalah TAMBAH daha maka, id disembunyikan, sedangkan lainnya bisa dimunculkan

{% highlight  kotlin %}
val status = intent.getStringExtra("STATUS")
if(status=="TAMBAH"){
    binding.etIdJenisbarang.visibility  = View.GONE
} else {
    binding.etIdJenisbarang.visibility  = View.VISIBLE
}
binding.etNamajenisbarang.requestFocus()
{% endhighlight %}

Pada `btSimpanJenisbarangPost.setOnClickListener` dibuat percabangan untuk memilah kapan dilakukan simpan TAMBAH dan kapan dilakukan simpan UBAH, ditambahkan juga `setResult(RESULT_OK)` untuk memberitahu activity JenisbarangActivity bahwa simpan data berhasil dilakukan. Perintah `finish()` juga ditambahkan agar JenisbarangPostActivity ditutup setelah simpan data berhasil dilakukan.

{% highlight  kotlin %}
binding.btSimpanJenisbarangPost.setOnClickListener{

    val idJenisbarang = binding.etIdJenisbarang.text.toString()
    val namaJenisBarang = binding.etNamajenisbarang.text.toString()
    val jenisBarangData = JenisbarangData(idJenisbarang,namaJenisBarang)

    binding.progressBarJenisbarangPost.visibility = View.VISIBLE
    binding.btSimpanJenisbarangPost.visibility = View.INVISIBLE

    if(status == "TAMBAH"){
        viewModel.create(jenisBarangData)
        viewModel.createResponse.observe(this,{
            binding.progressBarJenisbarangPost.visibility = View.INVISIBLE
            binding.btSimpanJenisbarangPost.visibility = View.VISIBLE
            Toast.makeText(this, it.body()?.message,Toast.LENGTH_SHORT).show()
            setResult(RESULT_OK)
            finish()
        })
    }
}
{% endhighlight %}


[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
[Praktikum Android Semester 7: Part 6]({% post_url 2021-07-17-android-sem7-part6 %})