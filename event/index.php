<?php
ob_start();
require "../partials/Config.php";
session_start();

// Cek apakah user sudah login
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user']['nama'];
}

// Ambil data event dari database
$sql = "SELECT * FROM acara ORDER BY dimulai_pada DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$eventList = $stmt->get_result();
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

    .event-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 10px;
    }

    .event-title {
      text-align: center;
      margin-bottom: 20px;
    }

    .event-title h2 {
      color: #444;
    }

    .event-grid {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .event-card {
      background: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      width: 300px;
      overflow: hidden;
      text-align: left;
    }

    .event-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }

    .event-card-content {
      padding: 15px;
    }

    .event-card h3 {
      font-size: 18px;
      margin: 0 0 10px;
      color: #007a8d;
    }

    .event-card p {
      margin: 0 0 10px;
      color: #666;
      font-size: 14px;
    }

    .event-card .details {
      font-size: 13px;
      color: #444;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .event-card .details span {
      display: flex;
      align-items: center;
      gap: 5px;
    }
</style>

<body>


  <!-- Header -->
  <div class="header">
    <h1>Temukan Event Seru dari Website Online Course Sekarang!</h1>
  </div>

  <!-- Event Container -->
  <div class="event-container">
    <div class="event-title">
      <h2>Event Website Online Course</h2>
      <p>Dapatkan informasi dan ikuti rangkaian event seru dari Website Online Course</p>
    </div>

    <div class="event-grid">
      <?php while ($event = $eventList->fetch_assoc()): ?>
        <div class="event-card">
          <?php if ($event['gambar']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($event['gambar']); ?>" alt="Event Image">
          <?php else: ?>
            <img src="https://via.placeholder.com/300x150" alt="Event Placeholder">
          <?php endif; ?>
          <div class="event-card-content">
            <h3><?php echo htmlspecialchars($event['judul']); ?></h3>
            <p>
                <?php
                $deskripsi = htmlspecialchars($event['deskripsi']);
                echo substr($deskripsi, 0, 100); // Menampilkan 100 huruf pertama
                if (strlen($deskripsi) > 100) {
                    echo '...';
                }
                ?>
            </p>
            <div class="details">
              <span>🗓 <?php echo date('d M Y', strtotime($event['dimulai_pada'])) . ' - ' . date('d M Y', strtotime($event['berakhir_pada'])); ?></span>
              <span>📍 <?php echo htmlspecialchars($event['lokasi']); ?></span>
            </div>
            <br>
            <div>
              <a href="detail_event.php?id=<?php echo $event['id_acara']; ?>">Lihat Detail</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>
