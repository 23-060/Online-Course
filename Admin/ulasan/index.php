<?php
ob_start();
session_start();
require_once "./../../model/ulasan.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION["user"]["peran"];
if ($role != 1) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "../");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_ulasan = $_POST["id_ulasan"];
    if (deleteUlasanById($id_ulasan)) {
        showAlert("Berhasil Menghapus Data.");
    } else {
        showAlert("Gagal Menghapus Data.");
    }
}

$data["ulasan"] = getUlasanByFilter([]);



?>
        <!-- <p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p> -->
<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <span class="text-gray-500">Kelola Ulasan</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Ulasan</h1>
    <div class="rounded-lg shadow-md">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID Kelas</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID Pengguna</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Rating</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Komentar</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Tanggal</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["ulasan"])): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                            Tidak ada ulasan yang tersedia.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["ulasan"] as $row): ?>
                        <tr class="<?= $row['id_ulasan'] % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id_ulasan']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id_kelas']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id_pengguna']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['rating']) ?>/5</td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['komentar']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['dibuat_pada']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <form method="POST" action="" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id_ulasan" value="<?= $row['id_ulasan'] ?>">
                                    <button type="submit" name="delete" class="px-3 py-1 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>
<?php
$content = ob_get_clean();
require "./../MasterAdmin.php";
