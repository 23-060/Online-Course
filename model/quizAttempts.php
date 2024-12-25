<?php

require __DIR__ . "/../db.php";

/**
 * Mengambil data percobaan quiz berdasarkan id_quiz dan id_pengguna.
 *
 * @param array $data Data yang berisi id_quiz dan id_pengguna.
 * @return array Data percobaan quiz yang ditemukan, atau array kosong jika tidak ditemukan.
 */
function getQuizAttempt($data)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM quiz_attempts WHERE id_quiz = :id_quiz AND id_pengguna = :id_pengguna");
    $stmt->bindParam(':id_quiz', $data['id_quiz']);
    $stmt->bindParam(':id_pengguna', $data['id_pengguna']);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Menambahkan data percobaan quiz baru.
 *
 * @param array $data Data yang berisi id_quiz, id_pengguna, total_percobaan, nilai_akhir, dan status.
 * @return bool Status eksekusi query.
 */
function insertQuizAttempt($data)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO quiz_attempts (id_quiz, id_pengguna, total_percobaan, nilai_akhir, status) 
                            VALUES (:id_quiz, :id_pengguna, :total_percobaan, :nilai_akhir, :status)");
    $stmt->bindParam(':id_quiz', $data['id_quiz']);
    $stmt->bindParam(':id_pengguna', $data['id_pengguna']);
    $stmt->bindParam(':total_percobaan', $data['total_percobaan']);
    $stmt->bindParam(':nilai_akhir', $data['nilai_akhir']);
    $stmt->bindParam(':status', $data['status']);
    return $stmt->execute();
}

/**
 * Memperbarui data percobaan quiz berdasarkan id_quiz dan id_pengguna.
 *
 * @param array $data Data yang berisi id_quiz, id_pengguna, total_percobaan, nilai_akhir, dan status.
 * @return bool Status eksekusi query.
 */
function updateQuizAttempt($data)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE quiz_attempts 
                            SET total_percobaan = :total_percobaan, nilai_akhir = :nilai_akhir, 
                                status = :status, updated_at = NOW() 
                            WHERE id_quiz = :id_quiz AND id_pengguna = :id_pengguna");
    $stmt->bindParam(':id_quiz', $data['id_quiz']);
    $stmt->bindParam(':id_pengguna', $data['id_pengguna']);
    $stmt->bindParam(':total_percobaan', $data['total_percobaan']);
    $stmt->bindParam(':nilai_akhir', $data['nilai_akhir']);
    $stmt->bindParam(':status', $data['status']);
    return $stmt->execute();
}

function getStatistikByUser($id_kelas, $id_pengguna)
{
    global $conn;
    $query = "SELECT q.id_quiz, q.judul, 
                     COALESCE(COUNT(qa.id_attempt), 0) AS jumlah_percobaan,
                     COALESCE(qa.nilai_akhir, 0) AS nilai_akhir
              FROM quiz q
              LEFT JOIN quiz_attempts qa ON q.id_quiz = qa.id_quiz 
                                         AND qa.id_pengguna = :id_pengguna
              JOIN materi m ON m.id_materi = q.id_materi
              WHERE m.id_kelas = :id_kelas
              GROUP BY q.id_quiz, q.judul, qa.nilai_akhir
              ORDER BY q.id_quiz";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id_kelas", $id_kelas);
    $stmt->bindParam(":id_pengguna", $id_pengguna);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
