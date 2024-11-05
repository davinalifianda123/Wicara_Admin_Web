<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk mengambil data dosen
function getDosen($db) {
    $query = "SELECT * FROM user WHERE role = '2'";
    $result = mysqli_query($db->koneksi, $query);
    if ($result) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return [];
    }
}

// Fungsi untuk mereset password dosen
function resetPassword($db, $id_dosen, $default_password) {
    $query = "UPDATE user SET password = '$default_password' WHERE id_user = '$id_dosen'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Password berhasil direset."];
    } else {
        return ["success" => false, "message" => "Gagal mereset password."];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dosen = getDosen($db);
    echo json_encode($dosen);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id_user'])) {
        $id_dosen = $data['id_user'];
        $default_password = "Polines123*"; // Password default
        $response = resetPassword($db, $id_dosen, $default_password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "ID dosen tidak ditemukan."]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
