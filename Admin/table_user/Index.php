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
$pengguna = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM pengguna"), MYSQLI_ASSOC);
?>
<p class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-4">
    Path: <?= $display_path; ?>
</p>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <caption class="p-5 text-lg font-semibold text-gray-900 bg-gray-100 dark:text-white dark:bg-gray-800">
            DATA USER
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Manajemen data pengguna dalam sistem.</p>
        </caption>
        <thead class="text-xs text-white uppercase bg-blue-600 dark:bg-blue-900">
            <tr>
                <th scope="col" class="px-6 py-3">Nama Pengguna</th>
                <th scope="col" class="px-6 py-3">Username</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Peran</th>
                <th scope="col" class="px-6 py-3">Tanggal Dibuat</th>
                <th scope="col" class="px-6 py-3">Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pengguna as $idx => $row): ?>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= $row['nama']; ?></td>
                <td class="px-6 py-4"><?= $row['username']; ?></td>
                <td class="px-6 py-4"><?= $row['email']; ?></td>
                <td class="px-6 py-4">
                    <?php if($row['peran'] == 1): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Admin</span>
                    <?php elseif($row['peran'] == 2): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Pengajar</span>
                    <?php elseif($row['peran'] == 3): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Siswa</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4"><?= $row['dibuat_pada']; ?></td>
                <td class="px-6 py-4">
                    <a onclick="return confirm('Apakah Anda Yakin Untuk Menghapus Data ini ?');" href="hapus.php?id=<?= $row['id_pengguna']; ?>" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-500 mr-2">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                    <a href="edit.php?id=<?= $row['id_pengguna']; ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-500">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>




<?php
$content = ob_get_clean(); 
include '../MasterAdmin.php'; 
?>