<?php
ob_start();
session_start();
require_once "./model/materi.php";
require_once "./model/pendaftaranKelas.php";
require_once "./model/quiz.php";
require_once "./model/pertanyaan.php";
require_once "./model/quizAttempts.php";
require_once "./autoload/autoload.php";

$id_pengguna = $_SESSION['user']["id_pengguna"];
$id_kelas = $_GET["id_kelas"];
$id_quiz = $_GET["id_quiz"];

$result = isset($_GET['result']) && $_GET['result'] == 'true';
$start = isset($_GET['start']) && $_GET['start'] == 'true';

$data["materi"] = getMateriByFilter(["id_kelas" => $id_kelas]);
$filter = [
    "id_kelas" => $id_kelas,
    "id_pengguna" => $id_pengguna,
    "status" => ["Dalam Proses", "Selesai"]
];

$data["pendaftaran_kelas"] = getPendaftaranKelasByFilter($filter);

$quiz = getQuizByFilter(["id_quiz" => $id_quiz]);
$questions = getPertanyaanByFilter(["id_quiz" => $id_quiz]);

$correctCount = null;
$score = 0;
$isPassed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SUBMIT_QUIZ'])) {
    $totalQuestions = count($questions);
    $correctCount = 0;
    $userAnswers = $_POST['answers'] ?? [];

    foreach ($questions as $question) {
        $correctAnswer = trim($question['jawaban_benar']);
        $userAnswer = trim($userAnswers[$question['id_pertanyaan']] ?? "");
        if ($userAnswer == $correctAnswer) {
            $correctCount++;
        }
    }

    $score = round(($correctCount / $totalQuestions) * 100, 2);
    $isPassed = $score >= 80;
    $data["quiz"] = getQuizAttempt(["id_quiz" => $id_quiz, "id_pengguna" => $id_pengguna]);

    if (!$data["quiz"]) {
        insertQuizAttempt([
            'id_quiz' => $id_quiz,
            'id_pengguna' => $id_pengguna,
            'total_percobaan' => 1,
            'nilai_akhir' => $score,
            'status' => $isPassed ? 'Selesai' : 'Dalam Proses'
        ]);
    } else {
        $newTotalPercobaan = $data["quiz"]['total_percobaan'] + 1;
        $newNilaiAkhir = max($score, $data["quiz"]['nilai_akhir']);
        $newStatus = $isPassed ? 'Selesai' : $data["quiz"]['status'];

        updateQuizAttempt([
            'id_quiz' => $id_quiz,
            'id_pengguna' => $id_pengguna,
            'total_percobaan' => $newTotalPercobaan,
            'nilai_akhir' => $newNilaiAkhir,
            'status' => $newStatus
        ]);
    }
    updateStatusPendaftaranKelas($id_kelas, $id_pengguna);
}
$quiz_attempt = getQuizAttempt(["id_quiz" => $id_quiz, "id_pengguna" => $id_pengguna]);

