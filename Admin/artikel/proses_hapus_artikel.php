<?php
require "../../partials/Config.php";
session_start();

// Pastikan user sudah login dan memiliki peran admin
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('ADMIN DATANG!!'); window.location.href = '../index.php'</script>";
    }
} else {
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href = '../index.php'</script>";
    exit();
}

// Cek apakah ada ID artikel yang diterima melalui URL
if (isset($_GET['id'])) {
    $id_artikel = $_GET['id'];

    // Query untuk menghapus artikel berdasarkan ID
    $sql = "DELETE FROM artikel WHERE id_artikel = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_artikel);

    // Eksekusi query dan periksa apakah artikel berhasil dihapus
    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil dihapus'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus artikel'); window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('ID artikel tidak ditemukan'); window.location.href = 'index.php';</script>";
}
?>
