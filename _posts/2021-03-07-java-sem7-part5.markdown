---
layout: post
title:  "Praktikum Java Semester 7: Part 5"
date:   2021-03-07 10:00:41 +0800
categories: java
published : true
comments : true
description: Proses Ubah Password
tags: 
 - java
 - netbeans
 - auth
 - 201-praktikum-7-java
---
[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
[Praktikum Java Semester 7: Part 4]({% post_url 2021-03-07-java-sem7-part4 %})
Praktikum Java Semester 7: Part 5

Pada model `Pengguna` tambahkan method `ubahpassword()`
{% highlight  java %}
public boolean ubahpassword(){
    boolean hasil = false;
    
    String updateSQL = "UPDATE pengguna SET password = ? WHERE id = ?";
    
    String md5Password = MD5.getMd5(this.password);
    
    PreparedStatement ps;
    try {
        ps = this.connection.prepareStatement(updateSQL);
        ps.setString(1, md5Password);
        ps.setInt(2,this.id);
        ps.execute();
        hasil = true;
    } catch (SQLException ex) {
        System.out.println("Gagal ubah password");
    }

    return hasil;
}
{% endhighlight %}

Pada package `auth` buat sebuah frame `ResetPasswordFrame`

![ResetPasswordFrame]({{ site.url }}/assets/img/java-7-5/java-7-5-1.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel
4. JPasswordField: name->tfPasswordLama
5. JLabel
6. JPasswordField: name->tfPasswordBaru
7. JLabel
8. JPasswordField: name->tfPasswordBaruUlang
9. JButton: btBatal
10. JButton: btSimpan



Sama seperti pada frame lainnya, masuk pada bagian `Source` dan tambahkan atribut Pengguna beserta setter-nya, letakkan sebelum constructor

{% highlight  java %}
Pengguna pengguna;
    
public void setPengguna(Pengguna pengguna){
    this.pengguna = pengguna;
}

public ResetPasswordFrame() {
        initComponents();
}
{% endhighlight %}

Ganti extends `javax.swing.JFrame` menjadi 
{% highlight  java %}
public class ResetPasswordFrame extends CustomFrame
{% endhighlight %}

Isikan method untuk masing-masing button

{% highlight  java %}
private void btBatalActionPerformed(java.awt.event.ActionEvent evt) {                                        
    dispose();
}                                       

private void btSimpanActionPerformed(java.awt.event.ActionEvent evt) {                                         
    String passwordLamaString = new String(tfPasswordLama.getPassword());
    String passwordBaruString = new String(tfPasswordBaru.getPassword());
    String passwordBaruUlangString = new String(tfPasswordBaruUlang.getPassword());

    if(passwordBaruString.equals(passwordBaruUlangString)){
        Database db = new Database();
        Connection con = db.getConnection();
        Pengguna mPengguna = new Pengguna(con);
        
        mPengguna.setUsername(this.pengguna.getUsername());
        mPengguna.setPassword(passwordLamaString);
        mPengguna = mPengguna.login();
        
        if(mPengguna != null){
            mPengguna.setPassword(passwordBaruString);
            mPengguna.setConnection(con);
            if(mPengguna.ubahpassword()){
                JOptionPane.showMessageDialog(null, "Ubah Password Berhasil");
                dispose();
            }else{
                JOptionPane.showMessageDialog(null, "Ubah Password Gagal");
            }
        }else{
            JOptionPane.showMessageDialog(null, "Password Lama salah");
        }
    }else{
        JOptionPane.showMessageDialog(null, "Password Baru tidak sesuai");
    }
}      
{% endhighlight %}

Kembali ke `AdminMainFrame` dan `CashierMainFrame`, isikan method `btUbahPassword` dengan

{% highlight  java %}
private void btUbahPasswordActionPerformed(java.awt.event.ActionEvent evt) {                                               
    ResetPasswordFrame frame = new ResetPasswordFrame();
    frame.setPengguna(this.pengguna);
    frame.customShow();
}      
{% endhighlight %}


[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
[Praktikum Java Semester 7: Part 3]({% post_url 2021-02-28-java-sem7-part3 %})
[Praktikum Java Semester 7: Part 4]({% post_url 2021-03-07-java-sem7-part4 %})
Praktikum Java Semester 7: Part 5