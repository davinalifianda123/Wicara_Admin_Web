<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

function editProfile($db, $id_user, $nama, $email, $password) {
    $nama = mysqli_real_escape_string($db->koneksi, $nama);
    $email = mysqli_real_escape_string($db->koneksi, $email);
    $passwordQuery = "";

    if (!empty($password)) { // Hash password
        $passwordQuery = ", password = '$password'";
    }

    $query = "UPDATE user SET nama = '$nama', email = '$email' $passwordQuery WHERE id_user = '$id_user'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Profil berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui profil.", "error" => mysqli_error($db->koneksi)];
    }
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['id_user']) && isset($data['nama']) && isset($data['email'])) {
        $id_user = $data['id_user'];
        $nama = $data['nama'];
        $email = $data['email'];
        $password = isset($data['password']) ? $data['password'] : '';

        $response = editProfile($db, $id_user, $nama, $email, $password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Parameter tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
