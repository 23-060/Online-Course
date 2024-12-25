<?php ob_start(); ?>
<?php
session_start();
require_once 'db.php';
require_once 'question.php';

if (isset($_SESSION['user']) && $_SESSION['user']['peran'] != 1) {
    $userid = $_SESSION['user'];
    echo "<script>alert(' ADMIN DATANG !! '); window.location.href = '../index.php'</script>";
}
$question = new Question($pdo);
$categories = [
    'Penalaran Umum' => 'Kemampuan Penalaran Umum',
    'Pengetahuan Umum' => 'Pengetahuan dan Pemahaman Umum',
    'Pemahaman Bacaan' => 'Pemahaman Bacaan dan Menulis',
    'Bahasa Inggris' => 'Bahasa Inggris',
    'Literasi Bahasa Indonesia' => 'Literasi Bahasa Indonesia'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Bank Soal</title>
    <style>
        /* General Reset */
        body,
        h1,
        h2,
        h3,
        p,
        ul,
        li,
        input,
        button,
        select,
        textarea {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-container h1 {
            text-align: center;
            color: #0052d4;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .question-form {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        .question-form h2 {
            color: #0052d4;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .question-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #0052d4;
        }

        .question-form input[type="text"],
        .question-form textarea,
        .question-form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-top: 5px;
        }

        #options .option-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        #options input[type="radio"] {
            margin-right: 5px;
        }

        .action-buttons button {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-buttons button[type="submit"] {
            background-color: #0052d4;
            color: white;
        }

        .action-buttons button[type="reset"] {
            background-color: #ddd;
            color: #333;
        }

        .questions-list .question-item {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .question-item h3 {
            color: #0052d4;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .question-item .action-buttons button.delete {
            background-color: #d9534f;
            color: white;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <h1>Admin Panel - Manajemen Soal</h1>
        <div class="question-form">
            <h2>Tambah/Edit Soal</h2>
            <form id="questionForm" method="POST" action="process.php" enctype="multipart/form-data">
                <label for="category">Pilih Kategori:</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $value => $label): ?>
                        <option value="<?php echo htmlspecialchars($value); ?>">
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="question_id" value="">
                <label for="questionText">Pertanyaan:</label>
                <textarea id="questionText" name="question" required></textarea>
                <label>Pilihan Jawaban:</label>
                <div id="options">
                    <?php foreach (range('A', 'D') as $option): ?>
                        <div class="option-container">
                            <input type="radio" name="correct_answer" value="<?php echo $option; ?>" required>
                            <input type="text" name="options[]" placeholder="Pilihan <?php echo $option; ?>" required>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="action-buttons">
                    <button type="submit">Simpan Soal</button>
                    <button type="reset">Reset</button>
                </div>
            </form>
        </div>
        <div class="questions-list" id="questionsList">
            <?php
            $currentQuestions = $question->getQuestions($_GET['category'] ?? 'Penalaran Umum');
            foreach ($currentQuestions as $index => $q):
            ?>
                <div class="question-item">
                    <h3>Soal <?php echo $index + 1; ?></h3>
                    <p><?php echo htmlspecialchars($q['question']); ?></p>
                    <ul>
                        <?php
                        $options = json_decode($q['options'], true);
                        foreach ($options as $index => $option):
                        ?>
                            <li>
                                <?php echo chr(65 + $index); ?>:
                                <?php echo htmlspecialchars($option); ?>
                                <?php if ($q['correct_answer'] === chr(65 + $index)): ?>
                                    (Jawaban Benar)
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="action-buttons">
                        <button onclick="editQuestion(<?php echo $q['id']; ?>)">Edit</button>
                        <button class="delete" onclick="deleteQuestion(<?php echo $q['id']; ?>)">Hapus</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
<?php
$content = ob_get_clean();
include '../MasterAdmin.php';
?>