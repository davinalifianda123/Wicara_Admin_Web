<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk mengedit data profil
function editProfile($db, $id_user, $nama, $email, $phone, $password) {
    // Sanitasi input untuk mencegah SQL Injection
    $nama = mysqli_real_escape_string($db->koneksi, $nama);
    $email = mysqli_real_escape_string($db->koneksi, $email);
    $password = mysqli_real_escape_string($db->koneksi, $password);

    if (!empty($password)) {
        $query = "UPDATE user SET nama = '$nama', email = '$email' password = '$password' WHERE id_user = '$id_user'";
    } else {
        $query = "UPDATE user SET nama = '$nama', email = '$email' WHERE id_user = '$id_user'";
    }

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Profil berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui profil."];
    }
}

// Mendekode data JSON yang dikirim pada request POST
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['id_user']) && isset($data['nama']) && isset($data['email'])) {
        $id_user = $data['id_user'];
        $nama = $data['nama'];
        $email = $data['email'];
        $password = isset($data['password']) ? $data['password'] : '';

        $response = editProfile($db, $id_user, $nama, $email, $phone, $password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Parameter tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
