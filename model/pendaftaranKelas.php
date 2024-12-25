<?php

require __DIR__ . "/../db.php";

/**
 * Mengambil data pendaftaran kelas berdasarkan filter.
 * 
 * @param array $filter Opsi filter yang digunakan dalam query.
 * @return array Hasil query pendaftaran kelas sesuai filter yang diberikan.
 */
function getPendaftaranKelasByFilter($filter)
{
    global $conn;

    $query = "
        SELECT  
            pendaftaran_kelas.*, 
            kelas.nama_kelas, 
            kelas.tingkat, 
            kelas.pelajaran, 
            kelas.deskripsi, 
            pengguna.nama AS nama_pengguna,
            pengguna.email AS email_pengguna,
            pengajar.nama AS nama_pengajar,
            transaksi.id_transaksi,
            transaksi.status_transaksi,
            transaksi.harga
        FROM 
            pendaftaran_kelas        
        LEFT JOIN 
            kelas ON pendaftaran_kelas.id_kelas = kelas.id_kelas
        LEFT JOIN 
            pengguna ON pendaftaran_kelas.id_pengguna = pengguna.id_pengguna
        LEFT JOIN 
            pengguna pengajar ON kelas.id_pengajar = pengguna.id_pengguna
        LEFT JOIN 
            transaksi ON transaksi.id_pendaftaran = pendaftaran_kelas.id_pendaftaran
    ";


    $conditions = [];
    $params = [];

    if (isset($filter["id_pengguna"])) {
        $conditions[] = "pendaftaran_kelas.id_pengguna = :id_pengguna";
        $params["id_pengguna"] = $filter["id_pengguna"];
    }

    if (isset($filter["id_kelas"])) {
        $conditions[] = "kelas.id_kelas = :id_kelas";
        $params["id_kelas"] = $filter["id_kelas"];
    }

    if (isset($filter["id_transaksi"])) {
        $conditions[] = "transaksi.id_transaksi = :id_transaksi";
        $params["id_transaksi"] = $filter["id_transaksi"];
    }

    if (isset($filter["status"])) {
        $placeholders = implode(", ", array_map(function ($i) {
            return ":status_$i";
        }, array_keys($filter["status"])));

        $conditions[] = "pendaftaran_kelas.status IN ($placeholders)";

        foreach ($filter["status"] as $key => $status) {
            $params[":status_$key"] = $status;
        }
    }


    if ($conditions) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);

    $stmt->execute($params);


    return isset($filter["id_kelas"]) || isset($filter["status"]) ?
        $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Menambahkan pendaftaran kelas baru.
 * 
 * @param array $data Data untuk pendaftaran kelas.
 * @return int ID pendaftaran yang baru dibuat.
 */
function createPendaftaranKelas($data)
{
    global $conn;


    $stmt = $conn->prepare("
        INSERT INTO pendaftaran_kelas (id_pengguna, id_kelas) 
        VALUES (:id_pengguna, :id_kelas)
    ");
    $stmt->execute([
        ":id_pengguna" => $data["id_pengguna"],
        ":id_kelas" => $data["id_kelas"]
    ]);
    $idPendaftaran = $conn->lastInsertId();


    $stmt = $conn->prepare("
        INSERT INTO transaksi (id_voucher, id_pendaftaran, harga, Metode_Pembayaran) 
        VALUES (:id_voucher, :id_pendaftaran, :harga, :metode_pembayaran)
    ");
    $stmt->execute([
        ":id_voucher" => $data["id_voucher"],
        ":id_pendaftaran" => $idPendaftaran,
        ":harga" => $data["harga"],
        ":metode_pembayaran" => $data["metode_pembayaran"]
    ]);

    return $idPendaftaran;
}

/**
 * Memperbarui pendaftaran kelas yang ada.
 * 
 * @param array $data Data untuk diperbarui.
 * @return int Jumlah baris yang diperbarui.
 */
function updatePendaftaranKelas($data)
{
    global $conn;

    $setClause = [];
    foreach ($data as $key => $value) {
        if (!empty($value) && $key != 'id_pengguna' && $key != 'id_kelas' && $key != 'id_pendaftaran') {
            $setClause[] = "$key = :$key";
        }
    }

    if (!empty($setClause)) {
        $query = "UPDATE pendaftaran_kelas SET " . implode(", ", $setClause) . " WHERE id_pendaftaran = :id_pendaftaran";
        $stmt = $conn->prepare($query);
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    return 0;
}

function updateStatusPendaftaranKelas($id_kelas, $id_pengguna)
{
    global $conn;


    $quiz_sql = "SELECT q.id_quiz
                 FROM quiz q
                 JOIN materi m ON q.id_materi = m.id_materi
                 WHERE m.id_kelas = :id_kelas";

    $quiz_stmt = $conn->prepare($quiz_sql);
    $quiz_stmt->bindParam(':id_kelas', $id_kelas);
    $quiz_stmt->execute();


    $quiz_ids = [];
    while ($quiz_row = $quiz_stmt->fetch(PDO::FETCH_ASSOC)) {
        $quiz_ids[] = $quiz_row['id_quiz'];
    }


    $nilai_sql = "SELECT qa.id_quiz, qa.nilai_akhir
                  FROM quiz_attempts qa
                  WHERE qa.id_pengguna = :id_pengguna AND qa.id_quiz IN (" . implode(",", $quiz_ids) . ")";

    $nilai_stmt = $conn->prepare($nilai_sql);
    $nilai_stmt->bindParam(':id_pengguna', $id_pengguna);
    $nilai_stmt->execute();


    $nilai_diperoleh = [];
    while ($nilai_row = $nilai_stmt->fetch(PDO::FETCH_ASSOC)) {
        $nilai_diperoleh[$nilai_row['id_quiz']] = $nilai_row['nilai_akhir'];
    }


    $belum_ambil_quiz = false;
    foreach ($quiz_ids as $quiz_id) {
        if (!isset($nilai_diperoleh[$quiz_id])) {
            $belum_ambil_quiz = true;
            break;
        }
    }


    if ($belum_ambil_quiz) {
        return false;
    }


    $semua_di_atas_80 = true;
    foreach ($nilai_diperoleh as $nilai) {
        if ($nilai < 80) {
            $semua_di_atas_80 = false;
            break;
        }
    }

    if ($semua_di_atas_80) {

        $update_sql = "UPDATE pendaftaran_kelas 
                       SET status = 'Selesai' 
                       WHERE id_pengguna = :id_pengguna AND id_kelas = :id_kelas AND status = 'Dalam Proses'";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':id_pengguna', $id_pengguna);
        $update_stmt->bindParam(':id_kelas', $id_kelas);
        $update_stmt->execute();

        return true;
    } else {
        return false;
    }
}


/**
 * Menghapus pendaftaran kelas dan transaksi terkait.
 * 
 * @param array $data Data untuk penghapusan (id_pendaftaran).
 * @return int Jumlah baris yang dihapus.
 */
function deletePendaftaranKelas($data)
{
    global $conn;


    $stmt = $conn->prepare("DELETE FROM pendaftaran_kelas WHERE id_pendaftaran = :id_pendaftaran");
    $stmt->execute($data);


    $stmt = $conn->prepare("DELETE FROM transaksi WHERE id_pendaftaran = :id_pendaftaran");
    $stmt->execute($data);

    return $stmt->rowCount();
}
