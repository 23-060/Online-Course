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

// Mengambil ID acara yang akan diedit
if (isset($_GET['id'])) {
    $id_acara = $_GET['id'];

    // Query untuk mengambil data acara berdasarkan ID
    $sql = "SELECT * FROM acara WHERE id_acara = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_acara);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data acara ditemukan
    if ($result->num_rows > 0) {
        $acara = $result->fetch_assoc();
    } else {
        echo "<script>alert('Acara tidak ditemukan'); window.location.href = 'index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID acara tidak ditemukan'); window.location.href = 'index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $dimulai_pada = $_POST['dimulai_pada'];
    $berakhir_pada = $_POST['berakhir_pada'];

    // Proses gambar
    $gambar = $acara['gambar']; // Gambar lama tetap digunakan jika tidak ada gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
    }

    // Query untuk update data acara
    $sql = "UPDATE acara SET judul = ?, deskripsi = ?, lokasi = ?, gambar = ?, dimulai_pada = ?, berakhir_pada = ? WHERE id_acara = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $judul, $deskripsi, $lokasi, $gambar, $dimulai_pada, $berakhir_pada, $id_acara);

    if ($stmt->execute()) {
        echo "<script>alert('Acara berhasil diperbarui!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui acara.');</script>";
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

    form {
        display: flex;
        flex-direction: column;
    }

    input[type="text"], input[type="date"], textarea {
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        padding: 10px 15px;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #218838;
    }

    .current-image img {
        width: 100px;
        height: 60px;
        object-fit: cover;
        margin: 10px 0;
    }
</style>

</head>
<body>
<p class="text-sm font-medium text-blue-400 hover:text-blue cursor-pointer mb-4">
            Path: <?= $display_path; ?>
        </p>
  <!-- Edit Event Form -->
  <div class="container">
    <div class="title">
      <h2>Edit Acara</h2>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <label for="judul">Judul Acara</label>
      <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($acara['judul']); ?>" required>

      <label for="deskripsi">Deskripsi Acara</label>
      <textarea name="deskripsi" id="deskripsi" rows="5"><?php echo htmlspecialchars($acara['deskripsi']); ?></textarea>

      <label for="lokasi">Lokasi Acara</label>
      <input type="text" name="lokasi" id="lokasi" value="<?php echo htmlspecialchars($acara['lokasi']); ?>" required>

      <label for="dimulai_pada">Dimulai Pada</label>
      <input type="date" name="dimulai_pada" id="dimulai_pada" value="<?php echo $acara['dimulai_pada']; ?>" required>

      <label for="berakhir_pada">Berakhir Pada</label>
      <input type="date" name="berakhir_pada" id="berakhir_pada" value="<?php echo $acara['berakhir_pada']; ?>" required>

      <label for="gambar">Gambar Acara</label>
      <input type="file" name="gambar" id="gambar" accept="image/*">
      
      <!-- Display current image if exists -->
      <?php if ($acara['gambar']): ?>
        <div class="current-image">
          <p>Gambar Saat Ini:</p>
          <img src="data:image/jpeg;base64,<?php echo base64_encode($acara['gambar']); ?>" alt="Gambar Acara">
        </div>
      <?php else: ?>
        <p>No Image</p>
      <?php endif; ?>

      <button type="submit">Perbarui Acara</button>
    </form>
  </div>
</body>
</html>

<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>
