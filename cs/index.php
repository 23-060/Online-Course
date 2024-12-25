<?php ob_start(); ?>
<?php 
session_start();
if(isset($_SESSION['user'])){
    $userid = $_SESSION['user']['nama'];
}
?>
<?php
// Fungsi untuk menangani input pengguna
define('CS_CONTACTS', [
    1 => 'Masalah Pembayaran: Silakan hubungi CS 1 (Yoel) di 085757610448.',
    2 => 'Masalah Pengiriman: Silakan hubungi CS 2 (Naufal) di 085882777906.',
    3 => 'Masalah Akun: Silakan hubungi CS 3 (Ubay) di +62 851-4252-7829.',
    4 => 'Produk Tidak Sesuai: Silakan hubungi CS 4 (Arul) di 089637680193.',
    5 => 'Komplain Layanan: Silakan hubungi CS 5 (Fahri) di 089512345678.',
    6 => 'Masalah Lainnya: Silakan hubungi CS 6 (Zaki) di 087654321123.'
]);

function handleUserInput($input) {
    if (array_key_exists($input, CS_CONTACTS)) {
        return CS_CONTACTS[$input];
    } else {
        return 'Input tidak valid. Silakan pilih angka 1 sampai 6.';
    }
}

$userMessage = '';
$response = '';

// Jika pengguna mengirimkan form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMessage = trim($_POST['message']);
    $response = handleUserInput($userMessage);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Service Interface</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #e3f2fd; margin: 0; padding: 0; }
        .header { background-color: #1976d2; color: white; padding: 20px; text-align: center; font-size: 24px; }
        .popup-btn { position: fixed; bottom: 20px; right: 20px; background-color: #1976d2; color: white; border-radius: 50%; width: 60px; height: 60px; font-size: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); }
        .chat-container { position: fixed; bottom: 80px; right: 20px; background-color: white; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); width: 400px; height: 600px; display: none; flex-direction: column; }
        .chat-container.open { display: flex; }
        .chat-header { background-color: #1976d2; color: white; padding: 20px; border-radius: 10px 10px 0 0; font-size: 22px; display: flex; justify-content: space-between; align-items: center; }
        .chat-header i { cursor: pointer; }
        .chat-messages { padding: 20px; height: 400px; overflow-y: auto; background-color: #e3f2fd; flex-grow: 1; }
        .message { padding: 10px 15px; margin: 10px 0; border-radius: 10px; max-width: 80%; position: relative; font-size: 16px; }
        .message.cs { background-color: white; align-self: flex-start; border: 1px solid #1976d2; color: #1976d2; }
        .message.user { background-color: #1976d2; color: white; align-self: flex-end; }
        .chat-input { padding: 10px; display: flex; align-items: center; background-color: #f0f0f0; border-top: 1px solid #ddd; }
        .chat-input textarea { width: 85%; padding: 10px; border-radius: 20px; border: 1px solid #ddd; resize: none; font-size: 16px; outline: none; }
        .chat-input button { background-color: #1976d2; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 20px; cursor: pointer; margin-left: 10px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
<div class="header">Customer Service Support</div>
<div class="popup-btn" id="popupBtn" onclick="toggleChatBox()">💬</div>
<div class="chat-container" id="chatBox">
    <div class="chat-header">
        <span>Support Chat</span>
        <i class="fas fa-times" onclick="toggleChatBox()"></i>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="message cs">
            Selamat datang di layanan Customer Service!<br>
            Silakan pilih masalah Anda:<br>
            1. Pembayaran<br>
            2. Pengiriman<br>
            3. Akun<br>
            4. Produk Tidak Sesuai<br>
            5. Komplain Layanan<br>
            6. Lainnya<br>
            Silakan ketik angka atau tulis masalah Anda.
        </div>
        <?php if ($response): ?>
            <div class="message user">
                <?php echo htmlspecialchars($userMessage); ?>
            </div>
            <div class="message cs">
                <?php echo $response; ?>
            </div>
        <?php endif; ?>
    </div>
    <form method="POST" class="chat-input">
        <textarea name="message" id="messageInput" placeholder="Tulis pesan..."></textarea>
        <button type="submit">&#x279E;</button>
    </form>
</div>
<script>
    function toggleChatBox() {
        document.getElementById('chatBox').classList.toggle('open');
    }
</script>
</body>
</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>