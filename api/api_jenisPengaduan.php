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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $jenisPengaduan = getJenisPengaduan($db);
    echo json_encode($jenisPengaduan);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id_jenis_pengaduan']) && isset($data['nama_jenis_pengaduan'])) {
        $result = editJenisPengaduan($db, $data['id_jenis_pengaduan'], $data['nama_jenis_pengaduan']);
        echo json_encode($result);
    } else {
        echo json_encode(["success" => false, "message" => "ID jenis pengaduan tidak ditemukan."]);
    }

} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
