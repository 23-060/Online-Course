<?php ob_start(); ?>
<?php 
require "../../partials/Config.php";
session_start();
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user'];
    if($userid['peran'] != 1){
        echo "<script>alert(' ADMIN DATANG !! '); window.location.href = '../index.php'</script>";
    }
}
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);
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
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .title {
      text-align: center;
      margin-bottom: 20px;
    }

    .title h2 {
      margin: 0;
      color: #444;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      font-size: 14px;
      color: #555;
    }

    input[type="text"],
    textarea,
    input[type="file"],
    input[type="date"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      color: #333;
    }

    textarea {
      height: 100px;
      resize: none;
    }

    button {
      padding: 10px 15px;
      background-color: #007a8d;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
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
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <h1>Admin Panel</h1>
    <ul>
      <li><a href="#">Beranda</a></li>
      <li><a href="#">Profil</a></li>
      <li><a href="../event/index.php">Event</a></li>
      <li><a href="../artikel/index.php">Artikel</a></li>
      <li><a href="../voucher/index.php">Voucher</a></li>
    </ul>
  </div>

  <!-- Tambah Artikel Container -->
  <div class="container">
    <div class="title">
      <h2>Tambah Artikel Baru</h2>
    </div>

    <a href="index.php" class="btn-back">&larr; Kembali</a>

    <form action="proses_tambah_artikel.php" method="POST" enctype="multipart/form-data">
      <label for="judul">Judul Artikel:</label>
      <input type="text" id="judul" name="judul" placeholder="Masukkan judul artikel" required>


      <label for="tanggal">Tanggal Publikasi:</label>
      <input type="date" id="tanggal" name="tanggal" required>

      <label for="konten">Konten Artikel:</label>
      <textarea id="konten" name="konten" placeholder="Masukkan konten artikel" required></textarea>

      <label for="gambar">Gambar Artikel:</label>
      <input type="file" id="gambar" name="gambar" accept="image/*" required>

      <button type="submit">Simpan Artikel</button>
    </form>
  </div>
</body>
</html>
<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>
