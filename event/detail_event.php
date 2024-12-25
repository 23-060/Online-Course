<?php
ob_start();
require "../partials/Config.php";
session_start();

// Cek apakah user sudah login
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user']['nama'];
}

// Ambil ID event dari URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($event_id > 0) {
    // Ambil data event berdasarkan ID
    $sql = "SELECT * FROM acara WHERE id_acara = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();

    if (!$event) {
        // Jika event tidak ditemukan
        header("Location: index.php"); // Redirect ke halaman index event
        exit;
    }
} else {
    header("Location: index.php"); // Jika ID tidak valid, redirect ke halaman index
    exit;
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

    .header {
      background-color: #b2e5f5;
      padding: 20px;
      text-align: center;
    }

    .header h1 {
      margin: 0;
      color: #007a8d;
    }

    .container {
      max-width: 800px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .container img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .container h2 {
      color: #007a8d;
      margin-bottom: 10px;
    }

    .container p {
      color: #444;
      line-height: 1.6;
    }

    .details {
      margin-top: 20px;
    }

    .details span {
      display: block;
      margin-bottom: 5px;
      color: #666;
      font-size: 14px;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .buttons a {
      display: inline-block;
      padding: 10px 15px;
      text-decoration: none;
      font-size: 14px;
      border-radius: 5px;
      color: #fff;
      text-align: center;
    }

    .btn-back {
      background-color: #007a8d;
    }

    .btn-back:hover {
      background-color: #005f70;
    }

    .btn-register {
      background-color: #28a745;
    }

    .btn-register:hover {
      background-color: #218838;
    }
</style>

<body>


  <!-- Header -->
  <div class="header">
    <h1>Detail Event</h1>
  </div>

  <!-- Detail Event Container -->
  <div class="container">
    <?php if ($event['gambar']): ?>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($event['gambar']); ?>" alt="Gambar Event">
    <?php else: ?>
        <img src="https://via.placeholder.com/800x400" alt="Gambar Event">
    <?php endif; ?>

    <h2><?php echo htmlspecialchars($event['judul']); ?></h2>
    <p>
      <?php echo nl2br(htmlspecialchars($event['deskripsi'])); ?>
    </p>

    <div class="details">
      <span>🗓 Tanggal: <?php echo date('d M Y', strtotime($event['dimulai_pada'])) . ' - ' . date('d M Y', strtotime($event['berakhir_pada'])); ?></span>
      <span>📍 Lokasi: <?php echo htmlspecialchars($event['lokasi']); ?></span>
    </div>

    <div class="buttons">
      <a href="index.php" class="btn-back">Kembali ke Halaman Event</a>
      <a href="form_register_event.php?id=<?php echo $event['id_acara']; ?>" class="btn-register">Ikuti Event</a>
    </div>
  </div>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>
