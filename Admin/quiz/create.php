<?php
ob_start();
session_start();
require "./../../model/quiz.php";
require "./../../model/materi.php";
require "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION['user']["peran"];
$id_pengajar = $_SESSION['user']["id_pengguna"];
$id_kelas = $_GET['id_kelas'];
$data["kelas"] = getKelasById($id_kelas);
$data["materi"] = getAllMateriWhereQuizIsNull($id_kelas);

if ($role == 3 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("Kamu tidak punya akses ke halaman ini.", "/");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $id_materi = $_POST['id_materi'];

    $quiz = [
        'judul' => $judul,
        'deskripsi' => $deskripsi,
        'id_materi' => $id_materi,
    ];
    if (createQuiz($quiz)) {
        showAlertAndRedirect("Quiz berhasil ditambahkan.", "index.php?id_kelas=$id_kelas");
    } else {
        showAlert("Gagal menambahkan quiz.");
    }
}
?>

<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <a href="/admin/quiz/index.php?id_kelas=<?= $id_kelas ?>" class="hover:text-blue-500">Kelola Quiz</a> &gt;
            <span class="text-gray-500">Tambah</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Quiz - <?= $data['kelas']['nama_kelas'] ?></h1>

    <form action="" method="POST">
        <div class="rounded-lg shadow-md bg-white p-8">
            <div class="mb-6">
                <label for="judul" class="block text-gray-600">Judul Quiz</label>
                <input type="text" id="judul" name="judul" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan judul quiz" required>
            </div>

            <div class="mb-6">
                <label for="id_materi" class="block text-gray-600">Pilih Materi</label>
                <select id="id_materi" name="id_materi" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>Pilih Materi</option>
                    <?php foreach ($data["materi"] as $materi): ?>
                        <option value="<?= $materi['id_materi'] ?>" <?= (isset($_POST["id_materi"]) && $_POST["id_materi"] == $materi["id_materi"] ? "selected" : "") ?>>
                            <?= $materi['judul_materi'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-600">Deskripsi Quiz</label>
                <textarea id="deskripsi" name="deskripsi" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Masukkan deskripsi quiz" required></textarea>
            </div>

            <div class="mb-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white text-xl rounded-lg hover:bg-blue-600 transition duration-200">Tambah Quiz</button>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require "../MasterAdmin.php";
?>