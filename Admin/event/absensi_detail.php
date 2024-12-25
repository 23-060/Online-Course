<?php
require_once '../../partials/Config.php';  // Koneksi ke database
session_start();

// Ambil id_acara dari URL
$id_acara = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_acara <= 0) {
    echo "ID acara tidak valid!";
    exit;
}

// Query untuk mengambil nama acara berdasarkan id_acara
$sql_acara = "SELECT judul FROM acara WHERE id_acara = ?";
$stmt_acara = $conn->prepare($sql_acara);

$event_name = "";  // Variable untuk menyimpan nama acara
if ($stmt_acara) {
    $stmt_acara->bind_param('i', $id_acara);
    $stmt_acara->execute();
    $result_acara = $stmt_acara->get_result();
    
    // Jika nama acara ditemukan
    if ($result_acara->num_rows > 0) {
        $row = $result_acara->fetch_assoc();
        $event_name = $row['judul'];  // Ambil nama acara
    } else {
        echo "Acara tidak ditemukan!";
        exit;
    }
    $stmt_acara->close();
} else {
    echo "Terjadi kesalahan saat mengambil data acara!";
    exit;
}

// Query untuk mengambil data peserta berdasarkan id_acara
$sql = "SELECT * FROM peserta_event WHERE id_acara = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $id_acara);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result->num_rows > 0) {
        $peserta_list = [];
        while ($row = $result->fetch_assoc()) {
            $peserta_list[] = $row;
        }
    } else {
        echo "Tidak ada peserta untuk acara ini.";
    }
    $stmt->close();
} else {
    echo "Terjadi kesalahan saat mengambil data peserta!";
    exit;
}
?>
        <p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .navbar {
            background-color: #007a8d;
            padding: 10px 20px;
            color: white;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007a8d;
            color: white;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007a8d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }
        .print-btn {
            background-color: #4CAF50;
        }
    </style>
    <script>
        function printAbsensi() {
            window.print();  // Fungsi untuk membuka dialog cetak
        }
    </script>
</head>
<body>

    <div class="container">
        <!-- Menampilkan Nama Acara (Event) -->
        <h3>Detail Peserta Acara: <?php echo htmlspecialchars($event_name); ?></h3>
        
        <!-- Tombol Cetak -->
        <button class="btn print-btn" onclick="printAbsensi()">Cetak Absensi</button>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($peserta_list)): ?>
                    <?php foreach ($peserta_list as $key => $peserta): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo htmlspecialchars($peserta['nama']); ?></td>
                            <td><?php echo htmlspecialchars($peserta['email']); ?></td>
                            <td><?php echo htmlspecialchars($peserta['telepon']); ?></td>
                            <td><?php echo htmlspecialchars($peserta['alamat']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada peserta untuk acara ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn">Kembali ke Daftar Acara</a>
    </div>
    
</body>
</html>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
