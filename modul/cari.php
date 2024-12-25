<?php
ob_start();

require "./../model/kelas.php";
require "./../model/pendaftaranKelas.php";
require "./../autoload/autoload.php";

$id_pengguna = $_SESSION["id_pengguna"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tingkat = $_POST["tingkat"];
    $pelajaran = $_POST["pelajaran"];
    $id_pengajar = $_POST["id_pengajar"];
    $filter = [];

    if ($tingkat !== '') {
        $filter["tingkat"] = $tingkat;
    }

    if ($pelajaran !== '') {
        $filter["pelajaran"] = $pelajaran;
    }

    if ($id_pengajar !== '') {
        $filter["id_pengajar"] = $id_pengajar;
    }

    $filter["id_pengguna"] = $id_pengguna;

    $data["kelas"] = getKelasByFilter($filter);
} else {
    $data["kelas"] = getKelasByFilter(["id_pengguna" => $id_pengguna]);
}

$data["daftarTingkat"] = getAllTingkatFromKelas();
$data["daftarPelajaran"] = getAllPelajaranFromKelas();
$data["daftarPengajar"] = getAllPengajarFromKelas();
?>


<div class="bg-gray-100 container mx-auto p-6">

    <h2 class="text-xl font-bold text-gray-800">Belajar Jadi Lebih Mudah</h2>
    <p class="text-gray-600">Jelajahi kelas terbaik yang sesuai dengan minat dan kebutuhan Anda.</p>

    <div class="flex mt-6">
        <aside class="w-1/4 bg-white p-4 rounded-lg shadow-md h-max">
            <h3 class="font-semibold text-gray-800">Filter</h3>
            <form class="mt-4" method="POST" action="">
                <div class="mt-4">
                    <label for="tingkat" class="block text-sm font-medium text-gray-600">Jenjang</label>
                    <select
                        id="tingkat"
                        name="tingkat"
                        class="w-full mt-1 px-3 py-2 border-gray-300 bg-white text-gray-800 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="" selected>Pilih jenjang</option>
                        <?php foreach ($data["daftarTingkat"] as $value): ?>
                            <option value="<?= $value["tingkat"] ?>" <?= isset($_POST["tingkat"]) && $value["tingkat"] == $_POST["tingkat"] ? "selected" : ""  ?>><?= $value["tingkat"] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="pelajaran" class="block text-sm font-medium text-gray-600">Mata Pelajaran</label>
                    <select
                        id="pelajaran"
                        name="pelajaran"
                        class="w-full mt-1 px-3 py-2 border-gray-300 bg-white text-gray-800 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="" selected>Pilih mata pelajaran</option>
                        <?php foreach ($data["daftarPelajaran"] as $value): ?>
                            <option value="<?= $value["pelajaran"] ?>" <?= isset($_POST["pelajaran"]) && $value["pelajaran"] == $_POST["pelajaran"] ? "selected" : ""  ?>><?= $value["pelajaran"] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="id_pengajar" class="block text-sm font-medium text-gray-600">Nama Pengajar</label>
                    <select
                        id="id_pengajar"
                        name="id_pengajar"
                        class="w-full mt-1 px-3 py-2 border-gray-300 bg-white text-gray-800 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="" selected>Pilih pengajar</option>
                        <?php foreach ($data["daftarPengajar"] as $value): ?>
                            <option value="<?= $value["id_pengajar"] ?>" <?= isset($_POST["id_pengajar"]) && $value["id_pengajar"] == $_POST["id_pengajar"] ? "selected" : ""  ?>><?= $value["nama"] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </aside>

        <div class="w-3/4 ml-6 grid grid-cols-2 gap-4">
            <?php if (isset($data["kelas"]) && count($data["kelas"]) > 0): ?>
                <?php foreach ($data["kelas"] as $kelas): ?>
                    <div class="bg-white rounded-lg shadow-md p-4 h-max">
                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($kelas["nama_kelas"]) ?></h3>
                        <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($kelas["deskripsi"]) ?></p>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-sm text-gray-600">Tingkat:</p>
                                <span class="px-2 py-1 text-sm text-gray-800"><?= htmlspecialchars($kelas["tingkat"]) ?></span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pelajaran:</p>
                                <span class="px-2 py-1 text-sm text-gray-800"><?= htmlspecialchars($kelas["pelajaran"]) ?></span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pengajar:</p>
                                <span class="px-2 py-1 text-sm text-gray-800"><?= htmlspecialchars($kelas["nama_pengajar"]) ?></span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Harga:</p>
                                <span class="px-2 py-1 text-sm text-gray-800">Rp <?= number_format($kelas["harga"], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <a href="./info.php?id=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-3 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                                Lihat Kelas
                            </a>
                            <?php if ($kelas["status_transaksi"] === 1): ?>
                                <a href="./../materi.php?id_kelas=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    Lihat Materi
                                </a>
                            <?php elseif ($kelas["status_transaksi"] === 0): ?>
                                <a href="#" class="flex-1 text-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    Menunggu Pembayaran
                                </a>
                            <?php else: ?>
                                <a href="./daftar.php?id_kelas=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Daftar Sekarang
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="col-span-2 text-center p-6 bg-white rounded-lg shadow-md h-max">
                    <p class="text-gray-600 text-lg">Oops, tidak ada kelas yang ditemukan.</p>
                    <p class="text-gray-400 mt-2">Cobalah mengubah filter atau kembali nanti.</p>

                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require "./../layout.php";
?>