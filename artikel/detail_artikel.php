<?php
ob_start();
require "../partials/Config.php"; // Pastikan koneksi database sudah terhubung
session_start();

// Jika user sudah login
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['nama'];
}

// Ambil ID artikel dari URL
if (isset($_GET['id'])) {
    $artikel_id = $_GET['id'];

    // Query untuk mengambil artikel berdasarkan ID
    $sql = "SELECT * FROM artikel WHERE id_artikel = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $artikel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah artikel ditemukan
    if ($result->num_rows > 0) {
        $artikel = $result->fetch_assoc();
    } else {
        // Jika artikel tidak ditemukan
        header("Location: index.php");
        exit();
    }
} else {
    // Jika ID tidak ada, redirect ke halaman utama artikel
    header("Location: index.php");
    exit();
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
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .navbar h1 {
    font-size: 20px;
    margin: 0;
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
    color: white;
    text-decoration: none;
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
  }

  .navbar ul li a:hover {
    background-color: #005f70;
  }

  .container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
  }

  .article-header {
    text-align: center;
    margin-bottom: 20px;
  }

  .article-header h2 {
    margin: 0;
    color: #007a8d;
  }

  .article-header p {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
  }

  .article-image img {
    width: 100%;
    border-radius: 10px;
    margin: 20px 0;
  }

  .article-content {
    font-size: 16px;
    line-height: 1.6;
    color: #444;
  }

  .article-content p {
    margin: 10px 0;
  }

  .back-link {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    color: #007a8d;
    font-size: 14px;
    font-weight: bold;
  }

  .back-link:hover {
    text-decoration: underline;
  }
</style>

<body>


  <!-- Artikel Detail -->
  <div class="container">
    <div class="article-header">
      <h2><?php echo $artikel['nama_artikel']; ?></h2>
      <p>Dipublikasikan pada: <?php echo date('d F Y', strtotime($artikel['dibuat_pada'])); ?></p>
    </div>

    <div class="article-image">
      <img src="data:image/jpeg;base64,<?php echo base64_encode($artikel['gambar']); ?>" alt="<?php echo $artikel['nama_artikel']; ?>">
    </div>

    <div class="article-content">
      <?php echo nl2br($artikel['konten']); ?>
    </div>

    <a href="index.php" class="back-link">← Kembali ke Artikel</a>
  </div>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>
