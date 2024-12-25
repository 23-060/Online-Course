<?php
ob_start();
require "../partials/Config.php";
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['nama'];
}

// Ambil data voucher dari database
$sql = "SELECT * FROM voucher WHERE kuota > 0 ORDER BY tanggal_kadaluarsa ASC";
$result = $conn->query($sql);

$vouchers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vouchers[] = $row;
    }
}
?>

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

        .voucher-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .voucher-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .voucher-title h2 {
            color: #444;
        }

        .voucher {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f7f7f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .voucher-details {
            flex: 1;
        }

        .voucher-code {
            font-weight: bold;
            color: #007a8d;
        }

        .voucher-expiry {
            font-size: 14px;
            color: #888;
        }

        .voucher-kuota {
            font-size: 14px;
            color: #888;
        }

        .btn-copy {
            background-color: #007a8d;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-copy:hover {
            background-color: #005f70;
        }
    </style>
    

    <!-- Voucher Container -->
    <div class="voucher-container">
        <div class="voucher-title">
            <h2>Voucher Diskon</h2>
            <p>Gunakan kode voucher berikut untuk mendapatkan diskon menarik!</p>
        </div>

        <!-- Voucher List -->
        <?php if (!empty($vouchers)): ?>
            <?php foreach ($vouchers as $voucher): ?>
                <div class="voucher">
                    <div class="voucher-details">
    <p>
        <span class="voucher-code"><?php echo htmlspecialchars($voucher['kode_voucher']); ?></span> - 
        <?php echo htmlspecialchars($voucher['deskripsi']); ?>
    </p>
    <p class="voucher-expiry">
        Berlaku hingga: <?php echo date("d F Y", strtotime($voucher['tanggal_kadaluarsa'])); ?>
    </p>
    <p class="voucher-quota">
        Kuota tersisa: <?php echo htmlspecialchars($voucher['kuota']); ?>
    </p>
</div>

                    <button class="btn-copy" onclick="copyVoucher('<?php echo htmlspecialchars($voucher['kode_voucher']); ?>', '<?php echo $voucher['id_voucher']; ?>')">Salin Kode</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center;">Tidak ada voucher tersedia saat ini.</p>
        <?php endif; ?>
    </div>

    <script>
        function copyVoucher(code, id) {
            navigator.clipboard.writeText(code).then(() => {
                alert(`Kode voucher "${code}" telah disalin ke clipboard!`);

                // Update kuota secara dinamis
                const kuotaElement = document.getElementById(`kuota-${id}`);
                let currentKuota = parseInt(kuotaElement.textContent);

                if (currentKuota > 0) {
                    currentKuota--;
                    kuotaElement.textContent = currentKuota;

                    // Kirim update kuota ke server
                    fetch('update_kuota.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            kuota: currentKuota
                        })
                    });
                }
            
            });
        }
    </script>
</body>
</html>

<?php
$content = ob_get_clean();
include '../partials/Master.php';
?>
