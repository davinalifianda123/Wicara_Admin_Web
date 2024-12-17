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

    // Pastikan folder penyimpanan ada
    if (!is_dir($poster_dir)) {
        mkdir($poster_dir, 0755, true);
    }

    // Path file poster
    $poster_file = "poster_" . $nama_instansi . ".pdf";

    // Konten HTML untuk PDF
    $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @page {
                background-image: url("../assets/WadahInformasiCatatanAspirasi&RatingAkademikWICARA.png");
                background-image-resize: 6;
            }
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                color: #fff;
            }
            .poster {
                margin: 0 auto;
                text-align: center;
                width: 100%;
            }
            .title h1 {
                margin-top: 45px;
                font-size: 54px;
                margin-bottom: 5px;
            }
            .title h2 {
                font-size: 36px;
                margin: 0;
            }
            .qr-code {
                margin-top: 36px;
            }
            .instructions {
                margin-top: 36px;
            }
            .footer {
                color: #fff;
                padding: 10px;
                text-align: center;
                margin-top: 45px;
            }
            .footer p {
                margin: 5px;
            }
        </style>
    </head>
    <body>
        <div class="poster">
            <img width="180" src="../assets/Polines.png" alt="Logo Polines">
            <div class="title">
                <h1>' . htmlspecialchars($nama_instansi) . '</h1>
                <h2>SCAN HERE TO RATE</h2>
            </div>
            <img class="qr-code" width="320" src="' . htmlspecialchars($qr_path) . '" alt="QR Code">
            <img class="instructions" width="75%" src="../assets/instructions.png" alt="Instructions">
            <div class="footer">
                <p>Powered by</p>
                <img width="150" src="../assets/logo wicara.png" alt="Logo Wicara">
            </div>
        </div>
    </body>
    </html>
    ';

    // Inisialisasi mPDF dan buat file PDF
    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4',
        'margin_left' => 20,
        'margin_right' => 15,
        'margin_top' => 25,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]);
    $mpdf->WriteHTML($html);
    $mpdf->Output($poster_file, \Mpdf\Output\Destination::FILE);

    // Kembalikan path file PDF
    return $poster_file;
}


function saveUnitLayanan($db, $nama_instansi, $email_pic, $password, $image_instansi = null) {
    // Inisialisasi path gambar jika tidak ada gambar yang diunggah
    $image_path = null;

    // Proses upload gambar jika ada
    if ($image_instansi && isset($image_instansi['tmp_name']) && $image_instansi['tmp_name']) {
        $target_dir = '../../Wicara_User_Web/assets/images/instansi/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $target_file = basename($image_instansi['name']);
        
        if (move_uploaded_file($image_instansi['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            return ["success" => false, "message" => "Gagal mengupload gambar."];
        }
    }

    // Format nama instansi untuk namaQR
    $nama_instansi_formatted = strtolower(str_replace(' ', '', $nama_instansi));

    // Simpan data ke database, termasuk gambar jika ada
    $query = "INSERT INTO instansi (nama_instansi, email_pic, password, gambar_instansi) VALUES ('$nama_instansi', '$email_pic', '$password', " . ($image_path ? "'$image_path'" : "NULL") . ")";
    
    if (mysqli_query($db->koneksi, $query)) {
        // Ambil ID dari instansi yang baru disimpan
        $unit_id = mysqli_insert_id($db->koneksi);

        // Buat namaQR dengan ID instansi
        $namaQR = $unit_id . "-" . $nama_instansi_formatted . "-polines";

        // Generate QR Code dan simpan path-nya
        $qr_path = '../qrcodes/'.generateQRCode($unit_id);

        // Generate poster dengan QR Code
        $poster_path = generatePoster($unit_id, $nama_instansi, $qr_path);

        $update_query = "UPDATE instansi SET qr_code_url = '$qr_path', poster_url = '$poster_path', namaQR = '$namaQR' WHERE id_instansi = '$unit_id'";
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
