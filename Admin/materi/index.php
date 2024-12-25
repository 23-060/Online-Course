<?php
ob_start();
session_start();
require "./../../model/materi.php";
require "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION['user']["peran"];
$id_pengajar = $_SESSION['user']["id_pengguna"];
$id_kelas = $_GET['id_kelas'];
$data["materi"] = getMateriByFilter(["id_kelas" => $id_kelas]);
$data["kelas"] = getKelasById($id_kelas);

if ($role != 2 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['DELETE'])) {
    $id_materi = $_POST['id_materi'];

    $materi = getMateriById($id_materi);
    if (!empty($materi)) {
        if (deleteMateri($id_materi)) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $materi['path_file'];

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            showAlertAndRedirect("Materi berhasil dihapus.", "index.php?id_kelas=$id_kelas");
        } else {
            showAlert("Gagal menghapus materi.");
        }
    } else {
        showAlert("Data materi tidak ditemukan.");
    }
}

?>

<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <span class="text-gray-500">Kelola Materi</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Materi - <?= htmlspecialchars($data['kelas']['nama_kelas']) ?></h1>
    <div class="mb-4">
        <a href="create.php?id_kelas=<?= $id_kelas ?>"
            class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
            Tambah Materi
        </a>
    </div>
    <div class="rounded-lg shadow-md">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID Materi</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Judul Materi</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Deskripsi</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Tanggal Dibuat</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["materi"])): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">
                            Belum Ada Materi Yang Dibuat
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["materi"] as $row): ?>
                        <tr class="<?= $row['id_materi'] % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['id_materi']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['judul_materi']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['dibuat_pada']) ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="edit.php?id_materi=<?= $row['id_materi'] ?>&id_kelas=<?= $id_kelas ?>"
                                    class="px-3 py-1 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600">Edit</a>
                                <form method="POST" action="" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id_materi" value="<?= $row['id_materi'] ?>">
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
require "../MasterAdmin.php";
?>