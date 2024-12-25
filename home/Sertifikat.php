<?php ob_start(); ?>
<?php 
session_start();
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user']['nama'];
}else{
    echo "<script>
    alert('Tolong Login Terlebih Dahulu Untuk MengAkses Fitur Ini'); 
    window.location.href='../login/'
    </script>";
}
?>
<div class="flex justify-center p-10">
    <div class="bg-[url('../partials/assets/sertifbg.png')] w-[800px] h-[566px] bg-center bg-cover text-center p-10">
        <p class="font-extrabold text-[70px]">SERTIFIKAT</p>
        <p class="font-extrabold text-[35px] mt-2">PENGHARGAAN</p>
        <p class="text-[20px]">Dengan Bangga Diberikan kepada :</p>
        <p class="font-extrabold text-[35px] mt-7"><?= $_SESSION['user']['nama'] ?></p>
    </div>
</div>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>