<?php

$GLOBALS["roles"] = [
    1 => "Admin",
    2 => "Pengajar",
    3 => "Siswa/Mahasiswa"
];

$GLOBALS['tingkat'] = [
    'TK A',
    'TK B',
    'SD Kelas 1',
    'SD Kelas 2',
    'SD Kelas 3',
    'SD Kelas 4',
    'SD Kelas 5',
    'SD Kelas 6',
    'SMP Kelas 7',
    'SMP Kelas 8',
    'SMP Kelas 9',
    'SMA Kelas 10',
    'SMA Kelas 11',
    'SMA Kelas 12',
    'Sarjana',
    'Magister',
    'Doktor',
];

$GLOBALS['metode_pembayaran'] = [
    1 => 'Transfer Bank',
    2 => 'Kartu Kredit',
    3 => 'E-Wallet',
    4 => 'Virtual Account',
    6 => 'QRIS',
];

$GLOBALS["kata_terlarang"] = ["bodoh", "jawa", "nigga", "pala bapak kau"];

$GLOBALS["status_transaksi"] = [
    0 => ["Menuggu Pembayaran", "bg-yellow-100 text-yellow-700"],
    1 => ["Selesai", "bg-green-100 text-green-700"],
    2 => ["Dibatalkan", "bg-red-100 text-red-700"],
];
