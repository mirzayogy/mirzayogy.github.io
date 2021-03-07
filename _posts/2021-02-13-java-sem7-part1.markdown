---
layout: post
title:  "Praktikum Java Semester 7: Part 1"
date:   2021-02-13 12:46:41 +0800
categories: java
published : true
comments : true
description: Langkah-langkah pengerjaan praktikum Java khusus untuk semester 7 Fakultas Teknologi Informasi, Universitas Islam Kalimantan Muhammad Arsyad Al Banjari Banjarmasin
tags: 
 - java
 - netbeans
 - 201-praktikum-7-java
---

Praktikum Java Semester 7: Part 1
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
[Praktikum Java Semester 7: Part 4]({% post_url 2021-03-07-java-sem7-part4 %})
[Praktikum Java Semester 7: Part 5]({% post_url 2021-03-07-java-sem7-part5 %})



Use Case Diagram untuk praktikum ini

![UseCaseDiagram]({{ site.url }}/assets/UseCaseDiagram.png)

Class Diagram untuk praktikum ini

![ClassDiagram]({{ site.url }}/assets/ClassDiagram.png)

Tools yang digunakan
1. IDE: NetBeans 12.+
2. MySQL
3. GitHub Desktop

Buat sebuah database baru di MySQL dengan nama `praktikum_penjualan`, pilih database-nya, buka menu SQL dan jalankan perintah berikut

{% highlight  sql %}
CREATE TABLE `jenisbarang` (
`id` INT NOT NULL AUTO_INCREMENT ,
`namajenisbarang` VARCHAR(200) NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;

INSERT INTO `jenisbarang` (`id`, `namajenisbarang`) VALUES 
(NULL, 'Pakaian pria'), 
(NULL, 'Pakaian wanita') 

{% endhighlight %}

Buat Project Baru, Category: `Java with Maven`, Projects: `Java Application`

Buka package `Project Files` buka file `pom.xml` posisikan kursor setelah `</properties>` dan isikan dengan

{% highlight  xml %}
<project ..>
    ...
    </properties>
    <dependencies>
        <!-- https://mvnrepository.com/artifact/mysql/mysql-connector-java -->
        <dependency>
            <groupId>mysql</groupId>
            <artifactId>mysql-connector-java</artifactId>
            <version>8.0.23</version>
        </dependency>
    </dependencies>
</project>
{% endhighlight %}

Buat package `db` dan didalamnya buat class `Database`, isikan dengan

{% highlight  java %}
public class Database {
    
    private final String URL = "jdbc:mysql://localhost:3306/";
    private final String DB_NAME = "praktikum_penjualan";
    private final String USER = "root";
    private final String PASS = "";
    
    public Connection getConnection(){
        Connection con = null;
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            con = DriverManager.getConnection(URL+DB_NAME, USER, PASS);
            System.out.println("Koneksi Berhasil");
        } catch (ClassNotFoundException | SQLException ex) {
            System.out.println(ex.toString());
        }
        return con;
    }
    
}
{% endhighlight %}

Buat class `Main`, isikan dengan

{% highlight  java %}
public class Main {
    public static void main(String[] args) {
        System.out.println("Hello world");
        
        Database db = new Database();
        Connection con = db.getConnection();
    }
}
{% endhighlight %}

Hasil yang diharapkan adalah tertulis `Hello world` dan `Koneksi Berhasil`

Buat package `template` dan didalamnya buat interface `MyModelInterface`, isikan dengan

{% highlight  java %}
public interface MyModelInterface {
    
    abstract boolean create();
    abstract ArrayList<Object> read();
    abstract boolean update();
    abstract boolean delete();
    abstract ArrayList<Object> search(String keyword);
    
}
{% endhighlight %}

Buat package `model` dan didalamnya buat class `JenisBarang`, isikan dengan

{% highlight  java %}
public class JenisBarang implements MyModelInterface{

    Connection con;
    private int id;
    private String namaJenisBarang;

}
{% endhighlight %}

