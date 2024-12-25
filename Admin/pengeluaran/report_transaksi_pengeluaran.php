<?php
ob_start();
require "../../partials/Config.php";

$data_harga = [];
$data = [];
$jumlah_pelanggan = 0;
$jumlah_pendapatan = 0;
$from = "";
$to = "";

if ((isset($_GET['submit']) || isset($_GET['export'])) && !empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];

    $total = mysqli_query($conn, "SELECT tanggal_pengeluaran, debet FROM pengeluaran WHERE tanggal_pengeluaran BETWEEN '$from' AND '$to' ORDER BY tanggal_pengeluaran");
    
    while ($row_total = mysqli_fetch_assoc($total)) {
        $data[] = $row_total["tanggal_pengeluaran"];
        $data_harga[] = $row_total["debet"];
        $jumlah_pelanggan++;
        $jumlah_pendapatan += $row_total['debet'];
    }

    if (isset($_GET['export'])) {
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=data_transaksi.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th>No.</th><th>Tanggal</th><th>Total</th></tr>";
        foreach ($data as $idx => $tanggal) {
            echo "<tr>";
            echo "<td>" . ($idx + 1) . "</td>";
            echo "<td>" . $tanggal . "</td>";
            echo "<td>" . $data_harga[$idx] . "</td>";
            echo "</tr>";
        }
        echo "<tr><td colspan='2'>Jumlah Pelanggan</td><td>$jumlah_pelanggan</td></tr>";
        echo "<tr><td colspan='2'>Jumlah Pendapatan</td><td>$jumlah_pendapatan</td></tr>";
        echo "</table>";
        exit;
    }
} else {
    if (isset($_GET['submit']) || isset($_GET['export'])) {
        echo "<p class='text-danger'>Tolong Isi tanggal From dan To nya</p>";
    }
}
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        @media print{
            .no_print {
                display: none;
            }
        }
    </style>
</head>
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
<body class="bg-light">
    <div class="container">
        <form action="" method="get" class="row g-3 mb-4 no_print">
            <div class="col-auto">
                <label for="from" class="form-label">From:</label>
                <input type="date" id="from" name="from" value="<?= $from; ?>" class="form-control">
            </div>
            <div class="col-auto">
                <label for="to" class="form-label">To:</label>
                <input type="date" name="to" id="to" value="<?= $to; ?>" class="form-control">
            </div>
            <div class="col-auto align-self-end">
                <button type="submit" name="submit" class="btn btn-primary">Tampilkan</button>
                <button type="submit" name="export" class="btn btn-primary">Export Excel</button>
                <button onclick="window.print()" class="btn btn-primary">Cetak</button>
            </div>
        </form>

        <?php if (isset($_GET['submit']) && !empty($data)): ?>
            <div class="card mb-4">
                <div class="card-body" style="height: 500px;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $idx => $tanggal): ?>
                                <tr>
                                    <td><?= $idx + 1; ?></td>
                                    <td>Rp<?= number_format($data_harga[$idx], 0, ',', '.'); ?></td>
                                    <td><?= date("d M Y", strtotime($tanggal)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Jumlah Pelanggan</th>
                                <th>Jumlah Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $jumlah_pelanggan; ?> Orang</td>
                                <td>Rp<?= number_format($jumlah_pendapatan, 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($data); ?>,
                datasets: [{
                    label: 'Total',
                    data: <?= json_encode($data_harga); ?>,
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
</body>
</html>
<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>