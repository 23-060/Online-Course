<?php
ob_start();
require "../../partials/Config.php";
session_start();

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('ADMIN DATANG!!'); window.location.href = '../index.php'</script>";
    }
}

// Pastikan ID acara diberikan
if (isset($_GET['id'])) {
    $id_acara = $_GET['id'];

    // Query untuk menghapus acara berdasarkan ID
    $sql = "DELETE FROM acara WHERE id_acara = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_acara);

    if ($stmt->execute()) {
        echo "<script>alert('Acara berhasil dihapus!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus acara.'); window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('ID acara tidak ditemukan'); window.location.href = 'index.php';</script>";
}
?>
