============================================================================================================================================

#Cara Membuka File PHP Native di Browser

Untuk XAMPP:

1. Simpan file di /xampp/htdocs.
2. Akses melalui browser: http://localhost/Online%20Course.

Untuk Laragon:

1. Simpan file di /laragon/www.
2. Start Laragon.
3. Klik kanan, pilih "www", lalu "Online Course".

============================================================================================================================================

#Cara Import Database di PhpMyAdmin

Untuk XAMPP:

1. Buka http://localhost/phpmyadmin.
2. Buat database baru: "paw_ta".
3. Import file "paw_ta.sql".

Untuk Laragon:

1. Klik "Database" di Laragon.
2. Buat database baru: "paw_ta".
3. Import file "paw_ta.sql".

==============================================================================================================================================

#Cara Mengedit Navbar dan Menghubungkan Database

Langkah 1: Mengedit Navbar

1. Buka file /partials/nav.php.
2. Edit kode navbar sesuai kebutuhan.
3. Simpan perubahan.

Langkah 2: Menghubungkan Database

1. Buka file /partials/Config.php.
2. Konfigurasikan pengaturan database (host, username, password, nama database).
3. Simpan perubahan.

Langkah 3: Menambahkan Navbar dan Footer

1. Tambahkan kode berikut di awal file PHP: <?php ob_start(); ?>.
2. Tambahkan kode berikut di akhir file PHP: <?php $content = ob_get_clean(); include '../partials/Master.php'; ?>.
3. Pastikan file Master.php berada di folder /partials.

==============================================================================================================================================

#Contoh Struktur File


<?php ob_start(); ?>

<!-- Konten halaman -->

<?php 
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>


Tips

- Pastikan pengaturan database benar.
- Periksa koneksi database sebelum mengedit navbar.
- Gunakan kode yang rapi dan terstruktur.

==============================================================================================================================================

Sumber

- Dokumentasi resmi PHP: https://www-php-net.translate.goog/docs.php?_x_tr_sl=en&_x_tr_tl=id&_x_tr_hl=id&_x_tr_pto=tc
- Dokumentasi resmi MySQLi: https://www-php-net.translate.goog/manual/en/book.mysqli.php?_x_tr_sl=en&_x_tr_tl=id&_x_tr_hl=id&_x_tr_pto=tc

==============================================================================================================================================

#Terima Kasih

Saya berharap tutorial ini membantu

===============================================================================================================================================
