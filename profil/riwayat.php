<?php
session_start();
include "../partials/Config.php"; // Koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID user dari sesi
$user_id = $_SESSION['user']['id_pengguna'];

// Query untuk mendapatkan riwayat kelas user
$query = "
    SELECT k.nama_kelas, k.deskripsi, p.status, p.progres, p.tanggal_pendaftaran, p.tanggal_selesai
    FROM pendaftaran_kelas p
    JOIN kelas k ON p.id_kelas = k.id_kelas
    WHERE p.id_pengguna = '$user_id'
    ORDER BY p.tanggal_pendaftaran DESC
";
$result = mysqli_query($conn, $query);

?>

<!-- Navbar -->
<nav class="bg-blue-600 text-white p-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <ul class="flex space-x-4">
            <li><a href="profil.php" class="hover:underline">Profil</a></li>
            <li><a href="favorit.php" class="hover:underline">Favorit</a></li>
            <li><a href="setting.php" class="hover:underline">Pengaturan Akun</a></li>
        </ul>
        <a href="../logout/logout.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">
            Logout
        </a>
    </div>
</nav>

<!-- Container -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-4">Riwayat Kelas</h1>

    <!-- Daftar Riwayat Kelas -->
    <ul class="space-y-4">
        <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <li class="p-4 bg-gray-100 rounded-md shadow-sm">
            <h2 class="font-semibold"><?php echo htmlspecialchars($row['nama_kelas']); ?></h2>
            <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
            <div class="mb-2">
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <span
                    class="text-sm text-<?php echo $row['status'] == 'selesai' ? 'green-600' : ($row['status'] == 'dibatalkan' ? 'red-600' : 'blue-600'); ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </div>
            <div class="mb-2">
                <span class="text-sm font-medium text-gray-700">Progres:</span>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: <?php echo $row['progres']; ?>%;">
                    </div>
                </div>
                <span class="text-sm text-gray-700"><?php echo $row['progres']; ?>%</span>
            </div>
            <p class="text-sm text-gray-600">
                <span class="font-medium">Tanggal Daftar:</span>
                <?php echo date('d-m-Y', strtotime($row['tanggal_pendaftaran'])); ?>
                <?php if ($row['status'] == 'selesai'): ?>
                <span class="font-medium ml-4">Tanggal Selesai:</span>
                <?php echo date('d-m-Y', strtotime($row['tanggal_selesai'])); ?>
                <?php endif; ?>
            </p>
        </li>
        <?php endwhile; ?>
        <?php else: ?>
        <p class="text-center text-gray-600">Belum ada riwayat kelas.</p>
        <?php endif; ?>
    </ul>
</div>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php';
?>