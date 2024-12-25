<?php ob_start(); ?>
<?php
include "../partials/Config.php"; // File koneksi database

// Inisialisasi variabel
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Periksa apakah email ada di database
    $query = "SELECT * FROM pengguna WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(32)); // Token acak
        $reset_url = "http://yourwebsite.com/reset-pass.php?token=$token";

        // Simpan token di database
        $update_query = "UPDATE pengguna SET reset_token = '$token', reset_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // Kirim email ke user
        $subject = "Reset Password";
        $message = "Klik link berikut untuk mereset password Anda: $reset_url";
        $headers = "From: no-reply@yourwebsite.com";

        if (mail($email, $subject, $message, $headers)) {
            $success = "Link reset password telah dikirim ke email Anda.";
        } else {
            $error = "Gagal mengirim email. Coba lagi nanti.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>
<div class=" p-[100px] flex justify-center my-[80px] bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Lupa Password</h1>
        <?php if ($error): ?>
        <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
        <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="email" name="email" id="email" placeholder="Masukkan Email Anda"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
                required />
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Kirim Link
                Reset</button>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean();
include '../partials/Master.php'; 
?>