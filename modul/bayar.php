<?php
ob_start();
session_start();
require_once "../model/kelas.php";
require_once "../model/pendaftaranKelas.php";
require_once "../model/transaksi.php";
require_once "../autoload/autoload.php";


$metode_pembayaran = $GLOBALS["metode_pembayaran"];
$status_transaksi = $GLOBALS["status_transaksi"];

$id_transaksi = $_GET["id_transaksi"];
$filter = [
    "id_transaksi" => $id_transaksi
];
$data["kelas"] = getPendaftaranKelasByFilter($filter)[0];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (updateTransaksi(["metode_pembayaran" => $_POST["metode_pembayaran"], "status_transaksi" => 1, "id_transaksi" => $id_transaksi])) {
        showAlertAndRedirect("Berhasil Melakukan Pembayaran", "../");
    } else {
        showAlertAndRedirect("Gagal Melakukan Transaksi", "./transaksi.php");
    }
}
?>
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Pembayaran</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Informasi Transaksi</h2>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <!-- Informasi Kelas -->
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Nama Kelas:</p>
                <p class="text-base text-gray-800 font-medium"><?= htmlspecialchars($data["kelas"]['nama_kelas']) ?></p>

                <p class="text-sm text-gray-600 mt-2">Deskripsi Kelas:</p>
                <p class="text-base text-gray-800 font-medium"><?= htmlspecialchars($data["kelas"]['deskripsi']) ?></p>

                <p class="text-sm text-gray-600 mt-2">Harga:</p>
                <p class="text-base text-gray-800 font-medium">Rp <?= number_format($data["kelas"]['harga'], 0, ',', '.') ?></p>
            </div>

            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Nama Pengguna:</p>
                <p class="text-base text-gray-800 font-medium"><?= htmlspecialchars($data["kelas"]['nama_pengguna']) ?></p>

                <p class="text-sm text-gray-600 mt-2">Status Transaksi:</p>
                <span class="px-3 py-1 text-sm font-medium rounded-lg <?= $status_transaksi[$data["kelas"]['status_transaksi']][1] ?>">
                    <?= htmlspecialchars($status_transaksi[$data["kelas"]['status_transaksi']][0]) ?>
                </span>
            </div>
        </div>
    </div>


    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h2>
        <form action="" method="POST" class="mt-4">
            <label for="metode_pembayaran" class="text-sm text-gray-600 mb-4">Pilih metode pembayaran yang ingin Anda gunakan untuk menyelesaikan pembayaran untuk kelas <strong><?= htmlspecialchars($data["kelas"]['nama_kelas']) ?></strong>.</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="w-full p-3 border border-gray-300 rounded-md mb-4" required>
                <option value="" selected disabled>Pilih Metode Pembayaran</option>
                <?php foreach ($metode_pembayaran as $key => $value): ?>
                    <option value="<?= $key ?>"><?= $value ?></option>
                <?php endforeach ?>
            </select>

            <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Bayar
            </button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>