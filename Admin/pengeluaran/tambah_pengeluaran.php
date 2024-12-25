<?php
// Mulai output buffering
ob_start();

// Memuat konfigurasi database
require_once '../../partials/Config.php';
session_start();

// Cek apakah user sudah login dan memiliki peran admin
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('ADMIN DATANG !!'); window.location.href = '../index.php';</script>";
        exit();
    }
}

// Proses form jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_admin = $_SESSION['user']['id_pengguna'];
    $tanggal_pengeluaran = $_POST['tanggal_pengeluaran'];
    $keterangan = $_POST['keterangan'];
    $debet = $_POST['debet'];

    // Query untuk menambahkan data pengeluaran
    $query = "INSERT INTO pengeluaran (id_admin, tanggal_pengeluaran, keterangan, debet) 
              VALUES ('$id_admin', '$tanggal_pengeluaran', '$keterangan', '$debet')";

    // Eksekusi query dan berikan umpan balik ke pengguna
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pengeluaran berhasil ditambahkan!'); window.location.href = 'pengeluaran.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan pengeluaran: " . mysqli_error($conn) . "');</script>";
    }
}
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
// Ambil data pengguna (jika diperlukan untuk fitur tambahan di masa depan)
$pengguna = mysqli_query($conn, "SELECT * FROM pengguna");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-control {
            border: 2px solid #0d6efd;
            padding: 10px;
            font-size: 16px;
        }
        .form-control:focus {
            box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
            border-color: #0d6efd;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #084298;
        }
    </style>
</head>
<body>
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
    <div class="container mt-4">
        <!-- Header -->
        <div class="bg-primary text-white text-center p-3 mb-4 rounded">
            <h3>Tambah Pengeluaran</h3>
        </div>

        <!-- Form Tambah Pengeluaran -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="tanggal_pengeluaran" class="form-label">Tanggal Pengeluaran</label>
                <input type="date" class="form-control" id="tanggal_pengeluaran" name="tanggal_pengeluaran" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="debet" class="form-label">Debet</label>
                <input type="number" class="form-control" id="debet" name="debet" required>
            </div>
            <div class="d-flex ">
                <button type="submit" class="btn btn-primary mr-5">Simpan</button>
                <a href="pengeluaran.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Selesaikan output buffering dan tampilkan konten di dalam template
$content = ob_get_clean();
include '../MasterAdmin.php';
?>