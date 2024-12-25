<?php
session_start();
require_once 'db.php';
require_once 'question.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['peran'] != 1) {
    http_response_code(403);
    exit('Unauthorized');
}

$question = new Question($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $media_url = '';
        $target_dir = "uploads/";
        
        // Pastikan direktori ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Proses file media jika ada
        if (isset($_FILES['media_file']) && $_FILES['media_file']['error'] === 0) {
            $file_extension = pathinfo($_FILES["media_file"]["name"], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file)) {
                $media_url = $target_file;
            } else {
                echo "Error: Gagal mengunggah file.";
                exit;
            }
        }

        $data = [
            'category' => $_POST['category'],
            'question' => $_POST['question'],
            'options' => json_encode($_POST['options']), // Encode options sebagai JSON
            'correct_answer' => $_POST['correct_answer'],
            'media_type' => $_POST['media_type'],
            'media_url' => $media_url
        ];

        if ($action === 'add') {
            $question->addQuestion($data);
        } else {
            $question->updateQuestion($_POST['question_id'], $data);
        }

        header('Location: index.php');
        exit;
    }

    if ($action === 'delete') {
        $data = json_decode(file_get_contents('php://input'), true);
        $question->deleteQuestion($data['question_id']);
        exit;
    }
}
?>
