<?php
ob_start();
session_start();
include "../partials/Config.php"; // File koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID user dari sesi
$user_id = $_SESSION['user']['id_pengguna'];

// Query untuk mendapatkan kelas favorit user
$query = "
    SELECT k.nama_kelas, k.deskripsi, k.id_kelas
    FROM kelas_favorit f
    JOIN kelas k ON f.id_kelas = k.id_kelas
    WHERE f.id_user = '$user_id'
";
$result = mysqli_query($conn, $query);

?>

<!-- Navbar -->
<nav class="bg-blue-600 text-white p-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <ul class="flex space-x-4">
            <li><a href="profil.php" class="hover:underline">Profil</a></li>
            <li><a href="riwayat.php" class="hover:underline">Riwayat</a></li>
            <li><a href="setting.php" class="hover:underline">Pengaturan Akun</a></li>
        </ul>
        <a href="../logout/logout.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">
            Logout
        </a>
    </div>
</nav>

<!-- Container -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Kelas Favorit</h1>

    <!-- Daftar Kelas -->
    <ul class="space-y-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li class="p-4 bg-gray-100 rounded-md shadow-sm flex justify-between items-center">
            <div>
                <h2 class="font-semibold"><?php echo htmlspecialchars($row['nama_kelas']); ?></h2>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
            </div>
            <form method="POST" action="hapus_favorit.php">
                <input type="hidden" name="id_kelas" value="<?php echo $row['id_kelas']; ?>">
                <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600">
                    Hapus
                </button>
            </form>
        </li>
        <?php endwhile; ?>
        <?php else: ?>
        <p class="text-center text-gray-600">Belum ada kelas favorit yang ditambahkan.</p>
        <?php endif; ?>
    </ul>
</div>

</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>