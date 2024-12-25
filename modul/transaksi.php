<?php
ob_start();
session_start();
require_once "../model/pendaftaranKelas.php";
require_once "../model/transaksi.php";
require_once "../autoload/autoload.php";


$id_pengguna = $_SESSION["user"]["id_pengguna"];
$status_transaksi = $GLOBALS["status_transaksi"];

$filter = [
    "id_pengguna" => $id_pengguna
];
$data["transaksi"] = getPendaftaranKelasByFilter($filter);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data["id_transaksi"] = $_POST["id_transaksi"];
    $data["status_transaksi"] = 2;
    if (updateTransaksi(["id_transaksi" => $_POST["id_transaksi"], "status_transaksi" => 2])) {
        $data["id_pendaftaran"] = $_POST["id_pendaftaran"];
        $data["status"] = "Batal";
        updatePendaftaranKelas(["id_pendaftaran" =>  $_POST["id_pendaftaran"], "status" => "Batal"]);
        showAlertAndRedirect("Berhasil Membatalkan Transaksi.", "./index");
    } else {
        showAlert("Gagal Membatalkan Transaksi.");
    }
}
?>

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar Transaksi</h1>

    <div class="overflow-x-auto bg-gray-100 shadow-md rounded-lg">
        <table class="table-auto w-full text-left border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-gray-600 font-semibold">#</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Nama Kelas</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Harga</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Status</th>
                    <th class="px-4 py-2 text-gray-600 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data["transaksi"])): ?>
                    <?php foreach ($data["transaksi"] as $index => $transaksi): ?>
                        <tr class="<?= $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>">
                            <td class="px-4 py-2"><?= $index + 1 ?></td>
                            <td class="px-4 py-2"><?= $transaksi['nama_kelas'] ?></td>
                            <td class="px-4 py-2">Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 text-sm font-medium rounded-lg 
                                    <?= $status_transaksi[$transaksi['status_transaksi']][1] ?>">
                                    <?= $status_transaksi[$transaksi['status_transaksi']][0]  ?>
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <?php if ($transaksi['status_transaksi'] == 0): ?>
                                    <div class="flex items-center gap-2">
                                        <a href="bayar.php?id_transaksi=<?= $transaksi['id_transaksi'] ?>"
                                            class="px-3 py-1 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600">
                                            Bayar
                                        </a>

                                        <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini?');">
                                            <input type="hidden" name="id_transaksi" value="<?= $transaksi['id_transaksi'] ?>">
                                            <input type="hidden" name="id_pendaftaran" value="<?= $transaksi['id_pendaftaran'] ?>">
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600">
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <p>-</p>
                                <?php endif ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-gray-600 py-4">Belum ada transaksi yang tercatat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include '../partials/Master.php'; 
?>