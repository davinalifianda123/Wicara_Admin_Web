<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../Back-end/config.php';
$db = new database();

// Fungsi untuk mengambil data mahasiswa
function getJenisPengaduan($db) {
    $query = "SELECT * FROM jenis_pengaduan";
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

function editJenisPengaduan($db, $id_jenis_pengaduan, $nama_jenis_pengaduan) {
    $query = "UPDATE jenis_pengaduan SET nama_jenis_pengaduan = '$nama_jenis_pengaduan' WHERE id_jenis_pengaduan = '$id_jenis_pengaduan'";
    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Data jenis pengaduan berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui data jenis pengaduan."];
    }
}

// Fungsi untuk menghapus dosen
function deleteData($db, $id_jenis_pengaduan) {
    // Pastikan id_dosen valid
    $query = "DELETE FROM jenis_pengaduan WHERE id_jenis_pengaduan = '$id_jenis_pengaduan'";

    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "jenis Pengaduan berhasil dihapus."];
    } else {
        return ["success" => false, "message" => "Gagal menghapus jenis pengaduan."];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $jenisPengaduan = getJenisPengaduan($db);
    echo json_encode($jenisPengaduan);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id_jenis_pengaduan']) && isset($data['nama_jenis_pengaduan']) && isset($data['action']) && $data['action'] == 'edit') {
        $result = editJenisPengaduan($db, $data['id_jenis_pengaduan'], $data['nama_jenis_pengaduan']);
        echo json_encode($result);
    } elseif (isset($data['id_jenis_pengaduan']) && isset($data['action']) && $data['action'] == 'delete') {
        // Menghapus data unit layanan
        $response = deleteData($db, $data['id_jenis_pengaduan']);
        echo json_encode($response);
    } elseif (isset($data['nama_jenis_pengaduan']) && isset($data['action']) && $data['action'] == 'add') {
        $query = "INSERT INTO jenis_pengaduan (nama_jenis_pengaduan) VALUES ('" . $data['nama_jenis_pengaduan'] . "')";
        if (mysqli_query($db->koneksi, $query)) {
            echo json_encode(["success" => true, "message" => "Jenis Pengaduan berhasil ditambahkan."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menambahkan jenis pengaduan."]);
        }
    } else {
        echo json_encode(["error" => "Invalid request"]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
