<?php
ob_start();
require "../../partials/Config.php";
session_start();

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    if ($userid['peran'] != 1) {
        echo "<script>alert('ADMIN DATANG!!'); window.location.href = '../index.php'</script>";
    }
}

// Ambil data voucher dari database
$sql = "SELECT * FROM voucher";
$result = $conn->query($sql);

// Simpan data voucher ke dalam array
$vouchers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vouchers[] = $row;
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
    <title>Kelola Voucher Diskon</title>
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
            max-width: 1000px;
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

        .btn-add {
            background-color: #007a8d;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: block;
            margin: 10px auto;
            text-align: center;
            text-decoration: none;
            max-width: 200px;
        }

        .btn-add:hover {
            background-color: #005f70;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .admin-table th, .admin-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .admin-table th {
            background-color: #007a8d;
            color: white;
        }

        .action-buttons a {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 12px;
            color: white;
            margin-right: 5px;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
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
            <h2>Kelola Voucher Diskon</h2>
            <p>Tambah, Edit, atau Hapus Voucher Diskon</p>
        </div>

        <!-- Button Tambah -->
        <a href="voucher_tambah.php" class="btn-add">+ Tambah Voucher Baru</a>

        <!-- Table Voucher -->
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Voucher</th>
                    <th>Diskon</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Deskripsi</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vouchers)): ?>
                    <?php foreach ($vouchers as $key => $voucher): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo htmlspecialchars($voucher['kode_voucher']); ?></td>
                            <td><?php echo htmlspecialchars($voucher['diskon']); ?>%</td>
                            <td><?php echo htmlspecialchars($voucher['tanggal_kadaluarsa']); ?></td>
                            <td><?php echo htmlspecialchars($voucher['deskripsi']); ?></td>
                            <td><?php echo htmlspecialchars($voucher['kuota']); ?></td>
                            <td class="action-buttons">
                                <a href="voucher_edit.php?id=<?php echo $voucher['id_voucher']; ?>" class="btn-edit">Edit</a>
                                <a href="proses_hapus_voucher.php?id=<?php echo $voucher['id_voucher']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada voucher tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
