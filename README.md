# Panduan Penggunaan dan Pengembangan Aplikasi Web

Dokumen ini menyediakan panduan komprehensif untuk menyiapkan, mengkonfigurasi, dan mengembangkan aplikasi web ini.

## I. Persiapan Lingkungan Pengembangan

Bagian ini menjelaskan langkah-langkah untuk menyiapkan lingkungan pengembangan menggunakan XAMPP atau Laragon.

### A. XAMPP

1.  **Penempatan File:** Simpan berkas-berkas aplikasi di direktori `/xampp/htdocs`.
2.  **Akses Aplikasi:** Buka aplikasi melalui peramban web dengan alamat `http://localhost/Online%20Course`.

### B. Laragon

1.  **Penempatan File:** Simpan berkas-berkas aplikasi di direktori `/laragon/www`.
2.  **Memulai Laragon:** Pastikan Laragon telah dijalankan.
3.  **Akses Aplikasi:** Klik kanan ikon Laragon pada *system tray*, pilih "www", kemudian pilih "Online Course".

## II. Impor Basis Data

Bagian ini menjelaskan cara mengimpor basis data menggunakan phpMyAdmin.

### A. XAMPP

1.  **Akses phpMyAdmin:** Buka `http://localhost/phpmyadmin` melalui peramban web.
2.  **Pembuatan Basis Data:** Buat basis data baru dengan nama `paw_ta`.
3.  **Impor Berkas SQL:** Impor berkas `paw_ta.sql` ke dalam basis data yang baru dibuat.

### B. Laragon

1.  **Akses Manajemen Basis Data:** Klik opsi "Database" pada antarmuka Laragon.
2.  **Pembuatan Basis Data:** Buat basis data baru dengan nama `paw_ta`.
3.  **Impor Berkas SQL:** Impor berkas `paw_ta.sql` ke dalam basis data yang baru dibuat.

## III. Konfigurasi dan Pengembangan Aplikasi

Bagian ini menjelaskan langkah-langkah untuk mengedit *navbar*, menghubungkan ke basis data, dan menambahkan *header* dan *footer*.

### A. Pengeditan *Navbar*

1.  **Lokasi Berkas:** Buka berkas `/partials/nav.php`.
2.  **Modifikasi:** Edit kode *navbar* sesuai kebutuhan.
3.  **Penyimpanan Perubahan:** Simpan perubahan yang telah dilakukan.

### B. Koneksi Basis Data

1.  **Lokasi Berkas:** Buka berkas `/partials/Config.php`.
2.  **Konfigurasi:** Konfigurasikan pengaturan koneksi basis data, termasuk *host*, *username*, *password*, dan nama basis data.
3.  **Penyimpanan Perubahan:** Simpan perubahan yang telah dilakukan.

### C. Penambahan *Header* dan *Footer*

1.  **Awal Berkas PHP:** Tambahkan kode `<?php ob_start(); ?>` di awal setiap berkas PHP yang membutuhkan *header* dan *footer*.
2.  **Akhir Berkas PHP:** Tambahkan kode `<?php $content = ob_get_clean(); include '../partials/Master.php'; ?>` di akhir setiap berkas PHP.
3.  **Lokasi Berkas *Master*:** Pastikan berkas `Master.php` terletak di dalam direktori `/partials`.

## IV. Struktur Berkas Contoh

Berikut adalah contoh penggunaan *header* dan *footer* dalam sebuah berkas PHP:

```php
<?php ob_start(); ?>

<?php
$content = ob_get_clean();
include '../partials/Master.php';
?>


Tips

- Pastikan pengaturan database benar.
- Periksa koneksi database sebelum mengedit navbar.
- Gunakan kode yang rapi dan terstruktur.


Sumber

- Dokumentasi resmi PHP: https://www-php-net.translate.goog/docs.php?_x_tr_sl=en&_x_tr_tl=id&_x_tr_hl=id&_x_tr_pto=tc
- Dokumentasi resmi MySQLi: https://www-php-net.translate.goog/manual/en/book.mysqli.php?_x_tr_sl=en&_x_tr_tl=id&_x_tr_hl=id&_x_tr_pto=tc


#Terima Kasih

Saya berharap tutorial ini membantu

