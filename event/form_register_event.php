<?php ob_start(); ?>
<?php 
require_once '../partials/Config.php';
session_start();
if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user']['id_pengguna'];
    
}

// Ambil data event secara dinamis dari tabel acara
$sql = "SELECT id_acara, judul FROM acara ORDER BY dimulai_pada DESC";
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

    .form-container {
      max-width: 600px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
      text-align: center;
      color: #007a8d;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .form-group textarea {
      resize: vertical;
      height: 100px;
    }

    .form-buttons {
      text-align: center;
      margin-top: 20px;
    }

    .form-buttons button {
      background-color: #007a8d;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    .form-buttons button:hover {
      background-color: #005f70;
    }

    .form-buttons .btn-cancel {
      background-color: #dc3545;
      margin-left: 10px;
    }

    .form-buttons .btn-cancel:hover {
      background-color: #b02a37;
    }
  </style>
<body>


  <!-- Form Container -->
  <div class="form-container">
    <h2>Formulir Pendaftaran Event</h2>
    <form action="proses_daftar_event.php" method="post">
      <!-- Nama Lengkap -->
      <div class="form-group">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Anda" required>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
      </div>

      <!-- Nomor Telepon -->
      <div class="form-group">
        <label for="telepon">Nomor Telepon</label>
        <input type="tel" id="telepon" name="telepon" placeholder="Masukkan nomor telepon Anda" required>
      </div>

      <!-- Alamat -->
      <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap Anda" required></textarea>
      </div>

      <!-- Jenis Event -->
      <div class="form-group">
        <label for="event">Pilih Event</label>
        <select id="event" name="event" required>
          <option value="" disabled selected>-- Pilih Event --</option>
          <?php while ($event = $eventList->fetch_assoc()): ?>
            <option value="<?php echo $event['id_acara']; ?>"><?php echo htmlspecialchars($event['judul']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Tombol -->
      <div class="form-buttons">
        <button type="submit">Daftar</button>
        <button type="button" class="btn-cancel" onclick="window.location.href='index.php'">Batal</button>
      </div>
    </form>
  </div>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>
