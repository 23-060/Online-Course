<?php ob_start(); ?>
<?php 
session_start();
require "../partials/Config.php";

$userid = null;
$id_pengguna = null;

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['nama'];
    $id_pengguna = $_SESSION['user']['id_pengguna'];
}

require "./../model/kelas.php";
require "./../model/pendaftaranKelas.php";
require "./../autoload/autoload.php";

if (isset($_POST["tingkat"]) === "POST") {
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

    if ($id_pengguna !== null) {
        $filter["id_pengguna"] = $id_pengguna;
    }

    $data["kelas"] = getKelasByFilter($filter);
} else {
    $data["kelas"] = $id_pengguna !== null ? getKelasByFilter(["id_pengguna" => $id_pengguna]) : [];
}

$data["daftarTingkat"] = getAllTingkatFromKelas();
$data["daftarPelajaran"] = getAllPelajaranFromKelas();
$data["daftarPengajar"] = getAllPengajarFromKelas();
?>

<div class="px-[50px] bg-slate-300 w-[100%]">
    <div class="flex justify-around">
        <?php if(isset($_SESSION['user'])): ?>
            <div class="w-400px self-center">
                <p class="text-[40px] font-bold">Welcome To Sipintar, <?= $_SESSION['user']['nama'] ?></p>
                <p class="text-[25px]">Pleased to meet you! We have prepared many interesting features for you.</p>
                <button onclick="window.location.href='#kelas'" class="px-3 py-2 mt-4 border-2 border-black rounded-lg hover:bg-black hover:text-white hover:transition-all transition-all active:scale-110 scale-100">Get Started <i class="bi bi-arrow-right"></i></button>
            </div>
            <div class="w-400px">
                <img class="w-[500px]" src="../partials/assets/lisa.png" alt="avatar">
            </div>
        <?php else: ?>
            <div class="w-400px self-center">
                <p class="text-[40px] font-bold">New to Sipintar? Welcome.</p>
                <p class="text-[25px]">Enjoy discounts of up to 50% for your first transaction.</p>
                <button onclick="window.location.href='/register/register'" class="px-3 py-2 mt-4 border-2 border-black rounded-lg hover:bg-black hover:text-white hover:transition-all transition-all active:scale-110 scale-100">Sign-In Now! <i class="bi bi-arrow-right"></i></button>
            </div>
            <div class="w-400px">
                <img class="w-[500px]" src="../partials/assets/rinko.png" alt="avatar">
            </div>
        <?php endif ?>
    </div>
</div>

<section id="kelas">

<div class="mt-[50px] bg-gray-100 container mx-auto p-6">

    <h2 class="text-xl font-bold text-gray-800">Belajar Jadi Lebih Mudah</h2>
    <p class="text-gray-600 mb-10">Jelajahi kelas terbaik yang sesuai dengan minat dan kebutuhan Anda.</p>

    <div class="flex mt-6">
        <aside class="w-1/4 bg-white p-4 rounded-lg shadow-md h-max border border-black">
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

        <div class="w-3/4 ml-6 grid grid-cols-2 gap-4 border border-black">
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
                            <a href="../modul/info.php?id=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-3 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                                Lihat Kelas
                            </a>
                            <?php if ($kelas["status_transaksi"] === 1): ?>
                                <a href="./../materi.php?id_kelas=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    Lihat Materi
                                </a>
                            <?php elseif ($kelas["status_transaksi"] === 0): ?>
                                <a href="../modul/transaksi.php" class="flex-1 text-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    Menunggu Pembayaran
                                </a>
                            <?php elseif ($kelas["status_transaksi"] === 0): ?>
                                <a href="../modul/transaksi.php" class="flex-1 text-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    Menunggu Pembayaran
                                </a>
                            <?php else: ?>
                                <a href="../modul/daftar.php?id_kelas=<?= $kelas["id_kelas"] ?>" class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Daftar Sekarang
                                </a>
                            <?php endif; ?>
                                
                        </div>
                    </div>
                <?php endforeach ?>
            <?php else: ?>
                <?php if(isset($_SESSION['user'])): ?>
                    <div class="col-span-2 text-center p-6 bg-white rounded-lg shadow-md h-max">
                        <p class="text-gray-600 text-lg">Oops, tidak ada kelas yang ditemukan.</p>
                        <p class="text-gray-400 mt-2">Cobalah mengubah filter atau kembali nanti.</p>
                <?php else: ?>
                        <p class="text-red-400 mt-2">Tolong Sign-In Untuk Memilih Kelas. <a href="../login/" class="text-blue-400">Sign-In</a></p>
                <?php endif; ?>                    
                    </div>
            <?php endif ?>
        </div>
    </div>
</div>
</section>


<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>