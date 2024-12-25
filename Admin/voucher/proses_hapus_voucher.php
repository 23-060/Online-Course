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

// Ambil ID voucher dari URL
$id_voucher = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validasi ID voucher
if ($id_voucher <= 0) {
    echo "<script>alert('ID voucher tidak valid!'); window.location.href = 'index.php';</script>";
    exit;
}

// Hapus data voucher berdasarkan ID
$sql = "DELETE FROM voucher WHERE id_voucher = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id_voucher);

    if ($stmt->execute()) {
        echo "<script>alert('Voucher berhasil dihapus!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus voucher!'); window.location.href = 'index.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Terjadi kesalahan pada server!'); window.location.href = 'index.php';</script>";
}
?>
