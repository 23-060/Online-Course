<?php
ob_start();
session_start();
require "./../../model/quiz.php";
require "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION['user']["peran"];
$id_pengajar = $_SESSION['user']["id_pengguna"];
$id_kelas = $_GET['id_kelas'];

$data["quiz"] = getQuizByFilter(["id_kelas" => $id_kelas]);
$data["kelas"] = getKelasById($id_kelas);

if ($role != 2 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../admin/kelas/");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id_quiz = $_POST['id_quiz'];

    if (deleteQuiz($id_quiz)) {
        showAlertAndRedirect("Quiz berhasil dihapus.", "index.php?id_kelas=$id_kelas&id_quiz=$id_quiz");
    } else {
        showAlert("Gagal menghapus quiz.");
    }
}
?>

<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <span class="text-gray-500">Kelola Quiz</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Quiz - <?= $data['kelas']['nama_kelas'] ?></h1>
    <div class="mb-4">
        <a href="create.php?id_kelas=<?= $id_kelas ?>"
            class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">
            Tambah Quiz
        </a>
    </div>
    <div class="rounded-lg shadow-md">
        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-gray-600 font-semibold">ID Quiz</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Judul Quiz</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Deskripsi</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Materi</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Tanggal Dibuat</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data["quiz"])): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                            Tidak ada quiz yang tersedia untuk kelas ini.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data["quiz"] as $row): ?>
                        <tr class="<?= $row['id_quiz'] % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= $row['id_quiz'] ?></td>
                            <td class="px-4 py-2"><?= $row['judul'] ?></td>
                            <td class="px-4 py-2 line-clamp-1 leading-loose max-w-64"><?= $row['deskripsi'] ?></td>
                            <td class="px-4 py-2"><?= $row['id_materi'] . " - " . $row['judul_materi'] ?></td>
                            <td class="px-4 py-2 "><?= $row['dibuat_pada'] ?></td>
                            <td class="px-4 py-2 text-center">
                                <a href="./../pertanyaan/index.php?id_quiz=<?= $row['id_quiz'] ?>&id_kelas=<?= $id_kelas ?>"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600">Kelola Pertanyaan</a>
                                <a href="edit.php?id_quiz=<?= $row['id_quiz'] ?>&id_kelas=<?= $id_kelas ?>"
                                    class="px-3 py-1 bg-purple-500 text-white text-sm rounded-lg hover:bg-purple-600">Edit</a>
                                <form method="POST" action="" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    <input type="hidden" name="id_quiz" value="<?= $row['id_quiz'] ?>">
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