<?php
ob_start();
session_start();
include "../partials/Config.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id_pengguna'];
$message = "";

// Ambil data pengguna untuk ditampilkan di form
$query = "SELECT * FROM pengguna WHERE id_pengguna = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Data pengguna tidak ditemukan.";
    exit;
}

// Proses form ketika disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $tanggal_update = date('Y-m-d H:i:s');

    // Proses upload foto jika ada file
    $foto_nama = $user['foto']; // Foto default
    if (!empty($_FILES['foto']['name'])) {
        $foto_nama = "foto_" . $user_id . "_" . basename($_FILES['foto']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $foto_nama;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto_nama = $target_file;
        } else {
            $message = "Gagal mengunggah foto.";
        }
    }

    // Update data pengguna
    $update_query = "
        UPDATE pengguna
        SET 
            nama = '$nama',
            username = '$username',
            bio = '$bio',
            foto = '$foto_nama',
            diperbarui_pada = '$tanggal_update'
        WHERE id_pengguna = '$user_id'
    ";

    if (mysqli_query($conn, $update_query)) {
        $message = "Profil berhasil diperbarui.";
    } else {
        $message = "Gagal memperbarui profil: " . mysqli_error($conn);
    }
}
?>

<!-- Navbar -->
<nav class="bg-blue-600 text-white p-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <ul class="flex space-x-4">
            <li><a href="profil.php" class="hover:underline">Profil</a></li>
            <li><a href="favorit.php" class="hover:underline">Favorit</a></li>
            <li><a href="riwayat.php" class="hover:underline">Riwayat</a></li>
        </ul>
        <a href="../logout/logout.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">
            Logout
        </a>
    </div>
</nav>

<!-- Form Edit Profil -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Profil</h2>
    <?php if (!empty($message)) : ?>
    <p class="text-green-500"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="nama" class="block mb-2 text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?php echo $user['nama']; ?>"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="bio" class="block mb-2 text-sm font-medium text-gray-700">Bio</label>
            <textarea name="bio" id="bio" rows="4"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo $user['bio']; ?></textarea>
        </div>
        <div class="mb-4">
            <label for="foto" class="block mb-2 text-sm font-medium text-gray-700">Foto Profil</label>
            <?php if (!empty($user['foto'])) : ?>
            <img src="<?php echo $user['foto']; ?>" alt="Foto Profil" class="w-24 h-24 rounded-full mb-4">
            <?php endif; ?>
            <input type="file" name="foto" id="foto"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Simpan
            Perubahan</button>
    </form>
</div>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>