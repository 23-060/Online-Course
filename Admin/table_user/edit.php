<?php
ob_start();
require "../../partials/Config.php";
session_start();

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('Anda tidak memiliki izin!'); window.location.href = '../index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href = '../index.php';</script>";
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = $id"), MYSQLI_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $peran = (int)$_POST['peran'];

    $query = "UPDATE pengguna SET 
                nama = '$nama', 
                username = '$username', 
                email = '$email', 
                peran = $peran 
              WHERE id_pengguna = $id";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mengupdate data: " . mysqli_error($conn) . "');</script>";
    }
}

?>

<form method="POST" action="" style="max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
    <h2 style="text-align: center;">Edit Pengguna</h2>
    <div style="margin-bottom: 15px;">
        <label for="nama" style="display: block; margin-bottom: 5px;">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data[0]['nama']) ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="username" style="display: block; margin-bottom: 5px;">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($data[0]['username']) ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="email" style="display: block; margin-bottom: 5px;">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($data[0]['email']) ?>" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="peran" style="display: block; margin-bottom: 5px;">Peran:</label>
        <select id="peran" name="peran" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="1" <?= $data[0]['peran'] == 1 ? 'selected' : '' ?>>Admin</option>
            <option value="2" <?= $data[0]['peran'] == 2 ? 'selected' : '' ?>>Pengajar</option>
            <option value="3" <?= $data[0]['peran'] == 3 ? 'selected' : '' ?>>Siswa</option>
        </select>
    </div>
    <div style="text-align: center;">
        <button type="submit" style="padding: 10px 20px; border: none; border-radius: 4px; background-color: #007BFF; color: #fff; cursor: pointer;">Update</button>
    </div>
</form>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
