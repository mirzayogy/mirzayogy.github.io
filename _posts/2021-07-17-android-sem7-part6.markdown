---
layout: post
title:  "Praktikum Android Semester 7: Part 6"
date:   2021-03-08 09:00:00 +0800
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

Pada [Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %}) aplikasi sudah dapat menampilkan data dari web service local yang dibangun sendiri menggunakan PHP dengan GET request, pada kali ini akan dijelaskan cara menggunakan `POST request` yang diimplementasikan dalam bentuk Login, dan token hasil login akan disimpan menggunakan `SharedPreferences`.

Pada project PHP folder `api` tambahkan folder `pengguna` dan buat file `pengguna.php` didalamnya, kemudian isikan dengan

{% highlight  php %}
<?php
class Pengguna
{

  private $conn;
  public $id;
  public $username;
  public $password;
  public $namalengkap;
  public $isadmin;

  public function __construct($db)
  {
    $this->conn = $db;
  }

    function find()
    {
        $selectSQL = "SELECT * FROM pengguna WHERE id = ?";
        $stmt = $this->conn->prepare($selectSQL);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        return $stmt;
    }

    function login()
    {

        $this->password = md5($this->password);

        $loginSQL = "SELECT * FROM pengguna WHERE username = ? AND password = ?";
        $stmt = $this->conn->prepare($loginSQL);
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->password);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                return $row['id'];
            }
        }

        return 0;
    }

}
?>
{% endhighlight %}

Masih pada folder yang sama buat file `login.php` dan isikan dengan

{% highlight  php %}
<?php
include_once "../../config/api-header.php";
include_once "pengguna.php";

include_once '../../config/api-core.php';
include_once '../../config/php-jwt-master/src/BeforeValidException.php';
include_once '../../config/php-jwt-master/src/ExpiredException.php';
include_once '../../config/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../config/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

$pengguna = new Pengguna($db);
$data = json_decode(file_get_contents("php://input"));

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if (
    !empty($data->username) &&
    !empty($data->password)
) {
    $pengguna->username = $data->username;
    $pengguna->password = $data->password;
    $pengguna->id = $pengguna->login();
    if ($pengguna->id != 0) {

        $stmt = $pengguna->find();
        $row = $stmt->fetch();
        $token = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer,
            "data" => array(
                "id" => $row["id"],
                "username" => $row["username"],
                "namalengkap" => $row["namalengkap"],
                "isadmin" => $row["isadmin"]
            )
        );

        $jwt = JWT::encode($token, $key);

        http_response_code(200);
        $response["success"] = true;
        $response["data"] = $jwt;
        $response["message"] = "login berhasil";
    } else {
        http_response_code(503);
        $response["message"] = "login gagal";
    }
} else {

    http_response_code(400);
    $response["message"] = "lengkapi data login";
}
echo json_encode($response);
{% endhighlight %}

Terakhir buat file `validate-token.php` dan isikan dengan

{% highlight  php %}

<?php
include_once "../../config/api-header.php";
include_once "pengguna.php";

include_once '../../config/api-core.php';
include_once '../../config/php-jwt-master/src/BeforeValidException.php';
include_once '../../config/php-jwt-master/src/ExpiredException.php';
include_once '../../config/php-jwt-master/src/SignatureInvalidException.php';
include_once '../../config/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

$pengguna = new Pengguna($db);
$data = json_decode(file_get_contents("php://input"));
$jwt = isset($data->jwt) ? $data->jwt : "";

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if ($jwt) {
    try {
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        http_response_code(200);
        $response["success"] = true;
        $response["message"] = "Access granted.";
        $response["data"] = $decoded->data;
    } catch (Exception $e) {
        http_response_code(401);
        $response["message"] = "Access denied.";
    }
} else {
    http_response_code(401);
    $response["message"] = "Access denied.";
}
echo json_encode($response);
?>
{% endhighlight %}

File `login.php` dan `validate-token.php` keduanya menampung data yang di POST kedalam variable $data, dimana pada login data yang ditampung adalah username dan password yang diteruskan untuk proses login dan generate token untuk user. Sedangkan untuk validate-token data yang ditampung adalah jwt atau token dari hasil login, yang akan diperiksa validitasnya, jika valid maka akan direturn data user, jika tidak maka akan direturn bahwa token sudah tidak valid lagi,

