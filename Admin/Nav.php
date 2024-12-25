<!-- Navbar -->
<nav class="bg-gray-900 text-white py-4 px-6 fixed w-full top-0 z-50 shadow-lg flex items-center justify-between">
    <div class="text-lg font-bold cursor-pointer" onclick="return location.href='../../'">Halaman Admin</div>
    <button id="menu-button" class="text-2xl focus:outline-none hover:text-gray-400">
        ☰
    </button>
</nav>

<!-- Slide Menu -->
<div id="slide-menu" class="fixed top-0 right-0 h-full w-64 bg-gray-800 text-white transform translate-x-full transition-transform duration-300 ease-in-out z-40 shadow-lg">
    <div class="p-4 flex justify-between items-center border-b border-gray-700">
        <span class="text-lg font-semibold">Menu</span>
        <button id="close-menu" class="text-xl focus:outline-none hover:text-gray-400">
            ✖
        </button>
    </div>
    <ul class="p-4 space-y-4">
        <li><a href="../table_user" class="block text-gray-200 hover:text-white">Kelola User</a></li>
        <li><a href="../buat_akun_pengajar/buat_akun.php" class="block text-gray-200 hover:text-white">Buat Akun Pengajar</a></li>
        <li><a href="../pemasukan/pemasukan.php" class="block text-gray-200 hover:text-white">Lihat Pemasukan</a></li>
        <li><a href="../pengeluaran/pengeluaran.php" class="block text-gray-200 hover:text-white">Kelola Pengeluaran</a></li>
        <li><a href="../artikel/" class="block text-gray-200 hover:text-white">Kelola Artikel</a></li>
        <li><a href="../event/" class="block text-gray-200 hover:text-white">Kelola Event</a></li>
        <li><a href="../voucher/" class="block text-gray-200 hover:text-white">Kelola Voucher</a></li>
        <li><a href="../feedback/" class="block text-gray-200 hover:text-white">Feedback</a></li>
        <li><a href="../bank_soal/" class="block text-gray-200 hover:text-white">Bank Soal</a></li>
        <li><a href="../kelas/" class="block text-gray-200 hover:text-white">Kelas</a></li>
        <li><a href="../ulasan/" class="block text-gray-200 hover:text-white">Ulasan_Kelas</a></li>
    </ul>
</div>


<script>
    // JavaScript for Slide Menu
    const menuButton = document.getElementById('menu-button');
    const closeMenu = document.getElementById('close-menu');
    const slideMenu = document.getElementById('slide-menu');

    // Open menu
    menuButton.addEventListener('click', () => {
        slideMenu.classList.remove('translate-x-full');
    });

    // Close menu
    closeMenu.addEventListener('click', () => {
        slideMenu.classList.add('translate-x-full');
    });

    // Close menu when clicking outside the menu
    window.addEventListener('click', (event) => {
        if (!slideMenu.contains(event.target) && !menuButton.contains(event.target)) {
            slideMenu.classList.add('translate-x-full');
        }
    });
</script>
