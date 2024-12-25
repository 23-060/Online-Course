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
$path = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$path_array = explode('/', trim($path, '/'));
$display_path = implode(' -> ', $path_array);

// Ambil semua acara dari database
$sql = "SELECT * FROM acara ORDER BY dimulai_pada DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$acaraList = $stmt->get_result();
?>

<style>
    /* CSS sama seperti sebelumnya */
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
        margin: 50px auto;
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

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #007a8d;
        color: white;
    }

    td img {
        width: 100px;
        height: 60px;
        object-fit: cover;
    }

    .action-buttons a {
        margin: 0 5px;
        padding: 5px 10px;
        background-color: #007a8d;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .action-buttons a:hover {
        background-color: #005f70;
    }

    .btn-add {
        background-color: #28a745;
        margin-bottom: 20px;
        padding: 10px 15px;
        color: white;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-add:hover {
        background-color: #218838;
    }
    
</style>

</head>
<body>
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
  <!-- Acara List Container -->
  <div class="container">
    <div class="title">
      <h2>Daftar Acara</h2>
    </div>

    <!-- Add New Acara Button -->
    <a href="event_tambah.php" class="btn-add">Tambah Acara</a>

    <!-- Acara Table -->
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Judul</th>
          <th>Lokasi</th>
          <th>Dimulai Pada</th>
          <th>Berakhir Pada</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        while ($acara = $acaraList->fetch_assoc()) :
        ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $acara['judul']; ?></td>
            <td><?php echo $acara['lokasi']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($acara['dimulai_pada'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($acara['berakhir_pada'])); ?></td>
            <td>
              <?php if ($acara['gambar']): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($acara['gambar']); ?>" alt="Gambar Acara">
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td class="action-buttons">
              <a href="event_edit.php?id=<?php echo $acara['id_acara']; ?>">Edit</a>
              <a href="proses_hapus_event.php?id=<?php echo $acara['id_acara']; ?>" onclick="return confirm('Anda yakin ingin menghapus acara ini?');">Hapus</a>
              <a href="absensi_detail.php?id=<?php echo $acara['id_acara']; ?>">Absensi</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
