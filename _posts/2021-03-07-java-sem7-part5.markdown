---
layout: post
title:  "Praktikum Java Semester 7: Part 4"
date:   2021-03-07 09:00:41 +0800
categories: java
published : false
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
Praktikum Java Semester 7: Part 3
[Praktikum Java Semester 7: Part 4]({% post_url 2021-03-07-java-sem7-part4 %})

Buat package `utils` dan didalamnya buat class `KeyValue` dan isikan dengan

{% highlight  java %}
public class KeyValue {
    
    int key;
    String value;
    
    @Override
    public String toString(){
        return value;
    }

    public KeyValue(int key, String value) {
        this.key = key;
        this.value = value;
    }
    
    public KeyValue(String value) {
        this.key = 0;
        this.value = value;
    }
    
    public KeyValue() {
        this.key = 0;
        this.value = "";
    }

    public int getKey() {
        return key;
    }

    public void setKey(int key) {
        this.key = key;
    }

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value;
    }
    
    
}
{% endhighlight %}

Class ini akan berfungsi dalam pengisian JComboBox yang dinamis. JComboBox akan menampilkan list (value) berupa nama barang tetapi juga menyediakan id barang (key) dalam datanya. 

Berikutnya masih pada package `utils` buat class `MD5` dan isikan dengan

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

Class ini berfungsi untuk melakukan enkripsi pada string password supaya bisa dicocokkan dengan data password yang ada pada table


AddFrame akan berfungsi sebagai frame yang melakukan proses tambah data, dan ubah data. Dimulai dengan yang table JenisBarang yang hanya berisikan 2 (dua) field maka harusnya pembuatan frame ini akan sangat mudah.

Pilih package `view.admin.jenisbarang` kemudian buat sebuah frame dengan nama `JenisBarangAddFrame` dan atur sedemikian rupa sehingga tampil seperti gambar berikut

![UseCaseDiagram]({{ site.url }}/assets/img/java-7-3/java-7-3-1.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel
4. JTextField: tfId -> Properties -> matikan centang `Editable`
5. JLabel
6. JTextField: tfNamaJenisBarang
7. JButton: btBatal
8. JButton: btSimpan

Pada baris sebelum constructor `JenisBarangAddFrame()` tambahkan
{% highlight  java %}

...
JenisBarang jenisBarang;

public void setJenisBarang(JenisBarang jenisBarang) {
    this.jenisBarang = jenisBarang;
}

public JenisBarangAddFrame() {
    initComponents();
}
{% endhighlight %}


Pindah ke bagian `Source` , ubah deklarasi class frame ini dari

{% highlight  java %}
public class JenisBarangAddFrame extends javax.swing.JFrame{
{% endhighlight %}

menjadi

{% highlight  java %}
public class JenisBarangAddFrame extends CustomFrame
   implements AddFrameInterface{
{% endhighlight %}

Implements abstract methodnya dan pada method `dataKosong` isikan dengan

{% highlight  java %}
@Override
public boolean dataKosong() {
    if (tfNamaJenisBarang.getText().equals("")) {
        return true;
    } else {
        return false;
    }
}
{% endhighlight %}


Pada method `simpanTambah` isikan dengan
{% highlight  java %}
@Override
public  void simpanTambah() {

    String namaJenisBarang = tfNamaJenisBarang.getText();

    Database db = new Database();
    Connection con = db.getConnection();
    jenisBarang = new JenisBarang(con);
    jenisBarang.setNamaJenisBarang(namaJenisBarang);


    if (jenisBarang.create()) {
        JOptionPane.showMessageDialog(null, "Data berhasil disimpan");
        dispose();
    } else {
        JOptionPane.showMessageDialog(null, "Data gagal disimpan");
    }


}
{% endhighlight %}

Pada method `simpanUbah` isikan dengan
{% highlight  java %}
@Override
public void simpanUbah() {

    String id = tfId.getText();
    int idInt = Integer.parseInt(id);
    String namaJenisBarang = tfNamaJenisBarang.getText();

    jenisBarang.setId(idInt);
    jenisBarang.setNamaJenisBarang(namaJenisBarang);
    
    if (jenisBarang.update()) {
        JOptionPane.showMessageDialog(null, "Data berhasil diubah");
        dispose();
    } else {
        JOptionPane.showMessageDialog(null, "Data gagal diubah");
    }
}
{% endhighlight %}



Pindah ke bagian `Design`, klik pada frame dan cari pada window `Properties` tab `Events`, scroll kebawah pada posisi `windowActivated`, pilih comboboxnya sehingga tercipta method `formWindowActivated`, dan isikan dengan

{% highlight  java %}
private void formWindowActivated(java.awt.event.WindowEvent evt) {                                     
    if (jenisBarang != null) {
        String id = String.valueOf(jenisBarang.getId());
        String namaJenisBarang = jenisBarang.getNamaJenisBarang();

        tfId.setText(id);
        tfNamaJenisBarang.setText(namaJenisBarang);
    }
}      
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btBatal`, dan isikan dengan

{% highlight  java %}
private void btBatalActionPerformed(java.awt.event.ActionEvent evt) {                                        
    dispose();
}     
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btSimpan`, dan isikan dengan

{% highlight  java %}
private void btSimpanActionPerformed(java.awt.event.ActionEvent evt) {                                         

    boolean tambahData = jenisBarang == null;

    if (!dataKosong()) {
        if (tambahData) {
            simpanTambah();
        } else {
            simpanUbah();
        }
    } else {
        JOptionPane.showMessageDialog(null, "Lengkapi data");
    }
}     
{% endhighlight %}

Kembali ke frame `JenisbarangViewFrame` klik dua kali pada `btTambah` dan isikan dengan

{% highlight  java %}
private void btTambahActionPerformed(java.awt.event.ActionEvent evt) {                                         
    JenisBarangAddFrame frame = new JenisBarangAddFrame();
    frame.customShow();
}            
{% endhighlight %}

klik dua kali pada `btUbah` dan isikan dengan

{% highlight  java %}
private void btUbahActionPerformed(java.awt.event.ActionEvent evt) {                                       

    TableModel tableModel = tbJenisbarang.getModel();
    int rowSelected = tbJenisbarang.getSelectedRow();

    if (rowSelected >= 0) {

        int id = (int) tableModel.getValueAt(rowSelected, 0);
        String namaJenisBarang = tableModel.getValueAt(rowSelected, 1).toString();

        Database db = new Database();
        Connection con = db.getConnection();

        JenisBarang jenisBarang = new JenisBarang(con);
        jenisBarang.setId(id);
        jenisBarang.setNamaJenisBarang(namaJenisBarang);

        JenisBarangAddFrame frame = new JenisBarangAddFrame();
        frame.setJenisBarang(jenisBarang);
        frame.customShow();
    } else {
        JOptionPane.showMessageDialog(null,
                "Pilih data",
                "Pesan",
                JOptionPane.WARNING_MESSAGE);
    }

}        
{% endhighlight %}

Pengujian dilakukan dengan mengubah isi dari class `Main` menjadi

{% highlight  java %}
public class Main {
    public static void main(String[] args) {

        JenisBarangViewFrame frame = new JenisBarangViewFrame();
        frame.customShow();
        
    }
}
{% endhighlight %}


[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
[Praktikum Java Semester 7: Part 2]({% post_url 2021-02-28-java-sem7-part2 %})
Praktikum Java Semester 7: Part 3