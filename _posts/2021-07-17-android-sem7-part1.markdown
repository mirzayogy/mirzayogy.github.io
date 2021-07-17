---
layout: post
title:  "Praktikum Android Semester 7: Part 1"
date:   2021-07-17 08:00:00 +0800
categories: android
comments : true
description: Melakukan request data ke web service menggunakan retrofit dan moshi dengan penerapan viewmodel
tags: 
 - android 
 - retrofit
 - moshi
 - viewmodel
 - 201-praktikum-7-android
---

[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})

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

Dan tentu masih banyak Web Service lainnya yang bisa ditemui, seperti untuk pengiriman paket, pemesanan tiket, dan lainnya.

Pada part 1 ini akan digunakan web service yang sederhana dulu agar bisa dipraktekkan cara menghubungkan aplikasi android dengan web service yang sudah ada

Buat project baru pada Android Studio

tambahkan pada `build.graddle.app`
{% highlight  xml %}
    ...
    android{
        ...
        buildFeatures {
        viewBinding = true
        }
    }
    ...
    dependencies{
        ...
        implementation "com.squareup.retrofit2:retrofit:2.9.0"
        implementation "com.squareup.retrofit2:converter-moshi:2.9.0"
        implementation "com.squareup.moshi:moshi-kotlin:1.9.3"
        implementation "androidx.lifecycle:lifecycle-viewmodel-ktx:2.3.1"
    }
{% endhighlight %}

`Retrofit` ini adalah HTTP client yang bisa digunakan untuk melakukan request, singkatnya untuk memanggil internet. Sedangkan `Moshi` digunakan sebagai penerjemah JSON yang didapat dari hasil request. Kedua dependency tadi dihubungkan melalui `retrofit:converter-moshi`. `Androidx Lifecycle` digunakan untuk mengimplementasikan `ViewModel` pada project kali ini. Jangan lupa untuk melakukan sync dengan klik `Sync Now`.

Project ini akan melakukan HTTP request atau menghubungkan diri dengan internet, maka perlu ditambahkan permission pada `AndroidManifest.xml`
{% highlight  xml %}
...
<uses-permission android:name="android.permission.INTERNET" />

<application

{% endhighlight %}

Web service yang digunakan adalah link berikut

    https://reqres.in/api/users?page=1

Link tersebut bisa dibuka menggunakan web browser biasa. Firefox sudah support untuk pembacaan JSON, Chrome perlu extensi (contoh: "JSON Formatter") agar bisa menyajikan JSON dengan rapi, atau untuk lebih detail dan proses pengujian lebih lanjut bisa digunakan <a href="https://www.postman.com/downloads/" target="_blank">Postman</a> yang mana sekarang tersedia juga versi web-nya

Berikut contoh hasil request ke `reqres.in`
{% highlight  JSON %}
{
    "page":1,
    "per_page":6,
    "total":12,
    "total_pages":2,
    "data":[
        {
            "id":1,
            "email":"george.bluth@reqres.in",
            "first_name":"George",
            "last_name":"Bluth",
            "avatar":"https://reqres.in/img/faces/1-image.jpg"
        },
        {
            "id":2,
            "email":"janet.weaver@reqres.in",
            "first_name":"Janet",
            "last_name":"Weaver",
            "avatar":"https://reqres.in/img/faces/2-image.jpg"
        },
        {
            "id":3,
            "email":"emma.wong@reqres.in",
            "first_name":"Emma",
            "last_name":"Wong",
            "avatar":"https://reqres.in/img/faces/3-image.jpg"
        },
        {
            "id":4,
            "email":"eve.holt@reqres.in",
            "first_name":"Eve",
            "last_name":"Holt",
            "avatar":"https://reqres.in/img/faces/4-image.jpg"
        },
        {
            "id":5,
            "email":"charles.morris@reqres.in",
            "first_name":"Charles",
            "last_name":"Morris",
            "avatar":"https://reqres.in/img/faces/5-image.jpg"
        },
        {
            "id":6,
            "email":"tracey.ramos@reqres.in",
            "first_name":"Tracey",
            "last_name":"Ramos",
            "avatar":"https://reqres.in/img/faces/6-image.jpg"
        }
    ],
    "support":
        {
            "url":"https://reqres.in/#support-heading",
            "text":"To keep ReqRes free, contributions towards server costs are appreciated!"
        }
}
{% endhighlight %}

