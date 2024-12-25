<?php

require __DIR__ . "/../db.php";

function getTransaksi($id_transaksi)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM transaksi WHERE id_transaksi = :id_transaksi");
    $stmt->bindParam(':id_transaksi', $id_transaksi);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateTransaksi($data)
{
    global $conn;

    $setParts = [];
    foreach ($data as $key => $value) {
        if ($key != "id_transaksi") {
            $setParts[] = "$key = :$key";
        }
    }
    $setClause = implode(", ", $setParts);

    $query = "UPDATE transaksi SET $setClause WHERE id_transaksi = :id_transaksi";

    $stmt = $conn->prepare($query);

    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();

    return $stmt->rowCount();
}
