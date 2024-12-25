<?php ob_start(); ?>
<?php 
require "../partials/Config.php"; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM pengguna WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if ($user['kata_sandi'] === $password) {
            $_SESSION['user'] = $user;

            if ($user['peran'] == 1) {
                echo "<script>alert('Berhasil Login'); window.location.href = '../'</script>";
            } else {
                echo "<script>alert('Berhasil Login'); window.location.href = '../'</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href = 'index.php'</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location.href = 'index.php'</script>";
    }
}
?>

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Login Akun</h2>
        <form action="" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="username" id="username" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
            <div>
                <label for="password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                    <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ingat Saya</label>
                </div>
                <a href="../forget_password/forget-pass.php"
                    class="text-sm text-blue-600 hover:underline dark:text-blue-500">Forgot
                    password?</a>
            </div>
            <button type="submit" name="submit"
                class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                Sign in </button>
        </form>
        <p class="text-sm text-center text-gray-700 dark:text-gray-300"> Belum Punya Akun? <a
                href="../register/register.php" class="text-blue-600 hover:underline dark:text-blue-500">Sign up</a>
        </p>
    </div>
</div>

<?php 
$content = ob_get_clean();
include '../partials/Master.php'; 
?>