<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

function editInstansiProfile($db, $id_instansi, $nama_instansi, $email_pic, $password) {
    $nama_instansi = mysqli_real_escape_string($db->koneksi, $nama_instansi);
    $email_pic = mysqli_real_escape_string($db->koneksi, $email_pic);
    $passwordQuery = "";

    // Jika password tidak kosong, tambahkan query update password
    if (!empty($password)) {
        $passwordQuery = ", password = '$password'";
    }

    // Query update untuk tabel instansi
    $query = "UPDATE instansi SET nama_instansi = '$nama_instansi', email_pic = '$email_pic' $passwordQuery WHERE id_instansi = '$id_instansi'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Profil instansi berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui profil instansi.", "error" => mysqli_error($db->koneksi)];
    }
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($data['id_instansi']) && isset($data['nama_instansi']) && isset($data['email_pic'])) {
        $id_instansi = $data['id_instansi'];
        $nama_instansi = $data['nama_instansi'];
        $email_pic = $data['email_pic'];
        $password = isset($data['password']) ? $data['password'] : '';

        // Panggil fungsi edit profil instansi
        $response = editInstansiProfile($db, $id_instansi, $nama_instansi, $email_pic, $password);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Parameter tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
