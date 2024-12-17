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

// Fungsi untuk menghapus dosen
function deleteAccount($db, $id_dosen) {
    // Pastikan id_dosen valid
    $query = "DELETE FROM user WHERE id_user = '$id_dosen'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Akun berhasil dihapus."];
    } else {
        return ["success" => false, "message" => "Gagal menghapus akun."];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dosen = getDosen($db);
    echo json_encode($dosen);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);

    // Proses reset password
    if (isset($data['id_user']) && isset($data['action']) && $data['action'] == 'reset_password') {
        $id_dosen = $data['id_user'];
        $default_password = "Polines123*"; // Password default
        $response = resetPassword($db, $id_dosen, $default_password);
        echo json_encode($response);

    // Proses hapus akun
    } elseif (isset($data['id_user']) && isset($data['action']) && $data['action'] == 'delete_account') {
        $id_dosen = $data['id_user'];
        $response = deleteAccount($db, $id_dosen);
        echo json_encode($response);

    } if (isset($data['nama']) && isset($data['nim']) && isset($data['no_telepon']) && isset($data['email'])) {
        $nama = $data['nama'];
        $nim = $data['nim'];
        $no_telepon = $data['no_telepon'];
        $email = $data['email'];
        $password = "Polines123*";

        $query = "INSERT INTO user (nama, nomor_induk, nomor_telepon, email, password, role) VALUES ('$nama', '$nim', '$no_telepon', '$email', '$password', '2')";
        if (mysqli_query($db->koneksi, $query)) {
            echo json_encode(["success" => true, "message" => "Dosen berhasil ditambahkan."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menambahkan dosen."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ID mahasiswa tidak ditemukan."]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
