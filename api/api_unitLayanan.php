<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk mengambil data unit layanan
function getUnitLayanan($db) {
    $query = "SELECT * FROM instansi";
    $result = mysqli_query($db->koneksi, $query);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Fungsi untuk memperbarui data unit layanan (nama_instansi, email_pic, dan password)
function updateUnitLayanan($db, $unit_id, $nama_instansi = null, $email_pic = null, $password = null) {
    $fields = [];
    
    // Tambahkan kolom yang ingin diperbarui sesuai data yang diberikan
    if ($nama_instansi !== null) {
        $fields[] = "nama_instansi = '$nama_instansi'";
    }
    if ($email_pic !== null) {
        $fields[] = "email_pic = '$email_pic'";
    }
    if ($password !== null) {
        $fields[] = "password = '$password'";
    }

    if (empty($fields)) {
        return ["success" => false, "message" => "Tidak ada data untuk diperbarui."];
    }

    $query = "UPDATE instansi SET " . implode(", ", $fields) . " WHERE id_instansi = '$unit_id'";
    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Data unit layanan berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui data unit layanan."];
    }
}

// Fungsi untuk menghapus dosen
function deleteData($db, $unit_id) {
    // Pastikan id_dosen valid
    $query = "DELETE FROM instansi WHERE id_instansi = '$unit_id'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Instansi berhasil dihapus."];
    } else {
        return ["success" => false, "message" => "Gagal menghapus Instansi."];
    }
}

// Menangani request GET dan POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = getUnitLayanan($db);
    echo json_encode($data);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['unit_id']) && isset($data['action']) && $data['action'] == 'edit') {
        // Memperbarui data unit layanan
        $response = updateUnitLayanan(
            $db,
            $data['unit_id'],
            $data['nama_instansi'] ?? null,
            $data['email_pic'] ?? null,
            $data['password'] ?? null
        );
        echo json_encode($response);

    } elseif (isset($data['unit_id']) && isset($data['action']) && $data['action'] == 'delete') {
        // Menghapus data unit layanan
        $response = deleteData($db, $data['unit_id']);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
