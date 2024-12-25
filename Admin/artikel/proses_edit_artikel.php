<?php
require "../../partials/Config.php";
session_start();
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
if (isset($_POST['id_artikel'])) {
    $id_artikel = $_POST['id_artikel'];
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $konten = $_POST['konten'];

    // Cek jika ada gambar yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
    } else {
        // Jika tidak ada gambar, gunakan gambar lama
        $gambar = null;
    }

    // Query untuk update artikel
    if ($gambar) {
        $sql = "UPDATE artikel SET nama_artikel = ?, konten = ?, dibuat_pada = ?, gambar = ? WHERE id_artikel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $judul, $konten, $tanggal, $gambar, $id_artikel);
    } else {
        $sql = "UPDATE artikel SET nama_artikel = ?, konten = ?, dibuat_pada = ? WHERE id_artikel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $judul, $konten, $tanggal, $id_artikel);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Artikel berhasil diperbarui'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui artikel'); window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('Data tidak lengkap'); window.location.href = 'index.php';</script>";
}
?>
