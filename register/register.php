<?php ob_start(); ?>
<?php
// Include koneksi database
require "../partials/Config.php"; 

$message = ""; // Variabel untuk menyimpan pesan notifikasi

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $sekolah = mysqli_real_escape_string($conn, $_POST['sekolah']);

    // Hash password menggunakan MD5
    $hashedPassword = md5($password);

    // Query untuk menyimpan data ke tabel pengguna
    $sql = "INSERT INTO pengguna (nama, email, username, kata_sandi, jenis_kelamin, tanggal_lahir, kelas, sekolah, peran, foto)
            VALUES ('$nama', '$email', '$username', '$hashedPassword', '$jenis_kelamin', '$tanggal_lahir', '$kelas', '$sekolah', 3, 'mewing.jpg')";

    // Eksekusi query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href='../login/';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>


<div class="min-h-screen flex items-center justify-center p-10">
    <form action="" method="post" class="w-full max-w-md bg-white p-6 rounded-lg shadow-2xl border-black border-2">
        <fieldset>
            <legend class="text-2xl font-bold mb-6 text-center text-gray-800">Register Akun</legend>
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <div class="relative">
                    <input type="text" name="nama" id="nama"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                    <span class="absolute right-3 top-3 text-gray-400">&#128100;</span>
                </div>
            </div>
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                <div class="relative">
                    <input type="email" name="email" id="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                    <span class="absolute right-3 top-3 text-gray-400">&#9993;</span>
                </div>
            </div>
            <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="relative">
                    <input type="text" name="username" id="username"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                    <span class="absolute right-3 top-3 text-gray-400">&#128272;</span>
                </div>
            </div>
            <div class="mb-6">
                <label for="pass" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="pass" id="pass"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                    <span class="absolute right-3 top-3 text-gray-400">&#128274;</span>
                </div>
            </div>
            <div class="mb-6">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="" disabled selected>Pilih jenis kelamin</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
            </div>
            <div class="mb-6">
                <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas" id="kelas"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="" disabled selected>Pilih kelas</option>
                    <option value="1">Kelas 1 SD</option>
                    <option value="2">Kelas 2 SD</option>
                    <option value="3">Kelas 3 SD</option>
                    <option value="4">Kelas 4 SD</option>
                    <option value="5">Kelas 5 SD</option>
                    <option value="6">Kelas 6 SD</option>
                    <option value="7">Kelas 7 (SMP)</option>
                    <option value="8">Kelas 8 (SMP)</option>
                    <option value="9">Kelas 9 (SMP)</option>
                    <option value="10">Kelas 10 (SMA)</option>
                    <option value="11">Kelas 11 (SMA)</option>
                    <option value="12">Kelas 12 (SMA)</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="sekolah" class="block text-sm font-medium text-gray-700">Nama Sekolah</label>
                <input type="text" name="sekolah" id="sekolah"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required />
            </div>
            <button type="submit"
                class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400">Register</button>
        </fieldset>
    </form>
</div>




<?php 
$content = ob_get_clean();
include '../partials/Master.php'; 
?>