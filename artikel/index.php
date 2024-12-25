<?php
ob_start();
require "../partials/Config.php";
session_start();

// Jika user sudah login
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['nama'];
}
?>

<style>
  /* CSS tetap sama seperti yang Anda berikan */
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
    max-width: 1200px;
    margin: 20px auto;
    padding: 10px;
  }

  .title {
    text-align: center;
    margin-bottom: 20px;
  }

  .title h2 {
    margin: 0;
    color: #444;
  }

  .title p {
    color: #666;
  }

  .article-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
  }

  .article-card {
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 300px;
    overflow: hidden;
    text-align: left;
    display: flex;
    flex-direction: column;
  }

  .article-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .article-card-content {
    padding: 15px;
    flex-grow: 1;
  }

  .article-card h3 {
    font-size: 18px;
    margin: 0 0 10px;
    color: #007a8d;
  }

  .article-card p {
    margin: 0 0 15px;
    font-size: 14px;
    color: #666;
  }

  .article-card a {
    text-decoration: none;
    color: #007a8d;
    font-size: 14px;
    font-weight: bold;
    display: inline-block;
    margin-top: auto;
  }

  .article-card a:hover {
    text-decoration: underline;
  }
</style>


  <!-- Artikel Container -->
  <div class="container">
    <div class="title">
      <h2>Artikel Terbaru</h2>
      <p>Temukan artikel menarik untuk meningkatkan pengetahuanmu!</p>
    </div>

    <div class="article-grid">
      <?php
        // Query untuk mengambil data artikel
        $sql = "SELECT * FROM artikel ORDER BY dibuat_pada DESC";
        $result = $conn->query($sql);

        // Periksa apakah ada artikel
        if ($result->num_rows > 0) {
            // Loop untuk menampilkan artikel
            while ($artikel = $result->fetch_assoc()) {
                echo '
                <div class="article-card">
                    <img src="data:image/jpeg;base64,' . base64_encode($artikel['gambar']) . '" alt="' . $artikel['nama_artikel'] . '">
                    <div class="article-card-content">
                        <h3>' . $artikel['nama_artikel'] . '</h3>
                        <p>' . substr($artikel['konten'], 0, 100) . '...</p>
                        <a href="detail_artikel.php?id=' . $artikel['id_artikel'] . '">Baca Selengkapnya</a>
                    </div>
                </div>';
            }
        } else {
            echo '<p>Tidak ada artikel yang tersedia.</p>';
        }
      ?>
    </div>
  </div>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>
