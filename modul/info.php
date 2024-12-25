<?php
ob_start();
session_start();
require_once "./../model/kelas.php";
require_once "./../model/pendaftaranKelas.php";
require_once "./../model/ulasan.php";
require_once "./../autoload/autoload.php";

$id_kelas = $_GET["id"];
$id_pengguna = $_SESSION['user']["id_pengguna"];

$role = $GLOBALS["roles"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["KIRIM_ULASAN"])) {
        $data["id_kelas"] = $id_kelas;
        $data["id_pengguna"] = $id_pengguna;
        $data["komentar"] = $_POST["komentar"];
        $data["rating"] =  $_POST["rating"];

        if (createUlasan($data)) {
            showAlertAndRedirect("Berhasil Mengirim Komentar");
        } else {
            showAlertAndRedirect("Gagal Mengirim Komentar");
        }
    } elseif (isset($_POST["EDIT_ULASAN"])) {
        $data["id_ulasan"] = $_POST["id_ulasan"];
        $data["komentar"] = $_POST["komentar"];
        $data["rating"] =  $_POST["rating"];

        if (updateUlasan($data)) {
            showAlertAndRedirect("Berhasil Mengubah Komentar");
        } else {
            showAlertAndRedirect("Gagal Mengubah Komentar");
        }
    } elseif (isset($_POST["HAPUS_ULASAN"])) {
        if (deleteUlasanByid($_POST["id_ulasan"])) {
            showAlertAndRedirect("Berhasil Menghapus Komentar");
        } else {
            showAlertAndRedirect("Gagal Menghapus Komentar");
        }
    }
}


$filter = [
    "id_kelas" => $id_kelas,
    "id_pengguna" => $id_pengguna
];

$data["kelas"] = getPendaftaranKelasByFilter($filter);
$data["kelas"] = $data["kelas"] ? $data["kelas"] : getKelasByFilter($filter)[0];

