<?php
session_start();
include "../partials/Config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id_pengguna'];
    $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);

    // Query hapus kelas favorit
    $query = "DELETE FROM kelas_favorit WHERE id_user = '$user_id' AND id_kelas = '$id_kelas'";
    if (mysqli_query($conn, $query)) {
        header("Location: favorit.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>