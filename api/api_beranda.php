<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk menghitung jumlah data berdasarkan jenis kejadian
function countDataByJenisKejadian($db, $id_jenis_kejadian) {
    $query = "SELECT COUNT(*) as count FROM kejadian WHERE id_jenis_kejadian = $id_jenis_kejadian";
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
    // Mendapatkan jumlah data untuk setiap kategori
    $pengaduan = countDataByJenisKejadian($db, 2);
    $kehilangan = countDataByJenisKejadian($db, 1);
    $rating = countDataByJenisKejadian($db, 3);

    // Mengembalikan data dalam format JSON
    echo json_encode([
        "pengaduan" => $pengaduan,
        "kehilangan" => $kehilangan,
        "rating" => $rating
    ]);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
