<?php
ob_start();
session_start();
require_once "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";


$role = $_SESSION['user']["peran"];

$id_pengajar = $_SESSION['user']["id_pengguna"];

if ($role != 2) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "./../../modul/cari.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data["id_pengajar"] = $id_pengajar;
    $data["nama_kelas"] = $_POST["nama_kelas"];
    $data["tingkat"] = $_POST["tingkat"];
    $data["pelajaran"] = $_POST["pelajaran"];
    $data["deskripsi"] = $_POST["deskripsi"];
    $data["harga"] = $_POST["harga"];

    if (createKelas($data)) {
        showAlertAndRedirect("Kelas Berhasil Dibuat!", "./index.php");
    } else {
        showAlert("Gagal membuat kelas baru.");
    }
}


?>
<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <span class="text-gray-500">Tambah</span>
        </nav>
    </div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kelas Baru</h2>
    <div class="mx-auto bg-white p-8 rounded-lg shadow-xl">

        <form action="" method="POST">
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Informasi Kelas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="nama_kelas" class="block text-gray-600">Nama Kelas</label>
                        <input type="text" id="nama_kelas" name="nama_kelas" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama kelas" value="<?= isset($_POST['nama_kelas']) ? htmlspecialchars($_POST['nama_kelas']) : '' ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="tingkat" class="block text-gray-600">Tingkat</label>
                        <select id="tingkat" name="tingkat" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled selected>Pilih Tingkat</option>
                            <?php foreach ($GLOBALS['tingkat'] as $item): ?>
                                <option value="<?= $item ?>" <?= isset($_POST["tingkat"]) && $_POST["tingkat"] == $item ? 'selected' : '' ?>><?= $item ?></option>
                            <?php endforeach; ?>
                        </select>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="pelajaran" class="block text-gray-600">Pelajaran</label>
                        <input type="text" id="pelajaran" name="pelajaran" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama pelajaran" value="<?= isset($_POST['pelajaran']) ? htmlspecialchars($_POST['pelajaran']) : '' ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="harga" class="block text-gray-600">Harga Kelas</label>
                        <input type="number" id="harga" name="harga" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan harga kelas" value="<?= isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : '' ?>" required>
                    </div>
                    <div class="mb-4 md:col-span-2">
                        <label for="deskripsi" class="block text-gray-600">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Deskripsikan kelas ini" required><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
                    </div>
                </div>
            </div>

            <div class="mb-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white text-xl rounded-lg hover:bg-blue-600 transition duration-200">Tambah Kelas</button>
            </div>
        </form>
    </div>
</div>


<?php
$content = ob_get_clean();
require "../MasterAdmin.php";
