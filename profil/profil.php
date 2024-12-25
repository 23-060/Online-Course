<?php
ob_start();
session_start();
include "../partials/Config.php"; // Ganti dengan file koneksi database Anda

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id_pengguna'];

// Ambil data user
$query_user = "SELECT * FROM pengguna WHERE id_pengguna = '$user_id'";
$result_user = mysqli_query($conn, $query_user);
$user = mysqli_fetch_assoc($result_user);

// Statistik berdasarkan pendaftaran kelas
$query_pendaftaran = "SELECT 
                        COUNT(*) AS total_diambil, 
                        SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) AS total_selesai 
                      FROM pendaftaran_kelas 
                      WHERE id_pengguna = '$user_id'";
$result_pendaftaran = mysqli_query($conn, $query_pendaftaran);
$pendaftaran = mysqli_fetch_assoc($result_pendaftaran);

// Jika ada pendaftaran dengan status selesai, tambahkan ke tabel kelas (otomasi)
$query_selesai = "SELECT id_kelas FROM pendaftaran_kelas WHERE id_pengguna = '$user_id' AND status = 'selesai'";
$result_selesai = mysqli_query($conn, $query_selesai);
while ($row = mysqli_fetch_assoc($result_selesai)) {
    $kelas_id = $row['id_kelas'];
    $check_kelas = "SELECT * FROM kelas WHERE id_user = '$user_id' AND id_kelas = '$kelas_id'";
    $result_check = mysqli_query($conn, $check_kelas);

    if (mysqli_num_rows($result_check) == 0) {
        $insert_kelas = "INSERT INTO kelas (id_kelas, id_kelas) VALUES ('$user_id', '$kelas_id')";
        mysqli_query($conn, $insert_kelas);
    }
}

// Ambil pencapaian & sertifikat
$query_achievements = "SELECT nama_sertifikat FROM sertifikat WHERE id_user = '$user_id'";
$result_achievements = mysqli_query($conn, $query_achievements);
$achievements = [];
while ($row = mysqli_fetch_assoc($result_achievements)) {
    $achievements[] = $row['nama_sertifikat'];
}

?>

<!-- Navbar -->
<nav class="bg-blue-600 text-white p-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <ul class="flex space-x-4">
            <li><a href="favorit.php" class="hover:underline">Favorit</a></li>
            <li><a href="riwayat.php" class="hover:underline">Riwayat</a></li>
            <li><a href="setting.php" class="hover:underline">Pengaturan Akun</a></li>
        </ul>
        <a href="../logout/logout.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">
            Logout
        </a>
    </div>
</nav>

<!-- Profil -->
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center space-x-6">
        <!-- Foto Profil -->
        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-blue-600">
            <img src="<?php echo !empty($user['foto']) ? htmlspecialchars($user['foto']) : 'https://via.placeholder.com/150'; ?>"
                alt="Foto Profil" class="w-full h-full object-cover">
        </div>
        <!-- Biodata -->
        <div>
            <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($user['nama']); ?></h1>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Deskripsi: <?php echo htmlspecialchars($user['bio'] ?? "Kosong"); ?></p>
            <p>Sekolah: <?php echo htmlspecialchars($user['sekolah'] ?? "Kosong"); ?></p>
            <p>Kelas: <?php echo htmlspecialchars($user['kelas'] ?? "Kosong"); ?></p>
            <p>Jenis Kelamin: <?php echo htmlspecialchars($user['jenis_kelamin'] ?? "Kosong"); ?></p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h2 class="text-xl font-semibold">Statistik</h2>
            <p><strong>Kelas Diambil:</strong> <?php echo $pendaftaran['total_diambil'] ?? 0; ?></p>
            <p><strong>Kelas Selesai:</strong> <?php echo $pendaftaran['total_selesai'] ?? 0; ?></p>
        </div>
        <div>
            <h2 class="text-xl font-semibold">Badge</h2>
            <span class="inline-block bg-blue-500 text-white py-1 px-3 rounded-md">
                <?php
                    $badge = "Beginner";
                    if ($pendaftaran['total_selesai'] >= 10) {
                        $badge = "Intermediate";
                    }
                    if ($pendaftaran['total_selesai'] >= 20) {
                        $badge = "Advanced";
                    }
                    echo $badge;
                    ?>
            </span>
        </div>
    </div>

    <!-- Pencapaian -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold">Pencapaian & Sertifikat</h2>
        <?php if (count($achievements) > 0): ?>
        <ul class="list-disc pl-5">
            <?php foreach ($achievements as $achievement): ?>
            <li><?php echo htmlspecialchars($achievement); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>Belum ada sertifikat atau pencapaian.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>