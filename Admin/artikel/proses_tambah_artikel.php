<?php
require "../../partials/Config.php";
session_start();

// if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] != 1) {
//     echo "<script>alert('Akses ditolak!'); window.location.href = '../index.php';</script>";
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);

    // Proses upload file gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambarTmpPath = $_FILES['gambar']['tmp_name'];
        $gambarData = addslashes(file_get_contents($gambarTmpPath));
    } else {
        echo "<script>alert('Gagal mengunggah gambar.'); window.history.back();</script>";
        exit;
    }

    // Query untuk memasukkan data ke database
    $query = "INSERT INTO artikel (nama_artikel, konten, dibuat_pada, gambar) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ssss", $judul, $konten, $tanggal, $gambarData);
        $execute = $stmt->execute();

        if ($execute) {
            echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan artikel.'); window.history.back();</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Terjadi kesalahan pada server.'); window.history.back();</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Akses tidak valid.'); window.history.back();</script>";
    exit;
}
?>