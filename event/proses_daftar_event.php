<?php
require_once '../partials/Config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $id_acara = (int) $_POST['event'];

    // Validasi data
    if (empty($nama) || empty($email) || empty($telepon) || empty($alamat) || empty($id_acara)) {
        $_SESSION['error'] = 'Semua field wajib diisi!';
        header('Location: form_register_event.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Format email tidak valid!';
        header('Location: form_register_event.php');
        exit;
    }

    // Insert data ke tabel peserta_event
    $sql = "INSERT INTO peserta_event (nama, email, telepon, alamat, id_acara) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameter sesuai dengan tipe data: 'ssssii' -> 4 string, 1 integer
        $stmt->bind_param('ssssi', $nama, $email, $telepon, $alamat, $id_acara);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Pendaftaran berhasil!';
             ?>
            <script>
                alert("Pendaftaran berhasil!");
                window.location.href = "index.php";
            </script>
            <?php
            exit;
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat mendaftarkan data!';
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Terjadi kesalahan pada server!';
    }

    // Redirect kembali ke form jika ada error
    header('Location: form_register_event.php');
    exit;
} else {
    // Jika akses bukan POST, redirect ke form
    header('Location: form_register_event.php');
    exit;
}
