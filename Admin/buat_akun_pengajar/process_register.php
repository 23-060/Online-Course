<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../partials/Config.php'; 

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $kata_sandi = password_hash($_POST['kata_sandi'], PASSWORD_DEFAULT); 
    $keahlian = intval($_POST['keahlian']); 
    $peran = 2; 

    $sql = "INSERT INTO pengguna (nama, email, username, kata_sandi, peran, keahlian, dibuat_pada, diperbarui_pada) 
            VALUES ('$nama', '$email', '$username', '$kata_sandi', $peran, $keahlian, NOW(), NOW())";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Akun Berhasil Di Buat'); window.location.href='./buat_akun.php';</script>";
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
