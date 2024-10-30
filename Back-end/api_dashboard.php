<?php
require_once 'config.php';

header('Content-Type: application/json');

$db = new database();
$koneksi = $db->koneksi;

// API to get event counts
$query = "
SELECT j.nama_kejadian AS kejadian, COUNT(*) AS count 
FROM kejadian k 
JOIN jenis_kejadian j ON k.id_jenis_kejadian = j.id_jenis_kejadian 
GROUP BY j.nama_kejadian
";

$result = mysqli_query($koneksi, $query);

if ($result) {
    $data = [];
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'kejadian' => $row['kejadian'],
            'count' => $row['count']
        ];
        $total += $row['count']; // Menghitung total
    }

    foreach ($data as &$item) {
        $item['percentage'] = $total > 0 ? ($item['count'] / $total) * 100 : 0; // Menghindari pembagian dengan nol
    }

    echo json_encode($data);
    
} else {
    echo json_encode(['error' => 'Data gagal diambil']);
}

// New API to get user and service unit statistics
$queryStats = "
SELECT
    (SELECT COUNT(*) FROM user WHERE role = 3) AS mahasiswa,
    (SELECT COUNT(*) FROM user WHERE role = 2 OR role = 4) AS dosen_tendik,
    (SELECT COUNT(*) FROM instansi) AS unit_layanan
";

$resultStats = mysqli_query($koneksi, $queryStats);

if ($resultStats) {
    $stats = mysqli_fetch_assoc($resultStats);
    echo json_encode($stats);
} else {
    echo json_encode(['error' => 'Data statistik gagal diambil']);
}
?>