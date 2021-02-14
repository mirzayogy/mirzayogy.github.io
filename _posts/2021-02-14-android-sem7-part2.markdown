---
layout: post
title:  "Praktikum Android Semester 7: Part 2"
date:   2021-02-14 09:00:00 +0800
categories: android
published: false
comments : true
description: Langkah-langkah pengerjaan Praktikum Android khusus untuk semester 7 Fakultas Teknologi Informasi, Universitas Islam Kalimantan Muhammad Arsyad Al Banjari Banjarmasin
tags: 
 - android
 - retrofit
 - moshi
 - viewmodel
 - recyclerview
 - 201-praktikum-7-android
---

[Part 1]({% post_url 2021-02-13-android-sem7-part1 %})

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
        android:id="@+id/tv_item_last_name"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_marginBottom="8dp"
        android:textSize="16sp"
        android:textStyle="bold"
        tools:text="Last Name" />

    <TextView
        android:id="@+id/tv_item_fist_name"
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:ellipsize="end"
        android:maxLines="2"
        tools:text="First Name" />
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
class ListUserAdapter(private val listUser: ArrayList<User>) : RecyclerView.Adapter<ListUserAdapter.ListViewHolder>() {
    class ListViewHolder(private val binding: ItemRowUserBinding) : RecyclerView.ViewHolder(binding.root) {

    }
}
{% endhighlight %}

Import dan implementasikan semua method yang diminta oleh interface Adapter RecyclerView. Masih pada file `ListUserAdapter`, tambahkan method `bind` di dalam inner class `ListViewHolder

{% highlight  kotlin %}
class ListViewHolder(private val binding: ItemRowUserBinding) : RecyclerView.ViewHolder(binding.root) {
    fun bind(userData: UserData) {
        with(binding){
            tvItemLastName.text = userData.last_name
            tvItemFirstName.text = userData.first_name
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

Berikutnya diperlukan sebuah interface untuk melakukan request menggunakan retrofit. Buat package `network` kemudian didalamnya buat sebuah class dengan nama `ApiService` dan isikan dengan

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
    private val _response = MutableLiveData<User>()

    val response: LiveData<User>
        get() = _response

    init {
        setResponse()
    }

    private fun setResponse() {
        viewModelScope.launch {
            try {
                val listResult = Api.retrofitService.getUsers()
                _response.value = listResult
            } catch (e: Exception) {
                _response.value = null
            }
        }
    }
}
{% endhighlight %}

class `UserViewModel` merupakan extends atau turunan atau subclass dari `ViewModel`, didalamnya terdapat atribut `_response` dengan tipe MutableLiveData dengan isi object dari User. Sedangkan `response` dengan tipe LiveData digunakan sebagai interface yang ditampilkan pada activity. Method `setResponse` dijalankan pada `init` atau ketika ViewModel di-instance, yang mana method tersebut beriskan CoroutineScope dengan try-catch proses request data menggunakan retrofit dan hasilnya disimpan di `_response`.

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

        viewModel.response.observe(this, {
            binding.progressBar.visibility = View.INVISIBLE
            binding.tvMainActivity.text = it.data[0].first_name
        })

    }
}
{% endhighlight %}

Project ini menggunakan teknik viewBinding sehingga perlu dibuat dulu sebuah atribut `binding` dengan tipe ActivityMainBinding dan parameter `setContentView` pada onCreate dirubah menyesuaikan dengan binding, atribut `viewModel` dibuat dengan tipe UserViewModel. Ketika dijalankan aplikasi akan melakukan request data kemudian menampilkan data pertama dari data user yang ada kedalam TextView. 

OK Proses request data berhasil.