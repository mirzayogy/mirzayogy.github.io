---
layout: post
title:  "Praktikum Java Semester 7: Part 4"
date:   2021-03-07 09:00:41 +0800
categories: java
published : true
comments : true
description: Proses Login
tags: 
 - java
 - netbeans
 - login
 - 201-praktikum-7-java
---
[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
Praktikum Java Semester 7: Part 4

Buat package `utils` buat class `MD5` dan isikan dengan

{% highlight  java %}
public class MD5 {
    public static String getMd5(String input) 
    { 
        try { 
            
            MessageDigest md = MessageDigest.getInstance("MD5"); 
            byte[] messageDigest = md.digest(input.getBytes()); 
            BigInteger no = new BigInteger(1, messageDigest); 

            String hashtext = no.toString(16); 
            while (hashtext.length() < 32) { 
                hashtext = "0" + hashtext; 
            } 
            return hashtext; 
        }  
  
        catch (NoSuchAlgorithmException e) { 
            throw new RuntimeException(e); 
        } 
    }
}
{% endhighlight %}


Class ini berfungsi untuk melakukan enkripsi pada string password supaya bisa dicocokkan dengan data password yang ada pada table.

Pada package `model`, buat class `Pengguna` dan isikan dengan

{% highlight  java %}
public class Pengguna {
    
    Connection connection;
    
    private int id;
    private String username;
    private String password;
    private String namaLengkap;
    private boolean isAdmin;
    
    public Pengguna(Connection connection) {
        this.connection = connection;
    }
    
    public Pengguna(int id, String username, String namaLengkap, boolean isAdmin) {
        this.id = id;
        this.username = username;
        this.namaLengkap = namaLengkap;
        this.isAdmin = isAdmin;
    }
    
    public Pengguna login(){
        Pengguna pengguna = null;
        
        String loginSQL = "SELECT * FROM pengguna WHERE username = ? AND password = ?";
        
        PreparedStatement ps;
        try {
            ps = this.connection.prepareStatement(loginSQL);
            
            String md5Password = MD5.getMd5(this.password);
            
            ps.setString(1, this.username);
            ps.setString(2, md5Password);
            ResultSet rs = ps.executeQuery();
            
            if(rs.next()){
                pengguna = new Pengguna(
                        rs.getInt(1),
                        rs.getString(2),
                        rs.getString(4),
                        rs.getBoolean(5)
                );
            }
        } catch (SQLException ex) {
            System.out.println("Gagal login");
        }
        
        return pengguna;
    }
}
{% endhighlight %}

Jangan lupa untuk `Generate Getter and Setter` untuk semua atribut pada class Pengguna. 

Method `login()` pada Pengguna mengubah atribut password kedalam md5Password menggunakan class MD5 yang sebelumnya sudah dibuat. Method ini akan me-return object Pengguna yang bisa diambil id, nama lengkap, dan level usernya.

Pada package `view.admin` Buat JFrame `AdminMainFrame` dan isikan

![AdminMainFrame]({{ site.url }}/assets/img/java-7-4/java-7-4-1.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel: name->lbNamaLengkap
4. JButton: name->btUbahPassword
5. JButton: name->btJenisBarang
6. JButton
7. JButton
8. JButton
9. JButton
10. JButton
11. JButton
12. JButton: name->btTutup

Masuk pada bagian `Source` dan tambahkan atribut Pengguna beserta setter-nya, letakkan sebelum constructor

{% highlight  java %}
Pengguna pengguna;
    
public void setPengguna(Pengguna pengguna){
    this.pengguna = pengguna;
}

public AdminMainFrame() {
        initComponents();
}
{% endhighlight %}

Ganti extends `javax.swing.JFrame` menjadi 
{% highlight  java %}
public class AdminMainFrame extends CustomFrame
{% endhighlight %}

Karena JenisBarang sudah punya frame bisa ditambahkan pada `btJenisBarang`

{% highlight  java %}
private void btjenisBarangActionPerformed(java.awt.event.ActionEvent evt) {                                              
    JenisBarangViewFrame frame = new JenisBarangViewFrame();
    frame.customShow();
}    
{% endhighlight %}

Tambahkan method untuk `btTutup` dan `formWindowActivated`

{% highlight  java %}
private void btTutupActionPerformed(java.awt.event.ActionEvent evt) {                                        
        System.exit(0);
    }                                       

private void formWindowActivated(java.awt.event.WindowEvent evt) {                                     
    lbNamaLengkap.setText(pengguna.getNamaLengkap());
}     
{% endhighlight %}


Pada package `view` buat package baru `cashier` dan buat JFrame `CashierMainFrame` dan isikan

![CashierMainFrame]({{ site.url }}/assets/img/java-7-4/java-7-4-2.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel: name->lbNamaLengkap
4. JButton: name->btUbahPassword
5. JButton
6. JButton
7. JButton: name->btTutup

Sama seperti pada AdminMainFrame, masuk pada bagian `Source` dan tambahkan atribut Pengguna beserta setter-nya, letakkan sebelum constructor

{% highlight  java %}
Pengguna pengguna;
    
public void setPengguna(Pengguna pengguna){
    this.pengguna = pengguna;
}

public CashierMainFrame() {
        initComponents();
}
{% endhighlight %}

Ganti extends `javax.swing.JFrame` menjadi 
{% highlight  java %}
public class CashierMainFrame extends CustomFrame
{% endhighlight %}

Tambahkan method untuk `btTutup` dan `formWindowActivated`

{% highlight  java %}
private void btTutupActionPerformed(java.awt.event.ActionEvent evt) {                                        
        System.exit(0);
    }                                       

private void formWindowActivated(java.awt.event.WindowEvent evt) {                                     
    lbNamaLengkap.setText(pengguna.getNamaLengkap());
}     
{% endhighlight %}


Pada package `view` buat package baru `auth` dan buat JFrame `LoginFrame` dan isikan

![LoginFrame]({{ site.url }}/assets/img/java-7-4/java-7-4-3.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel
4. JTextField: name->tfUsername
5. JLabel
6. JPasswordField: name->tfPassword
7. JButton: name->btBatal
8. JButton: name->btLogin

Ganti extends `javax.swing.JFrame` menjadi 
{% highlight  java %}
public class LoginFrame extends CustomFrame
{% endhighlight %}

Pada `btLogin` tambahkan

{% highlight  java %}
private void btLoginActionPerformed(java.awt.event.ActionEvent evt) {                                        
    Database db = new Database();
    Connection connection = db.getConnection();
    Pengguna pengguna = new Pengguna(connection);
    
    String stringPassword = new String(tfPassword.getPassword());

    pengguna.setUsername(tfUsername.getText());
    pengguna.setPassword(stringPassword);
    
    pengguna = pengguna.login();
    
    if(pengguna != null){
        if(pengguna.isIsAdmin()){
            dispose();
            AdminMainFrame frame = new AdminMainFrame();
            frame.setPengguna(pengguna);
            frame.customShow();
        }else{
            dispose();
            CashierMainFrame frame = new CashierMainFrame();
            frame.setPengguna(pengguna);
            frame.customShow();
        }
    }else{
        JOptionPane.showMessageDialog(null, "Login gagal");

    }
}     
{% endhighlight %}

Pada `btBatal` tambahkan

{% highlight  java %}
private void btBatalActionPerformed(java.awt.event.ActionEvent evt) {                                        
    System.exit(0);
}      
{% endhighlight %}

Terakhir pada class `Main` ubah method main-nya menjadi

{% highlight  java %}
public static void main(String[] args) {
    LoginFrame frame = new LoginFrame();
    frame.customShow();
}
{% endhighlight %}


Struktur project pada akhir Part 4 ini:

![StrukturAkhir]({{ site.url }}/assets/img/java-7-4/java-7-4-4.png)


[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
Praktikum Java Semester 7: Part 4