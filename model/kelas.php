<?php

require __DIR__ . "/../db.php";

/**
 * Mengambil data kelas berdasarkan filter yang diberikan.
 * 
 * @param array $filter Filter untuk data kelas, termasuk tingkat, pelajaran, dan pengajar.
 * @return array Data kelas yang ditemukan.
 */
function getKelasByFilter($filter = [])
{
    global $conn;

    $query = "
        SELECT kelas.*, pengguna.id_pengguna AS id_pengajar, pengguna.nama AS nama_pengajar, transaksi.status_transaksi, pendaftaran_kelas.status AS status_pendaftaran
        FROM kelas
        LEFT JOIN pengguna ON pengguna.id_pengguna = kelas.id_pengajar
        LEFT JOIN (
                SELECT id_kelas, status, id_pendaftaran, id_pengguna 
                FROM pendaftaran_kelas
                WHERE status NOT IN ('Batal') 
                OR id_pendaftaran = (
                        SELECT MAX(id_pendaftaran)
                        FROM pendaftaran_kelas AS pk
                        WHERE pk.id_kelas = pendaftaran_kelas.id_kelas AND status = 'Batal' AND status = 'Dalam Proses'
                    )
                ) AS pendaftaran_kelas ON kelas.id_kelas = pendaftaran_kelas.id_kelas AND pendaftaran_kelas.id_pengguna = :id_pengguna
        LEFT JOIN transaksi ON transaksi.id_pendaftaran = pendaftaran_kelas.id_pendaftaran
    ";

    $conditions = [];
    $params = [];


    if (isset($filter['tingkat'])) {
        $conditions[] = "kelas.tingkat = :tingkat";
        $params['tingkat'] = $filter['tingkat'];
    }

    if (isset($filter['pelajaran'])) {
        $conditions[] = "kelas.pelajaran = :pelajaran";
        $params['pelajaran'] = $filter['pelajaran'];
    }

    if (isset($filter['id_pengajar'])) {
        $conditions[] = "kelas.id_pengajar = :id_pengajar";
        $params['id_pengajar'] = $filter['id_pengajar'];
    }
    if (isset($filter['id_kelas'])) {
        $conditions[] = "kelas.id_kelas = :id_kelas";
        $params['id_kelas'] = $filter['id_kelas'];
    }

    if (isset($filter['id_pengguna'])) {
        $params['id_pengguna'] = $filter['id_pengguna'];
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);


    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Mengambil data kelas dengan pengajar, berdasarkan ID pengajar.
 * 
 * @param int $id_pengajar ID pengajar yang digunakan sebagai filter.
 * @return array Data kelas yang ditemukan.
 */
function getKelasByPengajar($id_pengajar)
{
    global $conn;
    $query = "SELECT kelas.*, pengguna.nama AS nama_pengajar
              FROM kelas
              LEFT JOIN pengguna ON kelas.id_pengajar = pengguna.id_pengguna
              WHERE kelas.id_pengajar = :id_pengajar";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_pengajar', $id_pengajar);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Mengambil data kelas berdasarkan ID kelas.
 * 
 * @param int $id_kelas ID kelas yang ingin diambil.
 * @return array Data kelas yang ditemukan.
 */
function getKelasById($id_kelas)
{
    global $conn;

    $query = "SELECT kelas.*, pengguna.nama AS nama_pengajar, pengguna.email AS email_pengajar
              FROM kelas
              LEFT JOIN pengguna ON kelas.id_pengajar = pengguna.id_pengguna
              WHERE kelas.id_kelas = :id_kelas";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_kelas', $id_kelas);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Menambahkan data kelas baru.
 * 
 * @param array $data Data kelas yang akan ditambahkan.
 * @return int ID kelas yang baru ditambahkan.
 */
function createKelas($data)
{
    global $conn;

    $query = "INSERT INTO kelas (nama_kelas, tingkat, pelajaran, deskripsi, id_pengajar, harga)
              VALUES (:nama_kelas, :tingkat, :pelajaran, :deskripsi, :id_pengajar, :harga)";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':nama_kelas', $data['nama_kelas']);
    $stmt->bindValue(':tingkat', $data['tingkat']);
    $stmt->bindValue(':pelajaran', $data['pelajaran']);
    $stmt->bindValue(':deskripsi', $data['deskripsi']);
    $stmt->bindValue(':id_pengajar', $data['id_pengajar']);
    $stmt->bindValue(':harga', $data['harga']);
    $stmt->execute();

    return $conn->lastInsertId();
}

/**
 * Memperbarui data kelas berdasarkan ID kelas.
 * 
 * @param int $id_kelas ID kelas yang akan diperbarui.
 * @param array $data Data kelas yang akan diperbarui.
 * @return int Jumlah baris yang terpengaruh.
 */
function updateKelas($data)
{
    global $conn;

    $query = "UPDATE kelas
              SET nama_kelas = :nama_kelas, tingkat = :tingkat, pelajaran = :pelajaran,
                  deskripsi = :deskripsi, harga = :harga
              WHERE id_kelas = :id_kelas";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':nama_kelas', $data['nama_kelas']);
    $stmt->bindValue(':tingkat', $data['tingkat']);
    $stmt->bindValue(':pelajaran', $data['pelajaran']);
    $stmt->bindValue(':deskripsi', $data['deskripsi']);
    $stmt->bindValue(':harga', $data['harga']);
    $stmt->bindValue(':id_kelas', $data['id_kelas']);
    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Menghapus kelas berdasarkan ID kelas.
 * 
 * @param int $id_kelas ID kelas yang akan dihapus.
 * @return int Jumlah baris yang terpengaruh.
 */
function deleteKelas($id_kelas)
{
    global $conn;

    $query = "DELETE FROM kelas WHERE id_kelas = :id_kelas";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':id_kelas', $id_kelas);
    $stmt->execute();

    return $stmt->rowCount();
}

function getAllPelajaranFromKelas()
{
    global $conn;
    $stmt = $conn->query("SELECT DISTINCT pelajaran FROM kelas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPengajarFromKelas()
{
    global $conn;
    $stmt = $conn->query("SELECT DISTINCT pengguna.id_pengguna AS id_pengajar, pengguna.nama FROM kelas JOIN pengguna ON kelas.id_pengajar = pengguna.id_pengguna");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllTingkatFromKelas()
{
    global $conn;
    $stmt = $conn->query("SELECT DISTINCT tingkat FROM kelas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
