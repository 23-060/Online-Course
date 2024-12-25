<?php
session_start();
ob_start();

require_once "./../model/quizAttempts.php";
require_once "./../model/kelas.php";
require_once "./../autoload/autoload.php";

$id_pengguna = $_SESSION["user"]['id_pengguna'] ?? null;
$id_kelas = $_GET['id_kelas'] ?? null;

if (!$id_pengguna || !$id_kelas) {
    die("Akses ditolak. User atau kelas tidak valid.");
}

$kelas = getKelasById($id_kelas);
$nama_kelas = $kelas['nama_kelas'] ?? "Kelas Tidak Ditemukan";

$data["quiz"] = getStatistikByUser($id_kelas, $id_pengguna);

$quizLabels = [];
$userAttempts = [];
$nilaiAkhir = [];
$totalPercobaan = 0;
$totalQuizDikerjakan = 0;

foreach ($data["quiz"] as $row) {
    if ($row['jumlah_percobaan'] > 0) {
        $totalQuizDikerjakan++;
    }
    $quizLabels[] = $row['judul'];
    $userAttempts[] = $row['jumlah_percobaan'];
    $nilaiAkhir[] = $row['nilai_akhir'];
    $totalPercobaan += $row['jumlah_percobaan'];
}


?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="mx-auto bg-white p-8 rounded-lg shadow-xl">
        <!-- Header Informasi Kelas -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Statistik Quiz Anda di <span class="text-blue-500"><?php echo htmlspecialchars($nama_kelas); ?></span>
        </h2>

        <div class="mb-6">
            <p class="text-gray-700 text-lg">
                Total Quiz di Kelas: <strong><?php echo count($data["quiz"]); ?></strong>
            </p>
            <p class="text-gray-700 text-lg">
                Total Quiz Dikerjakan: <strong><?php echo $totalQuizDikerjakan; ?></strong>
            </p>
            <p class="text-gray-700 text-lg">
                Total Percobaan: <strong><?php echo $totalPercobaan; ?></strong>
            </p>
        </div>

        <div class="mb-8">
            <div class="flex border-b border-gray-300">
                <button id="tab1" class="tab-button p-2 px-4 text-gray-700 hover:text-blue-500 focus:outline-none border-b-2 border-transparent hover:border-blue-500">Grafik Percobaan Quiz</button>
                <button id="tab2" class="tab-button p-2 px-4 text-gray-700 hover:text-blue-500 focus:outline-none border-b-2 border-transparent hover:border-blue-500">Grafik Nilai Akhir Quiz</button>
            </div>
        </div>

        <div id="tab1Content" class="tab-content mb-8 hidden">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Grafik Percobaan Quiz</h3>
            <canvas id="quizChart" class="w-full h-80"></canvas>
        </div>

        <div id="tab2Content" class="tab-content mb-8 hidden">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Grafik Nilai Akhir Quiz</h3>
            <canvas id="nilaiAkhirChart" class="w-full h-80"></canvas>
        </div>

        <?php if (!empty($data["quiz"])): ?>
            <div class="mt-6">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Detail Percobaan Quiz</h3>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Judul Quiz</th>
                            <th class="border border-gray-300 px-4 py-2">Jumlah Percobaan</th>
                            <th class="border border-gray-300 px-4 py-2">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data["quiz"] as $index => $row): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $index + 1; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['judul']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $row['jumlah_percobaan']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $row['nilai_akhir']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function switchTab(tabId) {
        const allTabs = document.querySelectorAll('.tab-button');
        const allTabContents = document.querySelectorAll('.tab-content');


        allTabs.forEach(tab => tab.classList.remove('text-blue-500', 'font-bold', 'border-blue-500'));


        document.getElementById(tabId).classList.add('text-blue-500', 'font-bold', 'border-blue-500');


        allTabContents.forEach(content => content.classList.add('hidden'));

        document.getElementById(tabId + 'Content').classList.remove('hidden');
    }


    document.getElementById('tab1').addEventListener('click', () => switchTab('tab1'));
    document.getElementById('tab2').addEventListener('click', () => switchTab('tab2'));


    switchTab('tab1');


    const quizChartCtx = document.getElementById('quizChart').getContext('2d');
    new Chart(quizChartCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($quizLabels); ?>,
            datasets: [{
                label: 'Jumlah Percobaan',
                data: <?php echo json_encode($userAttempts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            animation: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const nilaiAkhirChartCtx = document.getElementById('nilaiAkhirChart').getContext('2d');
    new Chart(nilaiAkhirChartCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($quizLabels); ?>,
            datasets: [{
                label: 'Nilai Akhir',
                data: <?php echo json_encode($nilaiAkhir); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            animation: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>



<?php
$content = ob_get_clean();
include '../partials/Master.php'; 

?>