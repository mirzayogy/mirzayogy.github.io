---
layout: post
title:  "Praktikum Java Semester 7: Part 2"
date:   2021-02-28 08:00:41 +0800
categories: java
published : true
comments : true
description: Membuat Frame
tags: 
 - java
 - netbeans
---
[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
Praktikum Java Semester 7: Part 2

Pembuatan Frame di NetBeans sangat dimudahkan oleh fitur visualnya, namun karena ada beberapa fitur yang akan memiliki method yang sama dan menghindari pengulangan coding maka terlebih dahulu dipersiapkan templatenya.

Pada pengelolaan data master akan dipersiapkan 2 (dua) frame untuk tiap table, yaitu View Frame yang fungsinya untuk menampilkan table, dan Add Frame yang fungsinya untuk tambah dan ubah data. Maka dibuat dulu Interface-nya

Buat sebuah package `template` dan didalamnya buat interface `ViewFrameInterface`, isikan dengan

{% highlight  java %}
public interface ViewFrameInterface {
    abstract void refresh();
    abstract void buildTable(ArrayList<Object> list);
}
{% endhighlight %}

berikutnya buat interface `AddFrameInterface`, isikan dengan

{% highlight  java %}
public interface MyAddFrameInterface {
    abstract boolean dataKosong();
    abstract void simpanTambah();
    abstract void simpanUbah();
}
{% endhighlight %}

Frame yang dibuat akan berupa custom yang tidak memiliki window sehingga supaya seragam kita buat dulu custom framenya, masih didalam package `template` buat sebuah class `CustomFrame`, dan isikan dengan

{% highlight  java %}
public class CustomFrame extends javax.swing.JFrame{
    public void customShow() {
        this.dispose();
        this.setUndecorated(true);
        this.setLocationRelativeTo(null);
        this.setVisible(true);
    }
}
{% endhighlight %}

Buat package `view.admin.jenisbarang` dan didalamnya buat sebuah frame dengan nama `JenisbarangViewFrame` dan atur sedemikian rupa sehingga tampil seperti gambar berikut

![UseCaseDiagram]({{ site.url }}/assets/img/java-7-2/java-7-2-1.png)

1. JPanel: Properties->background: [102,16,242]
2. JLabel
3. JLabel
4. JTextField: tfCari
5. JButton: btCari
6. JTable: tbJenisbarang
7. JButton: btBatal
8. JButton: btHapus
9. JButton: btUbah
10. JButton: btTambah
11. JButton: btTutup

`tbJenisbarang` bisa diatur dengan cara klik kanan -> `TableContents` -> pilih tab `Columns` -> isi title sesuai dengan kebutuhan -> pilih tab `Rows` -> atur agar `Count` bernilai 0

Pindah ke bagian `Source` , ubah deklarasi class frame ini dari

{% highlight  java %}
public class JenisBarangViewFrame extends javax.swing.JFrame{
{% endhighlight %}

menjadi

{% highlight  java %}
public class JenisBarangViewFrame extends CustomFrame
   implements ViewFrameInterface{
{% endhighlight %}

Implements abstract methodnya dan pada method `buildTable` isikan dengan

{% highlight  java %}
@Override
public void buildTable(ArrayList<Object> list) {
    DefaultTableModel model = (DefaultTableModel) tbJenisbarang.getModel();
    Object[] row = new Object[2];

    model.setRowCount(0);

    if (list.size() > 0) {
        for (int i = 0; i < list.size(); i++) {
            JenisBarang jenisBarang = (JenisBarang) list.get(i);
            row[0] = jenisBarang.getId();
            row[1] = jenisBarang.getNamajenisbarang();
            model.addRow(row);
        }
    }
}
{% endhighlight %}


Pada method `refresh` isikan dengan
{% highlight  java %}
@Override
public void refresh() {
    tfCari.setText("");
    Database db = new Database();
    JenisBarang jenisBarang = new JenisBarang(db.getConnection());
    ArrayList<Object> list = jenisBarang.read();
    if(!list.isEmpty()){
        buildTable(list);
    }
}
{% endhighlight %}

Pindah ke bagian `Design`, klik pada frame dan cari pada window `Properties` tab `Events`, scroll kebawah pada posisi `windowActivated`, pilih comboboxnya sehingga tercipta method `formWindowActivated`, dan isikan dengan

{% highlight  java %}
private void formWindowActivated(java.awt.event.WindowEvent evt) {                                     
    refresh();
}
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btBatal`, dan isikan dengan

{% highlight  java %}
private void btBatalActionPerformed(java.awt.event.ActionEvent evt) {                                        
    refresh();
}     
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btTutup`, dan isikan dengan

{% highlight  java %}
private void btBatalActionPerformed(java.awt.event.ActionEvent evt) {                                        
    dispose();
}     
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btCari`, dan isikan dengan

{% highlight  java %}
private void btCariActionPerformed(java.awt.event.ActionEvent evt) {                                       
        
    String keyword = tfCari.getText();
    if(!keyword.equals("")){
        Database db = new Database();
        Connection con = db.getConnection();

        JenisBarang jenisBarang = new JenisBarang(con);
        ArrayList<Object> list = jenisBarang.search(keyword);
        if(!list.isEmpty()){
            buildTable(list);
        }else{
            JOptionPane.showMessageDialog(null, "Data tidak ditemukan");
        }
    }else{
        JOptionPane.showMessageDialog(null, "Isi kata kunci pencarian");
    }
}    
{% endhighlight %}

Kembali ke bagian `Design`, klik dua kali pada `btHapus`, dan isikan dengan

{% highlight  java %}
private void btHapusActionPerformed(java.awt.event.ActionEvent evt) {                                        
        
    int barisTerpilih = tbJenisBarang.getSelectedRow();

    if(barisTerpilih >= 0){
        int pilihan = JOptionPane.showConfirmDialog(null, 
            "Yakin hapus?", 
            "Konfirmasi", 
            JOptionPane.YES_NO_OPTION);
    
        if(pilihan == 0){
            
            TableModel model = tbJenisBarang.getModel();
            int id = (int) model.getValueAt(barisTerpilih, 0);
            
            Database db = new Database();
            Connection con = db.getConnection();

            JenisBarang jb = new JenisBarang(con);
            jb.setId(id);
            jb.delete();
        }
    }else{
        JOptionPane.showMessageDialog(null, "Pilih dulu datanya");
    }
    
}   
{% endhighlight %}

Oke, sebagian fitur sudah diisi, untuk tombol tambah dan ubah diperlukan AddFrame yang akan dibahas pada part berikutnya.

[Praktikum Java Semester 7: Part 1]({% post_url 2021-02-13-java-sem7-part1 %})
Praktikum Java Semester 7: Part 2