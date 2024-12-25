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
$id_kelas = $_GET['id_kelas'];

$data["quiz"] = getQuizByFilter(["id_quiz" => $id_quiz]);

$data["kelas"] = getKelasById($id_kelas);

if ($role == 3 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../modul/cari.php");
}

if (empty($data["quiz"])) {
    showAlertAndRedirect("Quiz tidak ditemukan.", "./../quiz/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teksPertanyaan = $_POST['teks_pertanyaan'];
    $jawabanBenar = $_POST['jawaban_benar'];
    $pilihan = $_POST['pilihan'];

    if (empty($teksPertanyaan) || empty($jawabanBenar) || empty($pilihan)) {
        showAlert("Semua field harus diisi!");
    } else {
        $dataPertanyaan = [
            'id_quiz' => $id_quiz,
            'teks_pertanyaan' => $teksPertanyaan,
            'jawaban_benar' => $jawabanBenar,
            'pilihan' => $pilihan,
        ];

        if (createPertanyaan($dataPertanyaan)) {
            showAlertAndRedirect("Pertanyaan berhasil ditambahkan.", "./index.php?id_quiz=$id_quiz&id_kelas=$id_kelas");
        } else {
            showAlert("Gagal menambahkan pertanyaan.");
        }
    }
}
?>

<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <a href="/admin/quiz/index.php?id_kelas=<?= $id_kelas ?>" class="hover:text-blue-500">Kelola Quiz</a> &gt;
            <a href="/admin/pertanyaan/index.php?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $id_quiz ?>" class="hover:text-blue-500">Kelola Pertanyaan</a> &gt;
            <span class="text-gray-500">Tambah</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Pertanyaan - <?= $data["quiz"]["judul"] ?></h1>

    <form action="" method="POST">
        <div class="rounded-lg shadow-md bg-white p-8">
            <div class="mb-6">
                <label for="teks_pertanyaan" class="block text-gray-600">Teks Pertanyaan</label>
                <input type="text" id="teks_pertanyaan" name="teks_pertanyaan" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan teks pertanyaan" required>
            </div>

            <div class="mb-6">
                <label for="jawaban_benar" class="block text-gray-600">Jawaban Benar</label>
                <input type="text" id="jawaban_benar" name="jawaban_benar" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan jawaban yang benar" required>
            </div>

            <div class="mb-6">
                <label for="pilihan" class="block text-gray-600">Pilihan (pisahkan dengan koma)</label>
                <input type="text" id="pilihan" name="pilihan" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan pilihan yang ada, pisahkan dengan koma" required>
            </div>

            <div class="mb-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white text-xl rounded-lg hover:bg-blue-600 transition duration-200">Tambah Pertanyaan</button>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require "../MasterAdmin.php";

?>