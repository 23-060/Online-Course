<?php
ob_start();
session_start();
require_once "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";


$role = $_SESSION['user']["peran"];

$id_pengajar = $_SESSION['user']["id_pengguna"];

if ($role != 2) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["DELETE"])) {
    $id_kelas = $_POST["id_kelas"];
    if (deleteKelas($id_kelas)) {
        showAlertAndRedirect("Kelas Berhasil Dihapus.");
    } else {
        showAlertAndRedirect("Kelas Gagal Dihapus.");
    }
}

$data["kelas"] = getKelasByPengajar($id_pengajar);

?>
<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <span class="text-gray-500">Kelola Kelas</span>
        </nav>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Kelas</h1>
    <div class="mb-4">
        <a href="./create.php"
            class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
            Tambah Kelas
        </a>
    </div>
    <div class="rounded-lg shadow-md">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Nama Kelas</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Tingkat</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Pelajaran</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Harga</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Tanggal Dibuat</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["kelas"])): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-center text-gray-500">
                            Tidak ada kelas yang tersedia.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["kelas"] as $row): ?>
                        <tr class="<?= $row['id_kelas'] % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id_kelas']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama_kelas']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['tingkat']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['pelajaran']) ?></td>
                            <td class="px-4 py-2">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['dibuat_pada']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="./../materi/index.php?id_kelas=<?= $row['id_kelas'] ?>"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">Materi</a>
                                <a href="./../quiz/index.php?id_kelas=<?= $row['id_kelas'] ?>"
                                    class="px-3 py-1 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600">Quiz</a>

                                <a href="edit.php?id_kelas=<?= $row['id_kelas'] ?>"
                                    class="px-3 py-1 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600">Edit</a>
                                <form method="POST" action="" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id_kelas" value="<?= $row['id_kelas'] ?>">
                                    <button type="submit" name="DELETE" class="px-3 py-1 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>