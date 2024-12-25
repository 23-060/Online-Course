<?php
require "../../partials/Config.php";
session_start();

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('Anda tidak memiliki izin!'); window.location.href = '../index.php';</script>";
        exit;
    }
}
if (isset($_GET['id'])) {
    $id_pengguna = $_GET['id'];

    $cek_foreign = "
        SELECT 
            (SELECT COUNT(*) FROM pendaftaran_kelas WHERE id_pengguna = $id_pengguna) AS count_pendaftaran,
            (SELECT COUNT(*) FROM kelas WHERE id_pengajar = $id_pengguna) AS count_kelas
    ";
    $hasil_cek = mysqli_query($conn, $cek_foreign);
    $row = mysqli_fetch_assoc($hasil_cek);

    if ($row['count_pendaftaran'] > 0 || $row['count_kelas'] > 0) {
        echo "<script>alert('ID ini digunakan di tabel lain! Tidak dapat dihapus.'); window.location.href='index.php';</script>";
    } else {
        $hapus = "DELETE FROM pengguna WHERE id_pengguna = $id_pengguna";
        if (mysqli_query($conn, $hapus)) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data!'); window.location.href='index.php';</script>";
        }
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='index.php';</script>";
}
