<?php
ob_start();
session_start();
require "./../../model/pertanyaan.php";
require "./../../model/quiz.php";
require "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION['user']["peran"];
$id_pengajar = $_SESSION['user']["id_pengguna"];
$id_quiz = $_GET['id_quiz'];
$id_kelas = $_GET["id_kelas"];

$data["quiz"] = getQuizByFilter(["id_quiz" => $id_quiz]);
$data["kelas"] = getKelasById($id_kelas);

if ($role == 3 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../modul/cari.php");
}

$data["pertanyaan"] = getPertanyaanByFilter(["id_quiz" => $id_quiz]);

if (empty($data["quiz"])) {
    showAlertAndRedirect("Quiz tidak ditemukan.", "./../quiz/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id_pertanyaan = $_POST['id_pertanyaan'];
    if (deletePertanyaan(["id_pertanyaan" => $id_pertanyaan])) {
        showAlertAndRedirect("Pertanyaan berhasil dihapus.");
    } else {
        showAlert("Gagal menghapus pertanyaan.");
    }
}
?>

<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <a href="/admin/quiz/index.php?id_kelas=<?= $id_kelas ?>" class="hover:text-blue-500">Kelola Quiz</a> &gt;
            <span class="text-gray-500">Kelola Pertanyaan</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Pertanyaan - <?= $data["quiz"]["judul"] ?></h1>
    <div class="mb-4">
        <a href="create.php?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $id_quiz ?>"
            class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
            Tambah Pertanyaan
        </a>
    </div>
    <div class="rounded-lg shadow-md">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-gray-600 font-semibold">No.</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Teks Pertanyaan</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Jawaban Benar</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Pilihan</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["pertanyaan"])): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                            Tidak ada pertanyaan yang tersedia untuk quiz ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["pertanyaan"] as $key => $row): ?>
                        <tr class="<?= $row['id_pertanyaan'] % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= $key + 1 ?></td>
                            <td class="px-4 py-2"><?= $row['id_pertanyaan'] ?></td>
                            <td class="px-4 py-2"><?= $row['teks_pertanyaan'] ?></td>
                            <td class="px-4 py-2"><?= $row['jawaban_benar'] ?></td>
                            <td class="px-4 py-2"><?= $row['pilihan'] ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="edit.php?id_kelas=<?= $id_kelas ?>&id_pertanyaan=<?= $row['id_pertanyaan'] ?>&id_quiz=<?= $id_quiz ?>"
                                    class="px-3 py-1 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600">Edit</a>
                                <form method="POST" action="" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id_pertanyaan" value="<?= $row['id_pertanyaan'] ?>">
                                    <button type="submit" name="delete" class="px-3 py-1 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">Hapus</button>
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