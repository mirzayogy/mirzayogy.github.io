---
layout: post
title:  Maven Webapp
categories: Java
date:   2016-12-31 23:00:41 +0800
comments: true
published: false
---
1.	Buat project baru

		mvn archetype:generate -DgroupId=mirza.belajar -DartifactId=nama-aplikasi

2.	Input “912“ untuk memilih archetype-webapp
3.  Pilih Versinya 
4.  Pilih Versi kita
5.  Konfirmasi "Y"
6.  Copy file “web.xml“ dari folder 

		\apache-tomcat-8.5.5\webapps\ROOT\WEB-INF 

	ke 

		\nama-aplikasi\src\main\webapp\WEB-INF

7.  Edit file “index.jsp“ sesuai html yang diinginkan
8.  Run dengan perintah

		mvn clean package tomcat:run

9.  Buka di 

		http://localhost:8080/nama-aplikasi/

10. tekan tombol Ctrl+C untuk menghentikan aplikasi

11. Tambahkan dependency servlet dan mysql
	{% highlight java %}
	<dependency>
		<groupId>javax.servlet</groupId>
		<artifactId>servlet-api</artifactId>
		<version>2.5</version>
		<scope>provided</scope>
	</dependency>
	<dependency>
		<groupId>mysql</groupId>
		<artifactId>mysql-connector-java</artifactId>
		<version>5.1.40</version>
	</dependency>
	{% endhighlight %}      
	
{% if page.comments %}
<div id="disqus_thread"></div>
<script>

/**
*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
/*
var disqus_config = function () {
this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
};
*/
(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');
s.src = '//mirzayogy.disqus.com/embed.js';
s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>                      
{% endif %}