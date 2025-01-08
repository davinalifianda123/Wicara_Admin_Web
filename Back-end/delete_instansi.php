<?php
header("Content-Type: application/json");

include './config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve and delete image_instansi
    $query = "SELECT gambar_instansi FROM instansi WHERE id_instansi='$id'";
    $result = mysqli_query($db->koneksi, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $imagePath = '../../Wicara_User_Web/assets/images/instansi/'.$data['gambar_instansi'];
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Retrieve and delete qr_code_url
    $query2 = "SELECT qr_code_url FROM instansi WHERE id_instansi='$id'";
    $result2 = mysqli_query($db->koneksi, $query2);
    if ($result2 && mysqli_num_rows($result2) > 0) {
        $data2 = mysqli_fetch_assoc($result2);
        $imagePath2 = '../qrcodes/'.$data2['qr_code_url'];
        if ($imagePath2 && file_exists($imagePath2)) {
            unlink($imagePath2);
        }
    }

    // Retrieve and delete poster_url
    $query3 = "SELECT poster_url FROM instansi WHERE id_instansi='$id'";
    $result3 = mysqli_query($db->koneksi, $query3);
    if ($result3 && mysqli_num_rows($result3) > 0) {
        $data3 = mysqli_fetch_assoc($result3);
        $imagePath3 = '../posters/'.$data3['poster_url'];
        if ($imagePath3 && file_exists($imagePath3)) {
            unlink($imagePath3);
        }
    }

    // Delete database row
    $deleteQuery = "DELETE FROM instansi WHERE id_instansi='$id'";
    if (mysqli_query($db->koneksi, $deleteQuery)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete data"]);
    }
}
