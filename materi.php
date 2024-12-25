<?php
ob_start();
session_start();
require_once "./model/materi.php";
require_once "./model/pendaftaranKelas.php";
require_once "./model/quiz.php";
require_once "./model/pertanyaan.php";
require_once "./autoload/autoload.php";

$id_pengguna = $_SESSION['user']["id_pengguna"];
$id_materi = $_GET["id_materi"] ?? null;
$id_kelas = $_GET["id_kelas"];

$data["materi"] = getMateriByFilter(["id_kelas" => $id_kelas]);
$data["quiz"] = getQuizByFilter(["id_materi" => $id_materi]);
$filter = [
    "id_kelas" => $id_kelas,
    "id_pengguna" => $id_pengguna,
    "status" => ['Dalam Proses', 'Selesai']
];

$data["pendaftaran_kelas"] = getPendaftaranKelasByFilter($filter);
if (empty($data["pendaftaran_kelas"])) {
    showAlertAndRedirect("Kamu Tidak Punya Akses Ke Materi Ini.", "./cari_kelas.php");
}

$materi = [];

foreach ($data["materi"] as $value) {
    if ($value["id_materi"] == $id_materi) {
        $materi = $value;
        break;
    }
}


?>

<div class="container mx-auto ">
    <div class="flex h-max py-8 gap-4 ">
        <div class="w-1/4 bg-white border-r overflow-y-auto rounded-lg shadow-lg py-4">
            <h2 class="text-xl font-bold p-4">Daftar Materi</h2>
            <ul class="space-y-2">
                <?php foreach ($data["materi"] as $row): ?>
                    <li>
                        <a href="?id_kelas=<?= $id_kelas ?>&id_materi=<?= $row['id_materi'] ?>"
                            class="block px-4 py-2 hover:bg-gray-200">
                            <?= $row['judul_materi'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="w-3/4 bg-white p-6 overflow-y-auto rounded-lg shadow-lg">
            <?php if ($materi): ?>

                <?php if (!empty($materi['path_file'])): ?>
                    <iframe id="contentFrame" src=".<?= $materi['path_file'] ?>"
                        width="100%" class="border rounded-lg">
                    </iframe>
                <?php endif; ?>
                <?php if (!empty($data["quiz"])): ?>
                    <div class="mt-6">
                        <a href="./quiz.php?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $data["quiz"][0]["id_quiz"] ?>"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Mulai Quiz
                        </a>
                    </div>
                <?php endif ?>
            <?php else: ?>
                <p class="text-gray-500">Silakan pilih materi dari sidebar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    window.addEventListener("load", function() {
        var iframe = document.getElementById("contentFrame");

        function adjustIframeHeight() {
            if (iframe.contentDocument) {
                var contentHeight = iframe.contentDocument.documentElement.scrollHeight;
                iframe.style.height = contentHeight + 10 + "px";
            }
        }

        adjustIframeHeight();

        iframe.onload = function() {
            adjustIframeHeight();
        };
    });
</script>


<?php
$content = ob_get_clean();
require "./partials/master.php";
