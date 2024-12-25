<?php

require __DIR__ . "/../db.php";

/**
 * Mengambil informasi voucher berdasarkan kode.
 * 
 * @param array $data Data yang berisi kode voucher.
 * @return array Data voucher yang ditemukan, atau array kosong jika tidak ditemukan.
 */
function getVoucher($data)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM voucher WHERE kode_voucher = :kode");
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
