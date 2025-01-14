<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/x-www-form-urlencoded");

include '../Back-end/config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $idInstansi = isset($_GET['id_instansi']) ? $_GET['id_instansi'] : null;

    // Panggil fungsi tampil_data_ulasan dengan id_instansi jika ada
    $ulasan = $db->tampil_data_ulasan_by_id($idInstansi);
    echo json_encode($ulasan);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $idKejadian = isset($_GET['id_kejadian']) ? $_GET['id_kejadian'] : null;
    $action = isset($_GET['action']) ? $_GET['action'] : null;

    if ($action === 'delete' && $idKejadian) {
        // Ambil nama file lampiran dari database
        $queryGetLampiran = "SELECT lampiran FROM kejadian WHERE id_kejadian = '$idKejadian'";
        $result = mysqli_query($db->koneksi, $queryGetLampiran);
        $row = mysqli_fetch_assoc($result);

        if ($row && !empty($row['lampiran'])) {
            $filePath = "../../Wicara_User_Web/backend/rating/" . $row['lampiran'];
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file dari folder
            }
        }

        // Hapus data dari database
        $query = "DELETE FROM kejadian WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);

        echo json_encode(['status' => 'success', 'message' => 'Data dan lampiran berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid atau id kejadian tidak ditemukan']);
    }
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}
?>
