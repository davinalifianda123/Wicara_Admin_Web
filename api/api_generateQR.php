<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php'; // Menghubungkan ke database
include 'phpqrcode/qrlib.php'; // Memastikan library phpqrcode tersedia

$db = new database(); // Membuat instance database

// Fungsi untuk menghasilkan QR Code
function generateQRCode($unit_id) {
    // URL yang akan dimasukkan dalam QR Code
    $url = "https://9210-103-214-229-136.ngrok-free.app/Wicara_Admin_Web/Back-end/tambah_kejadian_ulasan.php?unit_id=" . $unit_id; // Gantilah dengan URL yang sesuai
    $file_path = "../qrcodes/unit_" . $unit_id . ".png"; // Tentukan lokasi penyimpanan QR Code
    
    // Membuat QR Code
    QRcode::png($url, $file_path, QR_ECLEVEL_L, 10);
    
    return $file_path; // Mengembalikan path ke file QR Code
}

// Menangani permintaan GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['unit_id'])) {
    $unit_id = $_GET['unit_id'];

    // Menghasilkan QR Code
    $qr_path = generateQRCode($unit_id);

    // Menyimpan path QR Code ke database
    $query = "UPDATE instansi SET qr_code_url = '$qr_path' WHERE id_instansi = '$unit_id'";
    if (mysqli_query($db->koneksi, $query)) {
        echo json_encode(["success" => true, "qr_path" => $qr_path]); // Mengembalikan path QR Code dalam format JSON
    } else {
        echo json_encode(["success" => false, "message" => "Gagal memperbarui QR Code di database."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Permintaan tidak valid atau unit_id tidak ditemukan."]);
}
?>
