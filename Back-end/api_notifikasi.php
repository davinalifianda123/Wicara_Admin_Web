<?php
// api notifikasi gagal//

// Database connection
require 'config.php';

function cekPengaduanBaru($lastCheckTimestamp) {
    global $conn;
    $query = "SELECT COUNT(*) AS total FROM kejadian WHERE tanggal > '$lastCheckTimestamp'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
}

function getPengaduanTerbaru($lastCheckTimestamp) {
    global $conn;
    $query = "SELECT id_kejadian FROM kejadian WHERE waktu_terbit > '$lastCheckTimestamp' ORDER BY waktu_terbit DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    return $data['id_kejadian'] ?? null;
}

$lastCheckTimestamp = $_GET['lastCheckTimestamp'] ?? date('Y-m-d H:i:s', strtotime('-1 day'));
$response = cekPengaduanBaru($lastCheckTimestamp);
$idPengaduanTerbaru = getPengaduanTerbaru($lastCheckTimestamp);

echo json_encode([
'newPengaduan' => !is_null($idPengaduanTerbaru),
'idPengaduanTerbaru' => $idPengaduanTerbaru
]);