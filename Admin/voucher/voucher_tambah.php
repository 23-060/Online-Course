<?php
ob_start();
require "../../partials/Config.php";
session_start();

// Cek apakah user sudah login dan admin
if(isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if($userid['peran'] != 1) {
        echo "<script>alert(' ADMIN DATANG !! '); window.location.href = '../index.php'</script>";
    }
}

// Proses penyimpanan data voucher baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_voucher = $_POST['kode_voucher'];
    $diskon = $_POST['diskon'];
    $tanggal_kadaluarsa = $_POST['tanggal_kadaluarsa'];
    $deskripsi = $_POST['deskripsi'];
    $kuota = $_POST['kuota'];

    // Query untuk memasukkan data ke database
    $sql = "INSERT INTO voucher (kode_voucher, diskon, tanggal_kadaluarsa, deskripsi, kuota) 
            VALUES ('$kode_voucher', '$diskon', '$tanggal_kadaluarsa', '$deskripsi', '$kuota')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Voucher berhasil ditambahkan!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan!');</script>";
    }
}
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
?>
        <p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Voucher Diskon</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .navbar h2 {
            margin: 0;
            font-size: 20px;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        .navbar ul li {
            display: inline;
        }

        .navbar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar ul li a:hover {
            background-color: #005f70;
        }

        .admin-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .admin-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-title h2 {
            color: #444;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input[type="submit"] {
            background-color: #007a8d;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #005f70;
        }

                    button {
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

      button:hover {
      background-color: #005f70;
    }

    .btn-back {
      display: inline-block;
      margin-bottom: 15px;
      background-color: #555;
      color: white;
      text-decoration: none;
      padding: 8px 15px;
      border-radius: 5px;
      font-size: 14px;
      font-weight: bold;
    }

    .btn-back:hover {
      background-color: #333;
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Profil</a></li>
            <li><a href="../event/index.php">Event</a></li>
            <li><a href="../artikel/index.php">Artikel</a></li>
            <li><a href="../voucher/index.php">Voucher</a></li>
        </ul>
    </div>

    <!-- Admin Container -->
    <div class="admin-container">
        <div class="admin-title">
            <h2>Tambah Voucher Baru</h2>
        </div>

        <a href="index.php" class="btn-back">&larr; Kembali</a>
        <form action="voucher_tambah.php" method="POST">
            <!-- Kode Voucher -->
            <div class="form-group">
                <label for="kode_voucher">Kode Voucher</label>
                <input type="text" id="kode_voucher" name="kode_voucher" required>
            </div>

            <!-- Diskon -->
            <div class="form-group">
                <label for="diskon">Diskon (%)</label>
                <input type="number" id="diskon" name="diskon" required min="0" max="100" step="0.01">
            </div>

            <!-- Tanggal Kadaluarsa -->
            <div class="form-group">
                <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
                <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" required>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
            </div>

            <!-- Kuota -->
            <div class="form-group">
                <label for="kuota">Kuota</label>
                <input type="number" id="kuota" name="kuota" required min="1">
            </div>

            <!-- Submit -->
            <div class="form-group">
                <input type="submit" value="Tambah Voucher">
            </div>
        </form>
    </div>

</body>
</html>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