Kembali ke Android Studio, pada package model buat class-class berikut

{% highlight  kotlin %}

data class JwtPost(
        val jwt: String
)

data class JwtResponse(
        val success: Boolean,
        val data : PenggunaData,
        val message: String
)

data class LoginPost(
        val username: String,
        val password: String
)

data class LoginResponse(
        val success: Boolean,
        val data : String,
        val message: String
)

data class Pengguna(
    val success: Boolean,
    val data : PenggunaData,
    val message: String
)

data class PenggunaData(
        @field:Json(name = "@id")
        val id: String,
        @field:Json(name = "@username")
        val username: String,
        @field:Json(name = "@namalengkap")
        val namalengkap: String,
        @field:Json(name = "@isadmin")
        val isadmin: String
)

{% endhighlight %}

Pada `network/ApiService` tambahkan method berikut

{% highlight  kotlin %}
@POST("pengguna/login.php")
fun login(@Body loginPost: LoginPost): Call<LoginResponse>
{% endhighlight %}

Buat sebuah empty activity baru didalam package `ui.login` dengan nama `LoginActivity`, buka file `activity_login.xml` dan isikan dengan

{% highlight  xml %}
<?xml version="1.0" encoding="utf-8"?>
<androidx.constraintlayout.widget.ConstraintLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:app="http://schemas.android.com/apk/res-auto"
    xmlns:tools="http://schemas.android.com/tools"
    android:layout_width="match_parent"
    android:layout_height="match_parent"
    tools:context=".ui.login.LoginActivity">

    <ScrollView
        android:layout_width="match_parent"
        android:layout_height="match_parent">

        <LinearLayout
            android:layout_width="match_parent"
            android:layout_height="wrap_content"
            android:layout_gravity="center"
            android:orientation="vertical" >

            <EditText
                android:id="@+id/etUsername"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:hint="Username"
                />

            <EditText
                android:id="@+id/etPassword"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:inputType="textPassword"
                android:hint="Password"
                />

            <Button
                android:id="@+id/btLogin"
                android:layout_width="match_parent"
                android:layout_height="wrap_content"
                android:text="Login"
                />

        </LinearLayout>
    </ScrollView>
</androidx.constraintlayout.widget.ConstraintLayout>
{% endhighlight %}

Pada `LoginActivity` tambahkan

{% highlight  kotlin %}

class LoginActivity : AppCompatActivity() {

    private lateinit var binding: ActivityLoginBinding

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLoginBinding.inflate(layoutInflater)
        setContentView(binding.root)

        binding.btLogin.setOnClickListener{
            val username = binding.etUsername.text.toString()
            val password = binding.etPassword.text.toString()

            val loginPost = LoginPost(username,password)

            doLogin(loginPost)
        }
    }

    private fun doLogin(loginPost: LoginPost) {
        Api.retrofitService.login(loginPost).enqueue(
                object: Callback<LoginResponse> {
                    override fun onFailure(call: Call<LoginResponse>, t: Throwable) {
                        Toast.makeText(applicationContext,"Login gagal", Toast.LENGTH_SHORT).show()
                    }
                    override fun onResponse(
                            call: Call<LoginResponse>,
                            response: Response<LoginResponse>
                    ) {
                        if(response.body()!=null){
                            Toast.makeText(applicationContext, response.body()!!.message, Toast.LENGTH_SHORT).show()
                        }else{
                            Toast.makeText(applicationContext, "Username/Password Salah", Toast.LENGTH_SHORT).show()
                        }
                    }
                }
        )
    }
}

{% endhighlight %}



[Praktikum Android Semester 7: Part 1]({% post_url 2021-07-17-android-sem7-part1 %})
[Praktikum Android Semester 7: Part 2]({% post_url 2021-07-17-android-sem7-part2 %})
[Praktikum Android Semester 7: Part 3]({% post_url 2021-07-17-android-sem7-part3 %})
[Praktikum Android Semester 7: Part 4]({% post_url 2021-07-17-android-sem7-part4 %})
[Praktikum Android Semester 7: Part 5]({% post_url 2021-07-17-android-sem7-part5 %})