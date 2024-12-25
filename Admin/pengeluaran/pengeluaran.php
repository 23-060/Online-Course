<?php 
ob_start();
require "../../partials/Config.php";
session_start();
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user'];
    if($userid['peran'] != 1){
        echo "<script>alert(' ADMIN DATANG !! '); window.location.href = '../index.php'</script>";
    }

}
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
$transaksi = mysqli_query($conn, "SELECT * FROM pengeluaran JOIN pengguna ON pengeluaran.id_admin = pengguna.id_pengguna");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 20px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
<body class="bg-light">
    <div class="container mt-4">
        <div class="header">Data Pengeluaran</div>
        <div class="mb-3">
            <a href="report_transaksi_pengeluaran.php" class="btn btn-primary">Lihat Laporan Pengeluaran</a>
            <a href="tambah_pengeluaran.php" class="btn btn-primary">Tambah Pengeluaran</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>No.</th>
                        <th>Yang Melakukan</th>
                        <th>Tanggal Pengeluaran</th>
                        <th>Keterangan</th>
                        <th>Debet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transaksi as $index => $row): ?>
                        <tr>
                            <td><?= $index+1 ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['tanggal_pengeluaran']; ?></td>
                            <td><?= $row['keterangan']; ?></td>
                            <td>Rp.<?= $row['debet']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>
