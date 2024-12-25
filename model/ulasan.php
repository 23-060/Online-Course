<?php

require __DIR__ . "/../db.php";
require_once __DIR__ . "/../autoload/autoload.php";

/**
 * Menambahkan ulasan baru ke dalam database.
 * 
 * @param array $data Data yang berisi ID kelas, ID pengguna, rating, dan komentar.
 * @return int Jumlah baris yang terpengaruh (jumlah ulasan yang berhasil dibuat).
 */
function createUlasan($data)
{
    global $conn;
    $kataTerlarang = $GLOBALS["kata_terlarang"];
    $komentar = $data['komentar'];


    foreach ($kataTerlarang as $kata) {
        $pattern = "/\b" . preg_quote($kata, '/') . "\b/i";
        $replacement = str_repeat('*', strlen($kata));
        $komentar = preg_replace($pattern, $replacement, $komentar);
    }

    $data['komentar'] = $komentar;

    $query = "INSERT INTO ulasan (id_kelas, id_pengguna, rating, komentar) 
              VALUES (:id_kelas, :id_pengguna, :rating, :komentar)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_kelas' => $data['id_kelas'],
        ':id_pengguna' => $data['id_pengguna'],
        ':rating' => $data['rating'],
        ':komentar' => $data['komentar'],
    ]);

    return $stmt->rowCount();
}

/**
 * Menghapus ulasan berdasarkan ID ulasan.
 * 
 * @param array $data Data yang berisi ID ulasan yang akan dihapus.
 * @return int Jumlah baris yang terpengaruh (jumlah ulasan yang berhasil dihapus).
 */
function deleteUlasanById($id_ulasan)
{
    global $conn;
    $query = "DELETE FROM ulasan WHERE id_ulasan = :id_ulasan";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_ulasan' => $id_ulasan,
    ]);

    return $stmt->rowCount();
}

/**
 * Mengambil ulasan berdasarkan filter dan opsi join.
 * 
 * @param array $data Filter yang digunakan untuk mencari ulasan, dan opsi join untuk mendapatkan informasi pengguna.
 * @return array[] Daftar ulasan yang sesuai dengan filter dan opsi join.
 */
function getUlasanByFilter($filter)
{
    global $conn;

    $query = "SELECT ulasan.*, pengguna.* FROM ulasan JOIN pengguna ON ulasan.id_pengguna = pengguna.id_pengguna";

    $conditions = [];
    $params = [];


    if (isset($filter['id_kelas'])) {
        $conditions[] = "ulasan.id_kelas = :id_kelas";
        $params[':id_kelas'] = $filter['id_kelas'];
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY ulasan.dibuat_pada DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Memperbarui ulasan berdasarkan ID ulasan.
 * 
 * @param array $data Data yang berisi ID ulasan, rating, dan komentar.
 * @return int Jumlah baris yang terpengaruh (jumlah ulasan yang berhasil diperbarui).
 */
function updateUlasan($data)
{
    global $conn;

    $kataTerlarang = $GLOBALS["kata_terlarang"];
    $komentar = $data['komentar'];
    foreach ($kataTerlarang as $kata) {
        $pattern = "/\b" . preg_quote($kata, '/') . "\b/i";
        $replacement = str_repeat('*', strlen($kata));
        $komentar = preg_replace($pattern, $replacement, $komentar);
    }
    $data['komentar'] = $komentar;


    $query = "UPDATE ulasan 
              SET rating = :rating, komentar = :komentar 
              WHERE id_ulasan = :id_ulasan";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':id_ulasan' => $data['id_ulasan'],
        ':rating' => $data['rating'],
        ':komentar' => $data['komentar'],
    ]);

    return $stmt->rowCount();
}
