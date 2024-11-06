<?php
include 'config.php';
include '../api/phpqrcode/qrlib.php'; // Include library QR Code

$db = new database();

// Fungsi untuk menghasilkan QR Code
function generateQRCode($unit_id) {
    // URL yang akan dimasukkan dalam QR Code
    $url = "https://6977-103-214-229-137.ngrok-free.app/Wicara_Admin_Web/Back-end/tambah_kejadian_ulasan.php?unit_id=" . $unit_id;
    $file_path = "../qrcodes/unit_" . $unit_id . ".png";
    
    // Membuat QR Code
    QRcode::png($url, $file_path, QR_ECLEVEL_L, 10);
    
    return $file_path;
}

function saveUnitLayanan($db, $nama_instansi, $email_pic, $jeda_waktu_rating, $image_instansi) {
    // Proses upload gambar
    $target_dir = "./foto-instansi/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($image_instansi['name']);
    
    if (move_uploaded_file($image_instansi['tmp_name'], $target_file)) {
        $image_path = $target_dir . basename($image_instansi['name']);
        // Simpan data ke database tanpa QR Code terlebih dahulu
        $query = "INSERT INTO instansi (nama_instansi, email_pic, jeda_waktu_rating, image_instansi) VALUES ('$nama_instansi', '$email_pic', '$jeda_waktu_rating', '$image_path')";
        if (mysqli_query($db->koneksi, $query)) {
            // Ambil ID dari instansi yang baru disimpan
            $unit_id = mysqli_insert_id($db->koneksi);
            // Generate QR Code dan simpan path-nya
            $qr_path = generateQRCode($unit_id);
            $update_query = "UPDATE instansi SET qr_code_url = '$qr_path' WHERE id_instansi = '$unit_id'";
            if (mysqli_query($db->koneksi, $update_query)) {
                return ["success" => true, "message" => "Data unit layanan berhasil disimpan dan QR Code di-generate.", "qr_path" => $qr_path];
            } else {
                return ["success" => false, "message" => "Gagal memperbarui QR Code di database."];
            }
        } else {
            return ["success" => false, "message" => "Gagal menyimpan data unit layanan."];
        }
    } else {
        return ["success" => false, "message" => "Gagal mengupload gambar."];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_instansi'])) {
    $nama_instansi = $_POST['nama_instansi'];
    $email_pic = $_POST['email_pic'];
    $jeda_waktu_rating = $_POST['jeda_waktu_rating'];
    $image_instansi = $_FILES['image_instansi'];
    
    $response = saveUnitLayanan($db, $nama_instansi, $email_pic, $jeda_waktu_rating, $image_instansi);
    echo json_encode($response);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method or missing image"]);
}

// Redirect ke halaman tampilan
header('Location: ../unit_layanan.php');
