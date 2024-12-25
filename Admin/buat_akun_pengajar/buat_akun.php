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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Pengajar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg border-2 border-blue-500 p-6 w-full max-w-md">
            <h1 class="text-3xl font-bold mb-6 text-center text-blue-500">Buat Akun Pengajar</h1>
            <form action="process_register.php" method="POST">
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="nama" name="nama" required 
                           class="mt-1 block w-full rounded-md border border-black-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required 
                           class="mt-1 block w-full rounded-md border border-black-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required 
                           class="mt-1 block w-full rounded-md border border-black-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="kata_sandi" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                    <input type="password" id="kata_sandi" name="kata_sandi" required 
                           class="mt-1 block w-full rounded-md border border-black-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="keahlian" class="block text-sm font-medium text-gray-700">Keahlian</label>
                    <input type="text" id="keahlian" name="keahlian" 
                           class="mt-1 block w-full rounded-md border border-black-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Daftar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>