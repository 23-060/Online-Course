<?php
require "../partials/Config.php";
session_start();

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] != 1) {
    header("Location: ../");
    exit;
}

// Ambil data feedback dari database
$sql = "SELECT feedback.id_feedback, feedback.id_pengguna, users.nama, feedback.pesan, feedback.rating, feedback.dibuat_pada 
        FROM feedback 
        JOIN users ON feedback.id_pengguna = users.id_pengguna 
        ORDER BY feedback.dibuat_pada DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Feedback</h1>
    
    <table class="w-full table-auto border-collapse border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 px-4 py-2 text-left">#</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Pesan</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Rating</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Dibuat Pada</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $i = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= $i++; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['nama']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($row['pesan']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= $row['rating']; ?> ★</td>
                        <td class="border border-gray-300 px-4 py-2"><?= $row['dibuat_pada']; ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="feedback_detail.php?id=<?= $row['id_feedback']; ?>" class="text-blue-500 hover:underline">Balas</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="border border-gray-300 px-4 py-2 text-center text-gray-500">Tidak ada feedback</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
