<div class="flex px-[10px] sm:px-[50px] py-5 justify-between">
    <!-- Logo Section -->
    <div class="flex cursor-pointer" onclick="location.href='/Online%20Course/'">
        <div>
            <img src="/Online%20Course/partials/assets/logo.png" alt="Logo" class="h-[50px]">
        </div>
        <div class="self-center ml-[-5px]">
            <p class="font-bold text-[20px]">iPintar</p>
        </div>
    </div>

    <div class="flex items-center text-[25px] relative">
        <!-- Notification Icon -->


        <?php if(isset($_SESSION['user'])): ?>
            <div class="relative group inline-block">
            <button class="px-4 text-[30px]" onclick="window.location.href='/Online%20Course/profil/profil.php'">
                <img src="/Online%20Course/profil/<?= $_SESSION['user']['foto'] ?> " alt="foto" class="w-[40px] rounded-full">
            </button>
                <div
                    class="absolute left-1/2 -translate-x-1/2 bottom-full mb-[-100px] hidden group-hover:block bg-gray-800 text-white text-sm py-2 px-4 rounded shadow-lg">
                    <?= $_SESSION['user']['nama'] ?> 
                </div>
            </div>
        <?php else: ?>
            <button onclick="window.location.href='/Online%20Course/login'"
                class="text-[15px] font-bold py-2 border-2 border-black px-4 mx-4 hidden sm:block hover:scale-110 hover:transition-all transition-all active:scale-100">Sign-In</button>
            <button onclick="window.location.href='/Online%20Course/register/register.php'"
                class="text-[15px] font-bold py-2 border-2 border-black px-4 bg-black text-white hidden sm:block hover:scale-110 hover:transition-all active:scale-100 transition-all">Sign-Up</button>
        <?php endif; ?>

        <button class="text-[40px] mt-[-7px] px-5 mr-5 sm:mr-0 hover:scale-110 hover:transition-all transition-all"
            onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
    </div>
</div>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 right-0 h-full w-64 bg-gray-800 text-white hidden">
    <div class="flex items-center justify-between p-4">
        <h2 class="text-lg font-bold">Menu</h2>
        <button onclick="toggleSidebar()" class="text-white text-xl">&times;</button>
    </div>
    <ul class="p-4">
        <?php if(isset($_SESSION['user'])): ?>
        <li onclick="location.href='/Online%20Course/index.php'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/index.php">Home</a></li>
        <li onclick="location.href='/Online%20Course/profil/profil.php'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/profil/profil.php">Profile</a></li>
        <li onclick="location.href='/Online%20Course/event/'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/event/">Event</a></li>
        <li onclick="location.href='/Online%20Course/artikel/'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/artikel/">Article</a></li>
        <li onclick="location.href='/Online%20Course/voucher/'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/voucher/">Voucher</a></li>
        <li onclick="location.href='/Online%20Course/bank_soal/'" class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/bank_soal/">Bank Soal</a></li>
        <?php if($_SESSION['user']['peran'] == 1): ?>
            <li class="py-2 hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/Admin">Halaman Admin</a></li>
        <?php endif; ?>
        <?php if($_SESSION['user']['peran'] == 2): ?>
            <li class="text-center pt-5">
                FITUR PENGAJAR
            </li>
            <li class="py-2 border-b-2 border-white hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/admin/kelas/?id=<?= $_SESSION['user']['id_pengguna'] ?>">Kelola Kelas</a></li>
        <?php endif; ?>
        <?php else: ?>
            <li class="py-2 hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/login">Sign-in</a></li>
            <li class="py-2 hover:bg-gray-700 cursor-pointer"><a href="/Online%20Course/register/register.php">Sign-Up</a></li>
        <?php endif; ?>
        </ul>
        <form action="/Online%20Course/logout/Logout.php" method="post" class="flex self-end">
            <button class="p-4 hover:bg-gray-700 cursor-pointer w-[100%] ">Sign-Out</button>
        </form>
</div>
<hr class="border-2 border-black">
<!-- JavaScript -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    }

    function toggleNotification() {
        const notificationPopup = document.getElementById('notificationPopup');
        notificationPopup.classList.toggle('hidden');
    }
</script>