Data tersebut akan diterjemahkan dalam bentuk object pada Android Studio dengan membuat Class yang strukturnya mirip.

Buat package `model` kemudian didalamnya buat sebuah data class dengan nama `User` dan isikan dengan

{% highlight  kotlin %}

data class User(
    val page: Int,
    val per_page: Int,
    val total: Int,
    val total_pages: Int,
    val data : List<UserData>
)

data class UserData(
    @field:Json(name = "@id")
    val id: Int,
    @field:Json(name = "@email")
    val email: String,
    @field:Json(name = "@first_name")
    val first_name: String,
    @field:Json(name = "@last_name")
    val last_name: String,
    @field:Json(name = "@avatar")
    val avatar: String
)
{% endhighlight %}

Berikutnya diperlukan sebuah interface untuk melakukan request menggunakan retrofit. Buat package `network` kemudian didalamnya buat sebuah interface dengan nama `ApiService` dan isikan dengan

{% highlight  kotlin %}

private const val BASE_URL = "https://reqres.in/api/"

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
}

object Api {
    val retrofitService : ApiService by lazy {
        retrofit.create(ApiService::class.java) }
}
{% endhighlight %}

`BASE_URL` merupakan link utama yang akan di-request, sedangkan parameter detailnya ditambahkan pada methof `getUsers()` bisa diperhatikan pada `@GET("users?page=1")` yang merupakan argumen tambahan kepada BASE_URL

Data hasil request akan diproses dalam sebuah `ViewModel` untuk dapat ditampilkan kedalam activity, praktek ini digunakan agar data tetap tersimpan ketika state berubah dan memungkinkan untuk dilakukan unit testing terhadap request datanya. Buat package `ui` didalamnya buat lagi package `user` didalamnya buat sebuah class `UserViewModel` dan isikan dengan

{% highlight  kotlin %}
class UserViewModel :ViewModel() {
    val response = MutableLiveData<User>()

    fun getUsers() {
        viewModelScope.launch {
            try {
                val listResult = Api.retrofitService.getUsers()
                response.value = listResult
            } catch (e: Exception) {
                response.value = null
            }
        }
    }
}
{% endhighlight %}

class `UserViewModel` merupakan extends atau turunan atau subclass dari `ViewModel`, didalamnya terdapat atribut `response` dengan tipe MutableLiveData dengan isi object dari User. Method `getusers()` akan dijalankan pada MainActivity, yang mana method tersebut beriskan CoroutineScope dengan try-catch proses request data menggunakan retrofit dan hasilnya disimpan di `response`.

Pengujian cepat untuk melihat hasil request bisa dilakukan sementara pada `MainActivity` dengan ditambahkan sedikit modifikasi terlebih dahulu pada layout `activity_main.xml`
{% highlight  xml %}
...
    <TextView
        android:id="@+id/tvMainActivity"
        ...
        />

    <ProgressBar
        android:id="@+id/progressBar"
        style="?android:attr/progressBarStyle"
        android:layout_width="wrap_content"
        android:layout_height="wrap_content"
        app:layout_constraintBottom_toBottomOf="parent"
        app:layout_constraintEnd_toEndOf="parent"
        app:layout_constraintStart_toStartOf="parent"
        app:layout_constraintTop_toTopOf="parent" />
...

{% endhighlight %}

Modifikasi dilakukan dengan menambahkan id pada TextView yang sudah ada, dan menambahkan `ProgressBar`. Sedangkan modifikasi pada `MainActivity` adalah sebagai berikut

{% highlight  kotlin %}
...
class MainActivity : AppCompatActivity() {

    private lateinit var binding:ActivityMainBinding
    private val viewModel: UserViewModel by lazy {
        ViewModelProvider(this).get(UserViewModel::class.java)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.progressBar.visibility = View.VISIBLE

        viewModel.getUsers()
        viewModel.response.observe(this, {
            binding.progressBar.visibility = View.INVISIBLE
            binding.tvMainActivity.text = it.data[0].first_name
        })

    }
}
{% endhighlight %}

Project ini menggunakan teknik viewBinding sehingga perlu dibuat dulu sebuah atribut `binding` dengan tipe ActivityMainBinding dan parameter `setContentView` pada onCreate dirubah menyesuaikan dengan binding, atribut `viewModel` dibuat dengan tipe UserViewModel. Ketika dijalankan aplikasi akan melakukan request data kemudian menampilkan data pertama dari data user yang ada kedalam TextView. 

OK Proses request data berhasil.

[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})
