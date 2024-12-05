<?php
include 'config.php';
include '../api/phpqrcode/qrlib.php'; // Include library QR Code
require_once __DIR__ . '/../mpdf/vendor/autoload.php';

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

// Fungsi untuk membuat poster menggunakan MPDF
function generatePoster($unit_id, $nama_instansi, $qr_path) {
    $poster_dir = "../posters/";

    if (!is_dir($poster_dir)) {
        mkdir($poster_dir, 0755, true);
    }

    $poster_file = $poster_dir . "poster_unit_" . $unit_id . ".pdf";

    $html = '
    <html>
    <head>
        <style>
            body { font-family: sans-serif; text-align: center; }
            .poster { border: 1px solid #ccc; padding: 20px; }
            .qr-code { margin-top: 20px; }
            .title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
            .subtitle { font-size: 18px; margin-bottom: 10px; }
        </style>
    </head>
    <body>
        <div class="poster">
            <div class="title">Selamat Datang di ' . htmlspecialchars($nama_instansi) . '</div>
            <div class="subtitle">Scan QR Code untuk memberikan rating</div>
            <div class="qr-code">
                <img src="' . htmlspecialchars($qr_path) . '" alt="QR Code" width="200">
            </div>
        </div>
    </body>
    </html>
    ';

    $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
    $mpdf->WriteHTML($html);

    $poster_path = "../posters/poster_" . $unit_id . ".pdf";
    $mpdf->Output($poster_path, \Mpdf\Output\Destination::FILE);

    return $poster_path;
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

        // Generate poster dengan QR Code
        $poster_path = generatePoster($unit_id, $nama_instansi, $qr_path);

        $update_query = "UPDATE instansi SET qr_code_url = '$qr_path', poster_url = '$poster_path' WHERE id_instansi = '$unit_id'";
        if (mysqli_query($db->koneksi, $update_query)) {
            return ["success" => true, "message" => "Data unit layanan berhasil disimpan dan poster di-generate.", "poster_path" => $poster_path];
        } else {
            return ["success" => false, "message" => "Gagal memperbarui data di database."];
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
