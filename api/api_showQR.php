<?php
if (isset($_GET['unit_id'])) {
    $unit_id = $_GET['unit_id'];
    $file_path = "../qrcodes/unit_" . $unit_id . ".png";

    if (file_exists($file_path)) {
        header('Content-Type: image/png');
        readfile($file_path);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "QR Code tidak ditemukan."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Parameter unit_id tidak tersedia."]);
}
