<?php

require __DIR__ . "/../db.php";

/**
 * Membuat quiz baru di dalam database.
 * 
 * @param array $data Data yang berisi ID kelas, judul quiz, dan deskripsi.
 * @return int Jumlah baris yang terpengaruh (jumlah quiz yang berhasil dibuat).
 */
function createQuiz($data)
{
    global $conn;
    $query = "
        INSERT INTO quiz (id_materi, judul, deskripsi) 
        VALUES (:id_materi, :judul, :deskripsi)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':judul' => $data['judul'],
        ':deskripsi' => $data['deskripsi'],
        ':id_materi' => $data['id_materi']
    ]);
    return $stmt->rowCount();
}

/**
 * Memperbarui quiz berdasarkan ID quiz.
 * 
 * @param array $data Data yang berisi ID quiz, judul, dan deskripsi.
 * @return int Jumlah baris yang terpengaruh (jumlah quiz yang berhasil diperbarui).
 */
function updateQuiz($data)
{
    global $conn;
    $query = "
        UPDATE quiz 
        SET judul = :judul, deskripsi = :deskripsi, id_materi = :id_materi 
        WHERE id_quiz = :id_quiz
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':judul' => $data['judul'],
        ':deskripsi' => $data['deskripsi'],
        ':id_materi' => $data['id_materi'],
        ':id_quiz' => $data['id_quiz']
    ]);
    return $stmt->rowCount();
}

/**
 * Mengambil quiz berdasarkan filter yang diberikan.
 * 
 * @param array $data Filter yang digunakan untuk mencari quiz (misalnya ID quiz).
 * @return array Hasil query quiz yang sesuai dengan filter.
 */
function getQuizByFilter($filter)
{
    global $conn;
    $query = "SELECT quiz.*, materi.id_materi, materi.judul_materi FROM quiz LEFT JOIN materi ON materi.id_materi = quiz.id_materi";
    $conditions = [];
    $params = [];
    if (isset($filter["id_materi"])) {
        $conditions[] = "quiz.id_materi = :id_materi";
        $params[":id_materi"] = $filter["id_materi"];
    }

    if (isset($filter["id_quiz"])) {
        $conditions[] = "quiz.id_quiz = :id_quiz";
        $params[":id_quiz"] = $filter["id_quiz"];
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    return isset($filter["id_quiz"]) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Menghapus quiz berdasarkan ID quiz.
 * 
 * @param array $data Data yang berisi ID quiz yang ingin dihapus.
 * @return int Jumlah baris yang terpengaruh (jumlah quiz yang berhasil dihapus).
 */
function deleteQuiz($data)
{
    global $conn;
    $query = "DELETE FROM quiz WHERE id_quiz = :id_quiz";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id_quiz", $data["id_quiz"]);
    $stmt->execute();
    return $stmt->rowCount();
}
