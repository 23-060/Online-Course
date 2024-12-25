<?php

require __DIR__ . "/../db.php";

/**
 * Mengambil data pertanyaan berdasarkan filter yang diberikan.
 * 
 * @param array $data Data filter untuk pencarian pertanyaan, seperti `id_quiz` dan `id_pertanyaan`.
 * @return array|array[] Mengembalikan satu pertanyaan jika `id_pertanyaan` diberikan, atau daftar pertanyaan jika tidak.
 */
function getPertanyaanByFilter($filter)
{
    global $conn;

    $query = "SELECT * FROM pertanyaan";
    $params = [];

    if (isset($filter['id_quiz'])) {
        $query .= " WHERE id_quiz = :id_quiz";
        $params[':id_quiz'] = $filter['id_quiz'];
    }

    if (isset($filter['id_pertanyaan'])) {
        $query .= isset($filter['id_quiz']) ? " AND id_pertanyaan = :id_pertanyaan" : " WHERE id_pertanyaan = :id_pertanyaan";
        $params[':id_pertanyaan'] = $filter['id_pertanyaan'];
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    return isset($filter['id_pertanyaan']) ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Menambahkan pertanyaan baru ke dalam database.
 * 
 * @param array $data Data yang diperlukan untuk menambahkan pertanyaan.
 * @return int ID dari pertanyaan yang baru ditambahkan.
 */
function createPertanyaan($data)
{
    global $conn;

    $query = "
        INSERT INTO pertanyaan (id_quiz, teks_pertanyaan, pilihan, jawaban_benar) 
        VALUES (:id_quiz, :teks_pertanyaan, :pilihan, :jawaban_benar)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_quiz' => $data['id_quiz'],
        ':teks_pertanyaan' => $data['teks_pertanyaan'],
        ':pilihan' => $data['pilihan'],
        ':jawaban_benar' => $data['jawaban_benar']
    ]);

    return $conn->lastInsertId();
}

/**
 * Memperbarui pertanyaan yang sudah ada.
 * 
 * @param array $data Data yang diperlukan untuk memperbarui pertanyaan.
 * @return int Jumlah baris yang terpengaruh oleh update.
 */
function updatePertanyaan($data)
{
    global $conn;

    $query = "
        UPDATE pertanyaan 
        SET teks_pertanyaan = :teks_pertanyaan, 
            pilihan = :pilihan, 
            jawaban_benar = :jawaban_benar 
        WHERE id_pertanyaan = :id_pertanyaan
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':teks_pertanyaan' => $data['teks_pertanyaan'],
        ':pilihan' => $data['pilihan'],
        ':jawaban_benar' => $data['jawaban_benar'],
        ':id_pertanyaan' => $data['id_pertanyaan']
    ]);

    return $stmt->rowCount();
}

/**
 * Menghapus pertanyaan berdasarkan ID pertanyaan.
 * 
 * @param int $id_pertanyaan ID pertanyaan yang akan dihapus.
 * @return int Jumlah baris yang dihapus.
 */
function deletePertanyaan($id_pertanyaan)
{
    global $conn;

    $query = "DELETE FROM pertanyaan WHERE id_pertanyaan = :id_pertanyaan";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id_pertanyaan' => $id_pertanyaan]);

    return $stmt->rowCount();
}