?>
<div class="container mx-auto">
    <div class="flex h-full py-8 gap-8">

        <div class="w-1/4 bg-white border-r overflow-y-auto rounded-lg shadow-lg py-4">
            <h2 class="text-xl font-bold p-4">Daftar Materi</h2>
            <ul class="space-y-2">
                <?php foreach ($data["materi"] as $materi): ?>
                    <li>
                        <a href="./materi.php?id_kelas=<?= $id_kelas ?>&id_materi=<?= $materi['id_materi'] ?>"
                            class="block px-4 py-2 hover:bg-gray-200 <?= ($id_quiz == $materi['id_materi']) ? 'bg-gray-300' : '' ?>">
                            <?= htmlspecialchars($materi['judul_materi']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <div class="w-3/4 bg-white p-6 overflow-y-auto rounded-lg shadow-lg">
            <?php if ($quiz): ?>
                <h1 class="text-3xl font-bold mb-4"><?= $quiz['judul'] ?></h1>
                <p class="text-gray-700 mb-6"><?= $quiz['deskripsi'] ?></p>


                <?php if ($quiz_attempt && !$start && !$result): ?>
                    <div class="p-4 <?= $quiz_attempt['nilai_akhir'] == 100 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?> rounded-md mb-6">
                        <h3 class="text-lg font-bold">Nilai Sebelumnya</h3>
                        <p>Nilai Anda Sebelumnya: <strong><?= $quiz_attempt['nilai_akhir'] ?>/100</strong></p>
                        <p>Jumlah Percobaan Quiz: <strong><?= $quiz_attempt['total_percobaan'] ?></strong></p>

                        <?php if ($quiz_attempt['nilai_akhir'] == 100): ?>
                            <p class="mt-4">Selamat! Anda telah mencapai nilai maksimal (100/100). Tidak perlu mengulang quiz.</p>
                        <?php else: ?>
                            <a href="?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $quiz["id_quiz"] ?>&start=true"
                                class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 inline-block">
                                Mulai Ulang Quiz
                            </a>
                        <?php endif; ?>
                    </div>
                <?php elseif ($result): ?>
                    <div class="p-4 <?= $isPassed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?> rounded-md mb-6">
                        <h3 class="text-lg font-bold"><?= $isPassed ? 'Selamat!' : 'Coba Lagi' ?></h3>
                        <p>Jawaban Benar: <strong><?= $correctCount ?></strong> dari <strong><?= count($questions) ?></strong> pertanyaan.</p>
                        <p>Nilai Anda: <strong><?= $score ?>/100</strong></p>
                        <p>Jumlah Percobaan Quiz: <strong><?= $quiz_attempt['total_percobaan'] ?></strong></p>

                        <p class="mt-2">
                            <?php if ($isPassed && $quiz_attempt['nilai_akhir'] == 100): ?>
                                Selamat! Anda telah mencapai nilai maksimal (100/100). Tidak perlu mengulang quiz.
                            <?php elseif ($isPassed): ?>
                                Anda telah berhasil menyelesaikan quiz ini, Anda dapat mengulang jika ingin mendapatkan nilai maksimal.
                            <?php else: ?>
                                Nilai Anda belum mencukupi, silakan coba lagi untuk mendapatkan skor minimal 80.
                            <?php endif; ?>
                        </p>
                        <?php if ($quiz_attempt['nilai_akhir'] != 100): ?>
                            <a href="?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $quiz["id_quiz"] ?>&start=true"
                                class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 inline-block">
                                Mulai Ulang Quiz
                            </a>
                        <?php endif; ?>
                    </div>
                <?php elseif (!$start && !$result): ?>
                    <p class="text-lg text-gray-700 mb-6">Klik tombol di bawah untuk memulai quiz.</p>
                    <a href="?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $quiz["id_quiz"] ?>&start=true"
                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 inline-block">
                        Mulai Quiz
                    </a>
                <?php endif; ?>

                <?php if ($start): ?>
                    <form method="POST" action="?id_kelas=<?= $id_kelas ?>&id_quiz=<?= $quiz["id_quiz"] ?>&result=true">
                        <h2 class="text-xl font-semibold mb-4">Pertanyaan:</h2>
                        <ul class="space-y-4">
                            <?php foreach ($questions as $index => $question): ?>
                                <li class="bg-white p-4 shadow rounded-md">
                                    <p class="text-gray-800 mb-2"><?= htmlspecialchars($question['teks_pertanyaan']) ?></p>
                                    <ul class="space-y-1">
                                        <?php
                                        $choices = explode(',', $question['pilihan']);
                                        foreach ($choices as $choice):
                                        ?>
                                            <li>
                                                <label class="flex items-center gap-2">
                                                    <input type="radio" name="answers[<?= $question['id_pertanyaan'] ?>]"
                                                        value="<?= htmlspecialchars($choice) ?>" class="form-radio" required>
                                                    <?= htmlspecialchars($choice) ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="submit" name="SUBMIT_QUIZ"
                            class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Submit Jawaban
                        </button>
                    </form>
                <?php endif ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
require "./partials/Master.php";
?>