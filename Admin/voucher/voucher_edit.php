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

// Ambil ID voucher dari URL
$id_voucher = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_voucher <= 0) {
    echo "ID voucher tidak valid!";
    exit;
}

// Ambil data voucher berdasarkan ID
$sql = "SELECT * FROM voucher WHERE id_voucher = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $id_voucher);
    $stmt->execute();
    $result = $stmt->get_result();
    $voucher = $result->fetch_assoc();
    if (!$voucher) {
        echo "Voucher tidak ditemukan!";
        exit;
    }
    $stmt->close();
} else {
    echo "Terjadi kesalahan saat mengambil data voucher.";
    exit;
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_voucher = trim($_POST['kode_voucher']);
    $diskon = trim($_POST['diskon']);
    $tanggal_kadaluarsa = trim($_POST['tanggal_kadaluarsa']);
    $kuota = trim($_POST['kuota']);
    $deskripsi = trim($_POST['deskripsi']);

    // Validasi data
    if (empty($kode_voucher) || empty($diskon) || empty($tanggal_kadaluarsa) || empty($kuota)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!is_numeric($diskon) || $diskon < 0 || $diskon > 100) {
        $error = 'Diskon harus berupa angka antara 0-100!';
    } elseif (!is_numeric($kuota) || $kuota < 0) {
        $error = 'Kuota harus berupa angka positif!';
    } else {
        // Update data voucher di database
        $sql = "UPDATE voucher SET kode_voucher = ?, diskon = ?, tanggal_kadaluarsa = ?, kuota = ?, deskripsi = ? WHERE id_voucher = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sdsssi', $kode_voucher, $diskon, $tanggal_kadaluarsa, $kuota, $deskripsi, $id_voucher);
            if ($stmt->execute()) {
                echo "<script>alert('Voucher berhasil diperbarui!'); window.location.href = 'index.php';</script>";
                exit;
            } else {
                $error = 'Terjadi kesalahan saat memperbarui voucher.';
            }
            $stmt->close();
        } else {
            $error = 'Terjadi kesalahan pada server.';
        }
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
    <title>Edit Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007a8d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background-color: #005f70;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Voucher</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="kode_voucher">Kode Voucher</label>
            <input type="text" name="kode_voucher" id="kode_voucher" value="<?php echo htmlspecialchars($voucher['kode_voucher']); ?>" required>

            <label for="diskon">Diskon (%)</label>
            <input type="number" name="diskon" id="diskon" value="<?php echo htmlspecialchars($voucher['diskon']); ?>" required>

            <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa</label>
            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" value="<?php echo htmlspecialchars($voucher['tanggal_kadaluarsa']); ?>" required>

            <label for="kuota">Kuota</label>
            <input type="number" name="kuota" id="kuota" value="<?php echo htmlspecialchars($voucher['kuota']); ?>" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4"><?php echo htmlspecialchars($voucher['deskripsi']); ?></textarea>

            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="index.php" class="btn">Kembali</a>
        </form>
    </div>
</body>
</html>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>