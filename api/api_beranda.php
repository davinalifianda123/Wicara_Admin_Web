<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk menghitung jumlah data berdasarkan filter tertentu
function countFilteredData($db, $id_jenis_kejadian, $status_column = null, $status_value = null) {
    $query = "SELECT COUNT(*) as count FROM kejadian WHERE id_jenis_kejadian = $id_jenis_kejadian";
    if ($status_column !== null && $status_value !== null) {
        $query .= " AND $status_column = $status_value";
    }
    $result = mysqli_query($db->koneksi, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        return isset($data['count']) ? (int) $data['count'] : 0; // Jika tidak ada data, kembali 0
    } else {
        // Jika query gagal, kembalikan 0 atau gunakan error handling lainnya
        return 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mendapatkan jumlah data dengan filter untuk setiap kategori
    $pengaduan = countFilteredData($db, 2, 'status_pengaduan', 1);
    $pengaduanInstansi = countFilteredData($db, 2, 'status_pengaduan', 3);
    $kehilangan = countFilteredData($db, 1, 'status_kehilangan', 4);
    $rating = countFilteredData($db, 3);

    // Mengembalikan data dalam format JSON
    echo json_encode([
        "pengaduan" => $pengaduan,
        "pengaduanInstansi" => $pengaduanInstansi,
        "kehilangan" => $kehilangan,
        "rating" => $rating
    ]);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
