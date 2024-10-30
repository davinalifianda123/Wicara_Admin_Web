<?php
include 'config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKejadian = $_POST['id_kejadian'];
    $action = $_POST['action'];

    if ($action === 'terima') {
        $statusKehilangan = 1; // ID status untuk "Belum Ditemukan"
    } elseif ($action === 'delete') {
        $query = "DELETE FROM kejadian WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        exit;
    }

    if (isset($statusKehilangan)) {
        $query = "UPDATE kejadian SET status_kehilangan = '$statusKehilangan' WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}
?>
