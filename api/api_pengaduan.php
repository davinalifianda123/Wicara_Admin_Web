<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/x-www-form-urlencoded");

include '../Back-end/config.php';
$db = new database();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pengaduan = $db->tampil_data_pengaduan();
    echo json_encode($pengaduan);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKejadian = $_POST['id_kejadian'];
    $action = $_POST['action'];

    // Process action: terima, tolak, or delete
    if ($action === 'terima') {
        $statusPengaduan = 3; // ID status for "Diproses"
    } elseif ($action === 'tolak') {
        $statusPengaduan = 4; // ID status for "Ditolak"
    } elseif ($action === 'selesai') {
        $statusPengaduan = 5; // ID status for "Selesai"
    } elseif ($action === 'delete') {
        // Delete the data and its related image
        $query = "SELECT lampiran FROM kejadian WHERE id_kejadian = '$idKejadian'";
        $result = mysqli_query($db->koneksi, $query);
        $row = mysqli_fetch_assoc($result);
        
        // Delete the file from server
        if ($row) {
            $imagePath = "../Back-end/foto-pengaduan/".$row['lampiran'];
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image from the folder
            }
        }

        $query = "DELETE FROM kejadian WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        exit;
    }

    if (isset($statusPengaduan)) {
        $query = "UPDATE kejadian SET status_pengaduan = '$statusPengaduan' WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}
?>
