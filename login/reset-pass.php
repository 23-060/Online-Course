<?php
include "konek.php"; // File koneksi database

// Inisialisasi variabel
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Password tidak cocok.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Verifikasi token
        $query = "SELECT * FROM pengguna WHERE reset_token = '$token' AND reset_expiry > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $update_query = "UPDATE pengguna SET kata_sandi = '$hashed_password', reset_token = NULL, reset_expiry = NULL WHERE reset_token = '$token'";
            if (mysqli_query($conn, $update_query)) {
                $success = "Password berhasil diubah. Silakan login kembali.";
            } else {
                $error = "Gagal mengubah password.";
            }
        } else {
            $error = "Token tidak valid atau telah kedaluwarsa.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Reset Password</h1>
        <?php if ($error): ?>
        <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
        <p class="text-green-500 text-center mb-4"><?php echo $success; ?></p>
        <?php else: ?>
        <form action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium">Password Baru</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    class="w-full p-2 border rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Reset
                Password</button>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>