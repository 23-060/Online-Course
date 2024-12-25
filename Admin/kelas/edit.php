<?php
ob_start();
session_start();
require_once "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";


$role = $_SESSION['user']["peran"];

$id_pengajar = $_SESSION['user']["id_pengguna"];
$id_kelas = $_GET["id_kelas"];

$data["kelas"] = getKelasById($id_kelas);
if ($role != 2 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../modul/cari.php");
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data["form"]["nama_kelas"] = $_POST["nama_kelas"];
    $data["form"]["tingkat"] = $_POST["tingkat"];
    $data["form"]["pelajaran"] = $_POST["pelajaran"];
    $data["form"]["deskripsi"] = $_POST["deskripsi"];
    $data["form"]["harga"] = $_POST["harga"];
    $data["form"]["id_kelas"] = $id_kelas;

    if (updateKelas($data["form"])) {
        showAlertAndRedirect("Berhasil mengubah kelas!", "./index.php");
    } else {
        showAlert("Gagal mengubah kelas.");
    }
}

?>
<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <span class="text-gray-500">edit</span>
        </nav>
    </div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Kelas</h2>
    <div class="mx-auto bg-white p-8 rounded-lg shadow-xl">


        <form action="" method="POST">
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Informasi Kelas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="nama_kelas" class="block text-gray-600">Nama Kelas</label>
                        <input type="text" id="nama_kelas" name="nama_kelas" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama kelas" value="<?= isset($_POST['nama_kelas']) ? htmlspecialchars($_POST['nama_kelas']) : (isset($data['kelas']['nama_kelas']) ? htmlspecialchars($data['kelas']['nama_kelas']) : '') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="tingkat" class="block text-gray-600">Tingkat</label>
                        <select id="tingkat" name="tingkat" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled selected>Pilih Tingkat</option>
                            <?php foreach ($_SESSION['tingkat'] as $item): ?>
                                <option value="<?= $item ?>" <?= isset($_POST["tingkat"]) && $_POST["tingkat"] == $item || $data["kelas"]["tingkat"] == $item ? 'selected' : '' ?>><?= $item ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="pelajaran" class="block text-gray-600">Pelajaran</label>
                        <input type="text" id="pelajaran" name="pelajaran" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama pelajaran" value="<?= isset($_POST['pelajaran']) ? htmlspecialchars($_POST['pelajaran']) : (isset($data['kelas']['pelajaran']) ? htmlspecialchars($data['kelas']['pelajaran']) : '') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="harga" class="block text-gray-600">Harga Kelas</label>
                        <input type="number" id="harga" name="harga" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan harga kelas" value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : (isset($data['kelas']['harga']) ? htmlspecialchars($data['kelas']['harga']) : '') ?>" required>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label for="deskripsi" class="block text-gray-600">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Deskripsikan kelas ini" required><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : (isset($data['kelas']['deskripsi']) ? htmlspecialchars($data['kelas']['deskripsi']) : '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="mb-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white text-xl rounded-lg hover:bg-blue-600 transition duration-200">ubah Kelas</button>
            </div>
        </form>
    </div>
</div>



<?php
$content = ob_get_clean();
require "../MasterAdmin.php";
