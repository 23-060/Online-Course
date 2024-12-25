<?php
ob_start();
session_start();
require_once "./../model/kelas.php";
require_once "./../model/materi.php";
require_once "./../model/quiz.php";
require_once "./../model/pendaftaranKelas.php";
require_once "./../model/voucher.php";
require_once "./../autoload/autoload.php";


$id_kelas = $_GET["id_kelas"];
$id_pengguna = $_SESSION['user']["id_pengguna"];

$data["kelas"] = getKelasById($id_kelas);
$data["quiz"] = getQuizByFilter(["id_kelas" => $id_kelas]);
$data["matero"] = getMateriByFilter(["id_kelas" => $id_kelas]);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["REDEEM_VOUCHER"])) {
        $data["kode"] = $_POST["kode"];
        $voucher = getVoucher($data);
        if (!empty($voucher)) {
            if (!($voucher["tanggal_kadaluarsa"] < date("Y-m-d"))) {
                $data["id_voucher"] = $voucher["id_voucher"];
                $data["harga_diskon"] = $data["kelas"]["harga"] - ($data["kelas"]["harga"] * $voucher["diskon"] / 100);
                showAlert("Berhasil Menukar Voucher");
            } else {
                showAlert("Voucher sudah kadaluarsa");
            }
        } else {
            showAlert("Voucher Tidak Ditemukan");
        }
    } elseif (isset($_POST["DAFTAR_KELAS"])) {
        $data["id_kelas"] = $id_kelas;
        $data["id_pengguna"] = $id_pengguna;
        $data["id_voucher"] = !empty($_POST["id_voucher"]) ? $_POST["id_voucher"] : null;
        $data["harga"] = $_POST["harga"];
        $data["metode_pembayaran"] = null;

        if (createPendaftaranKelas($data)) {
            showAlertAndRedirect("Berhasil Mendaftar Silahkan Lanjut Ke Pembayaran.", "../");
        } else {
            showAlert("Gagal Mendaftar Kelas");
        }
    }
}
?>
<div class="container mx-auto p-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Pendaftaran Kelas</h2>

        <?php if ($data["kelas"]): ?>
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-semibold text-gray-800"><?= $data["kelas"]['nama_kelas'] ?></h3>
                <p class="text-gray-600 mt-2"><?= $data["kelas"]['deskripsi'] ?></p>
                <p class="mt-2"><strong>Pengajar:</strong> <?= $data["kelas"]['nama_pengajar'] ?></p>
                <p class="mt-2"><strong>Email Pengajar:</strong> <a href="mailto:<?= $data["kelas"]['email_pengajar'] ?>" class="text-blue-500"><?= $data["kelas"]['email_pengajar'] ?></a></p>
                <p class="mt-2"><strong>Harga:</strong> <span class="<?= isset($data["harga_diskon"]) ? "line-through text-gray-500" : "" ?>">Rp <?= number_format($data["kelas"]['harga'], 0, ',', '.') ?></span> <?= isset($data["harga_diskon"]) ? "Rp" . number_format($data["harga_diskon"], 0, ',', '.') : "" ?></p>

                <div class="mt-4 text-gray-700">
                    <h4 class="text-md font-semibold">Apa yang Akan Anda Pelajari:</h4>
                    <p>
                        <?php
                        if (!empty($data['matero'])) {
                            foreach ($data['matero'] as $materi) {
                                echo "<strong>" . $materi['judul_materi'] . ":</strong> " . $materi['deskripsi'] . "<br>";
                            }
                        } else {
                            echo "Materi untuk kelas ini sedang disiapkan.";
                        }
                        ?>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <p class="text-red-600">Kelas tidak ditemukan!</p>
            <?php exit(); ?>
        <?php endif; ?>

        <div class="bg-white rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Voucher Diskon (Opsional)</h3>
            <form action="" method="POST">
                <label for="kode" class="block text-gray-700">Masukkan Kode Voucher</label>
                <div class="mb-4 flex gap-4">
                    <input type="text" name="kode" id="kode" class="w-full p-3 border border-gray-300 rounded-md 
                <?= isset($data['harga_diskon']) ? 'bg-gray-200 cursor-not-allowed' : 'bg-white' ?>"
                        placeholder="Masukkan kode voucher jika ada"
                        <?= isset($data["harga_diskon"]) ? "disabled" : "" ?>
                        value="<?= $_POST["kode"] ?? "" ?>" />

                    <button type="submit" name="REDEEM_VOUCHER"
                        class="w-52 px-4 py-2 rounded-lg 
                <?= isset($data['harga_diskon']) ? 'bg-gray-400 text-gray-600 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600' ?>"
                        <?= isset($data["harga_diskon"]) ? "disabled" : "" ?>>
                        Redeem Voucher
                    </button>
                </div>
            </form>
            <form action="" method="POST">
                <input type="hidden" name="id_voucher" value="<?= $data["id_voucher"] ?? null ?>">
                <input type="hidden" name="harga" value="<?= $data["harga_diskon"] ?? $data["kelas"]['harga']  ?>">

                <div class="mb-4">
                    <button type="submit" name="DAFTAR_KELAS" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Daftar Sekarang</button>
                </div>
            </form>
        </div>



    </div>
</div>


<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>