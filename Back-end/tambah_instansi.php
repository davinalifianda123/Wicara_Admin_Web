<?php
include 'config.php';
include '../api/phpqrcode/qrlib.php'; // Include library QR Code

$db = new database();


// Fungsi untuk menghasilkan QR Code
function generateQRCode($unit_id) {
    // URL yang akan dimasukkan dalam QR Code
    $url = "https://9210-103-214-229-136.ngrok-free.app/Wicara_Admin_Web/Back-end/tambah_kejadian_ulasan.php?unit_id=" . $unit_id;
    $file_path = "../qrcodes/unit_" . $unit_id . ".png";
    
    // Membuat QR Code
    QRcode::png($url, $file_path, QR_ECLEVEL_L, 10);
    
    return $file_path;
}

function saveUnitLayanan($db, $nama_instansi, $email_pic, $password, $image_instansi = null) {
    // Inisialisasi path gambar jika tidak ada gambar yang diunggah
    $image_path = null;

    // Proses upload gambar jika ada
    if ($image_instansi && isset($image_instansi['tmp_name']) && $image_instansi['tmp_name']) {
        $target_dir = "./foto-instansi/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $target_file = $target_dir . basename($image_instansi['name']);
        
        if (move_uploaded_file($image_instansi['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            return ["success" => false, "message" => "Gagal mengupload gambar."];
        }
    }

    // Simpan data ke database, termasuk gambar jika ada
    $query = "INSERT INTO instansi (nama_instansi, email_pic, password, image_instansi) VALUES ('$nama_instansi', '$email_pic', '$password', " . ($image_path ? "'$image_path'" : "NULL") . ")";
    
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
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_instansi = $_POST['nama_instansi'];
    $email_pic = $_POST['email_pic'];
    $password = "Polines123*"; // Password default
    $image_instansi = isset($_FILES['image_instansi']) ? $_FILES['image_instansi'] : null;
    
    $response = saveUnitLayanan($db, $nama_instansi, $email_pic, $password, $image_instansi);
    echo json_encode($response);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

// Redirect ke halaman tampilan
header('Location: ../rating.php');
?>