Perhatikan lampu peringatan, klik dan pilih `Implement all abstract methods` sehingga akan muncul method CRUD sesuai dengan interface yang sudah dibuat sebelumnya.

Generate constructor JenisBarang dengan parameter con dan satu constructor lagi dengan parameter id dan namaJenisBarang. Generate juga Getter and Setter untuk setiap atribut.

Cari method create() dan isikan dengan

{% highlight  java %}
@Override
public boolean create() {
    boolean berhasil = false;
    
    String insertSQL = "INSERT INTO jenisbarang VALUES (NULL,?)";
    
    try {
        PreparedStatement ps = this.con.prepareStatement(insertSQL);
        ps.setString(1, this.namaJenisBarang);
        ps.execute();
        berhasil = true;
    } catch (SQLException ex) {
        System.out.println(ex.toString());
    }
    
    return berhasil;
}
{% endhighlight %}

Cari method read() dan isikan dengan

{% highlight  java %}

@Override
public ArrayList<Object> read() {
    ArrayList<Object> list =  new ArrayList<>();
    
    String selectSQL = "SELECT * FROM jenisbarang";
    
    try {
        Statement statement = this.con.createStatement();
        ResultSet resultSet = statement.executeQuery(selectSQL);
        
        while(resultSet.next()){
            JenisBarang jb = new JenisBarang(
                    resultSet.getInt(1),
                    resultSet.getString(2)
            );
            list.add(jb);
        }
        
    } catch (SQLException ex) {
        System.out.println(ex.toString());
    }
    
    return list;
}
{% endhighlight %}

Cari method update() dan isikan dengan

{% highlight  java %}
@Override
public boolean update() {
    
    boolean berhasil = false;
    
    String updateSQL = "UPDATE jenisbarang SET namajenisbarang = ? WHERE id = ?";
    
    try {
        PreparedStatement ps = this.con.prepareStatement(updateSQL);
        ps.setString(1, this.namaJenisBarang);
        ps.setInt(2, this.id);

        ps.execute();
        berhasil = true;
    } catch (SQLException ex) {
        System.out.println(ex.toString());
    }
    
    return berhasil;
}
{% endhighlight %}

Cari method update() dan isikan dengan

{% highlight  java %}
@Override
public boolean delete() {
    boolean berhasil = false;
    
    String deleteSQL = "DELETE FROM jenisbarang WHERE id = ?";
    
    try {
        PreparedStatement ps = this.con.prepareStatement(deleteSQL);
        ps.setInt(1, this.id);

        ps.execute();
        berhasil = true;
    } catch (SQLException ex) {
        System.out.println(ex.toString());
    }
    
    return berhasil;
}
{% endhighlight %}

Cari method search() dan isikan dengan

{% highlight  java %}
@Override
public ArrayList<Object> search(String keyword) {
    ArrayList<Object> list =  new ArrayList<>();
    
    String searchSQL = "SELECT * FROM jenisbarang WHERE namajenisbarang like ?";
    
    keyword = "%"+keyword+"%";
    
    try {
        PreparedStatement ps = this.con.prepareStatement(searchSQL);
        ps.setString(1, keyword);
        ResultSet resultSet = ps.executeQuery();
        
        while(resultSet.next()){
            JenisBarang jb = new JenisBarang(
                    resultSet.getInt(1),
                    resultSet.getString(2)
            );
            list.add(jb);
        }
        
    } catch (SQLException ex) {
        System.out.println(ex.toString());
    }
    
    return list;
}
{% endhighlight %}

And... that's about it for today. Aplikasi sudah mampu melakukan CRUD sederhana namun masih tanpa view visual.

Praktikum Java Semester 7: Part 1
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
[Praktikum Java Semester 7: Part 4]({% post_url 2021-03-07-java-sem7-part4 %})
[Praktikum Java Semester 7: Part 5]({% post_url 2021-03-07-java-sem7-part5 %})


<iframe width="560" height="315" src="https://www.youtube.com/embed/FNS_zA36qtY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>