$data["komentar"] = getUlasanByFilter(["id_kelas" => $id_kelas]);
?>
<div class="container mx-auto p-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-3xl font-semibold text-gray-800"><?= $data["kelas"]['nama_kelas'] ?></h2>
        <p class="text-sm text-gray-600 mt-2"><?= $data["kelas"]['deskripsi'] ?></p>

        <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Tingkat:</p>
                <span class="px-2 py-1 text-sm text-gray-800"><?= $data["kelas"]['tingkat'] ?></span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pelajaran:</p>
                <span class="px-2 py-1 text-sm text-gray-800"><?= $data["kelas"]['pelajaran'] ?></span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pengajar:</p>
                <span class="px-2 py-1 text-sm text-gray-800"><?= $data["kelas"]['nama_pengajar'] ?></span>
            </div>
            <div>
                <p class="text-sm text-gray-600">Harga:</p>
                <span class="px-2 py-1 text-sm text-gray-800">Rp <?= number_format($data["kelas"]['harga'], 0, ',', '.') ?></span>
            </div>
        </div>
        <div class="mt-6">
            <?php if ($data["kelas"]["status_transaksi"] === 1): ?>
                <div class="space-x-4">
                    <a href="./../materi.php?id_kelas=<?= $id_kelas ?>" class="flex-1 text-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                        Lihat Materi
                    </a>
                    <a href="./statistik.php?id_kelas=<?php echo $id_kelas; ?>" class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Lihat Statistik Anda
                    </a>
                    <?php
                    if ($data["kelas"]["status"] == "Selesai") : ?>
                        <a href="./../home/Sertifikat.php?id_kelas=<?= $id_kelas ?>"
                            class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Cetak Sertifikat
                        </a>
                    <?php endif ?>
                </div>
            <?php elseif ($data["kelas"]["status_transaksi"] === 0): ?>
                <a href="./transaksi.php" class="flex-1 text-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    Menunggu Pembayaran
                </a>
            <?php else: ?>
                <a href="./daftar.php?id_kelas=<?= $data["kelas"]["id_kelas"] ?>" class="flex-1 text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Daftar Sekarang
                </a>
            <?php endif; ?>
        </div>
    </div>


    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-2xl font-semibold text-gray-800">Komentar</h3>

        <div class="mt-6">
            <div class="my-6">
                <?php if ($data["kelas"]["status_transaksi"] == 1): ?>
                    <form method="POST" action="">

                        <div class="my-4 flex items-center">
                            <span class="text-gray-600 mr-4">Rating:</span>
                            <div class="flex text-yellow-500">

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="rating" value="1" class="hidden" />
                                    <span class="text-gray-300 text-2xl">★</span>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="rating" value="2" class="hidden" />
                                    <span class="text-gray-300 text-2xl">★</span>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="rating" value="3" class="hidden" />
                                    <span class="text-gray-300 text-2xl">★</span>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="rating" value="4" class="hidden" />
                                    <span class="text-gray-300 text-2xl">★</span>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="rating" value="5" class="hidden" />
                                    <span class="text-gray-300 text-2xl">★</span>
                                </label>
                            </div>
                        </div>


                        <div class="my-4">
                            <textarea name="komentar" class="w-full p-4 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Tulis komentar Anda di sini..."></textarea>
                        </div>


                        <button type="submit" name="KIRIM_ULASAN" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">Kirim Komentar</button>
                    </form>
                <?php else: ?>
                    <p class="text-gray-600">Anda harus mempunyai akses untuk memberi ulasan</p>
                <?php endif ?>
            </div>

            <div class="mt-4">
                <?php if (!empty($data["komentar"])): ?>
                    <?php foreach ($data["komentar"] as $value): ?>
                        <div class="bg-gray-100 p-4 mb-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($value['nama']) ?></p>
                                <span class="text-sm text-gray-500"><?= date("d M Y, H:i", strtotime($value['dibuat_pada'])) ?></span>
                            </div>
                            <div class="flex text-yellow-500 mt-2">
                                <?php
                                $rating = (int)$value['rating'];
                                for ($i = 1; $i <= 5; $i++):
                                    $starClass = $i <= $rating ? 'text-yellow-500' : 'text-gray-300';
                                ?>
                                    <span class="cursor-pointer <?= $starClass ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm text-gray-600 mt-2"><?= nl2br(htmlspecialchars($value['komentar'])) ?></p>
                            <?php if ($value['id_pengguna'] == $id_pengguna || $role == 1): ?>
                                <div class="mt-2 flex space-x-4">
                                    <form action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                        <input type="hidden" name="id_ulasan" value="<?= $value['id_ulasan'] ?>">
                                        <button type="submit" name="HAPUS_ULASAN" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                    <?php if ($value['id_pengguna'] == $id_pengguna): ?>
                                        <button class="text-blue-600 hover:text-blue-800 edit-btn" data-komentar-id="<?= $value['id_ulasan'] ?>">Edit</button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="edit-form hidden mt-4" id="edit-form-<?= $value['id_ulasan'] ?>">
                                <form method="POST" action="" class="flex flex-col">
                                    <textarea name="komentar" class="w-full p-4 border-gray-300 rounded-md" rows="4" placeholder="Tulis komentar Anda di sini..."><?= htmlspecialchars($value['komentar']) ?></textarea>

                                    <div class="mt-4 flex items-center">
                                        <span class="text-gray-600 mr-4">Rating:</span>
                                        <div class="flex text-yellow-500">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++):
                                                $starClass = $i <= $rating ? 'text-yellow-500' : 'text-gray-300';
                                            ?>
                                                <span class="cursor-pointer <?= $starClass ?>" data-rating="<?= $i ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <input type="hidden" name="rating" id="rating-<?= $value['id_ulasan'] ?>" value="<?= $rating ?>" />
                                        <input type="hidden" name="id_ulasan" value="<?= $value['id_ulasan']  ?>" />
                                    </div>

                                    <button type="submit" name="EDIT_ULASAN" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Simpan Perubahan</button>
                                </form>
                            </div>

                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <p class="text-gray-600">Belum ada komentar untuk kelas ini.</p>
                <?php endif ?>
            </div>


        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const komentarId = this.getAttribute('data-komentar-id');
                const editForm = document.getElementById('edit-form-' + komentarId);

                editForm.classList.toggle('hidden');
            });
        });

        const stars = document.querySelectorAll('.flex .cursor-pointer');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-rating');
                const ratingInput = document.getElementById('rating-' + star.closest('.edit-form').id.split('-')[2]);
                ratingInput.value = rating;

                const formStars = star.closest('.flex').querySelectorAll('.cursor-pointer');
                formStars.forEach(star => {
                    star.classList.remove('text-yellow-500');
                    star.classList.add('text-gray-300');
                });

                for (let i = 0; i < rating; i++) {
                    formStars[i].classList.add('text-yellow-500');
                }
            });
        });
    });
</script>

</script>
<script>
    const stars = document.querySelectorAll('input[type="radio"]');

    function updateStarColors() {
        const selectedRating = document.querySelector('input[name="rating"]:checked')?.value || 0;
        const starLabels = document.querySelectorAll('label span');

        starLabels.forEach((star) => {
            star.classList.add('text-gray-300');
            star.classList.remove('text-yellow-500')
        });

        for (let i = 0; i < selectedRating; i++) {
            starLabels[i].classList.remove('text-gray-300');
            starLabels[i].classList.add('text-yellow-500');
        }
    }

    stars.forEach(star => {
        star.addEventListener('change', updateStarColors);
    });

    window.addEventListener('load', updateStarColors);
</script>

<?php
$content = ob_get_clean();
include '../partials/Master.php';
?>