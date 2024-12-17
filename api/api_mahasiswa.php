<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk mengambil data mahasiswa
function getMahasiswa($db) {
    $query = "SELECT * FROM user WHERE role = '3'";
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

// Fungsi untuk mereset password mahasiswa
function resetPassword($db, $id_mahasiswa, $default_password) {
    $query = "UPDATE user SET password = '$default_password' WHERE id_user = '$id_mahasiswa'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Password berhasil direset."];
    } else {
        return ["success" => false, "message" => "Gagal mereset password."];
    }
}

function deleteAccount($db, $id_mahasiswa) {
    // Pastikan id_dosen valid
    $query = "DELETE FROM user WHERE id_user = '$id_mahasiswa'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Akun berhasil dihapus."];
    } else {
        return ["success" => false, "message" => "Gagal menghapus akun."];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dosen = getMahasiswa($db);
    echo json_encode($dosen);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id_user']) && isset($data['action']) && $data['action'] == 'reset_password') {
        $id_mahasiswa = $data['id_user'];
        $default_password = "Polines123*"; // Password default
        $response = resetPassword($db, $id_mahasiswa, $default_password);
        echo json_encode($response);
    } elseif (isset($data['id_user']) && isset($data['action']) && $data['action'] == 'delete_account') {
        $id_mahasiswa = $data['id_user'];
        $response = deleteAccount($db, $id_mahasiswa);
        echo json_encode($response);
    } if (isset($data['nama']) && isset($data['nim']) && isset($data['no_telepon']) && isset($data['email'])) {
        $nama = $data['nama'];
        $nim = $data['nim'];
        $no_telepon = $data['no_telepon'];
        $email = $data['email'];
        $password = "Polines123*";

        $query = "INSERT INTO user (nama, nomor_induk, nomor_telepon, email, password, role) VALUES ('$nama', '$nim', '$no_telepon', '$email', '$password', '3')";
        if (mysqli_query($db->koneksi, $query)) {
            echo json_encode(["success" => true, "message" => "Mahasiswa berhasil ditambahkan."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menambahkan mahasiswa."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ID mahasiswa tidak ditemukan."]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
