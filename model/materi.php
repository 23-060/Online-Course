<?php
require __DIR__ . "/../db.php";

/**
 * Fungsi ini digunakan untuk mengambil data materi berdasarkan filter.
 * 
 * @param array $filter Opsi filter yang digunakan dalam query.
 * @return array Mengembalikan array hasil query materi yang sesuai dengan filter yang diberikan.
 */
function getMateriByFilter($filter)
{
    global $conn;

    $query = "SELECT * FROM materi";
    $params = [];
    $conditions = [];



    if (isset($filter["id_kelas"])) {
        $conditions[] = "materi.id_kelas = :id_kelas";
        $params[":id_kelas"] = $filter["id_kelas"];
    }

    if ($conditions) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMateriWhereQuizIsNull($id_kelas, $id_materi = null)
{
    global $conn;
    $query = "SELECT materi.* FROM materi LEFT JOIN quiz ON quiz.id_materi = materi.id_materi WHERE materi.id_kelas = :id_kelas AND quiz.id_quiz IS NULL";
    if ($id_materi) {
        $query .= " OR materi.id_materi = :id_materi";
    }
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_kelas', $id_kelas);
    if ($id_materi) {
        $stmt->bindParam(':id_materi', $id_materi);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fungsi ini digunakan untuk mengambil data materi berdasarkan ID materi.
 * 
 * @param int $id_materi ID materi yang akan diambil.
 * @return array Data materi yang ditemukan.
 */
function getMateriById($id_materi)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM materi WHERE id_materi = :id_materi");
    $stmt->bindValue(":id_materi", $id_materi);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Fungsi ini digunakan untuk menambahkan materi baru.
 * 
 * @param array $data Data materi yang akan dimasukkan.
 * @return int Mengembalikan ID materi yang baru ditambahkan.
 */
function createMateri($data)
{
    global $conn;


    $stmt = $conn->prepare("INSERT INTO materi (id_kelas, judul_materi, deskripsi, path_file) 
                            VALUES (:id_kelas, :judul_materi, :deskripsi, :path_file)");

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $conn->lastInsertId();
}

/**
 * Fungsi ini digunakan untuk memperbarui data materi yang sudah ada.
 * 
 * @param array $data Data materi yang diperbarui.
 * @return int Mengembalikan jumlah baris yang diperbarui.
 */
function updateMateri($data)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE materi SET judul_materi = :judul_materi, deskripsi = :deskripsi, path_file = :path_file
                            WHERE id_materi = :id_materi");

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    return $stmt->rowCount();
}

/**
 * Fungsi ini digunakan untuk menghapus materi berdasarkan ID materi.
 * 
 * @param int $id_materi ID materi yang akan dihapus.
 * @return int Mengembalikan jumlah baris yang dihapus.
 */
function deleteMateri($id_materi)
{
    global $conn;

    $stmt = $conn->prepare("DELETE FROM materi WHERE id_materi = :id_materi");
    $stmt->bindValue(":id_materi", $id_materi);
    $stmt->execute();

    return $stmt->rowCount();
}
