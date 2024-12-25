<?php
require "../partials/Config.php";
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_feedback = $_GET['id'];

    // Ambil data feedback
    $sql = "SELECT feedback.*, users.nama FROM feedback 
            JOIN users ON feedback.id_pengguna = users.id_pengguna 
            WHERE feedback.id_feedback = '$id_feedback'";
    $result = mysqli_query($conn, $sql);
    $feedback = mysqli_fetch_assoc($result);

    // Ambil data balasan admin
    $reply_sql = "SELECT * FROM feedback_reply WHERE id_feedback = '$id_feedback'";
    $reply_result = mysqli_query($conn, $reply_sql);
    $reply = mysqli_fetch_assoc($reply_result);

    // Jika ada POST untuk balasan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reply_text = $_POST['reply'];
        $replied_at = date('Y-m-d H:i:s');

        // Simpan balasan ke database
        $insert_sql = "INSERT INTO feedback_reply (id_feedback, reply, replied_at) VALUES ('$id_feedback', '$reply_text', '$replied_at')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Balasan berhasil dikirim!'); window.location.href='admin_feedback.php';</script>";
        } else {
            echo "<p class='text-red-500'>Terjadi kesalahan: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Detail Feedback</h1>

    <p><strong>Nama Pengguna:</strong> <?= htmlspecialchars($feedback['nama']); ?></p>
    <p><strong>Pesan:</strong> <?= htmlspecialchars($feedback['pesan']); ?></p>
    <p><strong>Rating:</strong> <?= $feedback['rating']; ?> ★</p>
    <p><strong>Dibuat Pada:</strong> <?= $feedback['dibuat_pada']; ?></p>

    <hr class="my-4">

    <?php if ($reply): ?>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Balasan Admin</h2>
        <p><?= htmlspecialchars($reply['reply']); ?></p>
        <p class="text-sm text-gray-500">Dibalas pada: <?= $reply['replied_at']; ?></p>
    <?php else: ?>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Tambahkan Balasan</h2>
        <form action="#" method="post" class="space-y-4">
            <textarea name="reply" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            <button type="submit" class="bg-blue-500 text-white font-medium py-2 px-4 rounded-md shadow-sm hover:bg-blue-600">Kirim Balasan</button>
        </form>
    <?php endif; ?>
</div>
