<?php
include 'config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKejadian = $_POST['id_kejadian'];
    $action = $_POST['action'];

    if ($action === 'terima') {
        $statusPengaduan = 3; // ID status untuk "Diproses"
    } elseif ($action === 'tolak') {
        $statusPengaduan = 4; // ID status untuk "Ditolak"
    } elseif ($action === 'delete') {
        $query = "DELETE FROM kejadian WHERE id_kejadian = '$idKejadian'";
        mysqli_query($db->koneksi, $query);
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        exit;
    } elseif ($action === 'selesai') {
        $statusPengaduan = 5; // ID status untuk "Selesai"
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

// Redirect dinamis ke halaman asal
if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect_url = $_SERVER['HTTP_REFERER']; // URL halaman asal
    header("Location: $redirect_url");
}
exit;
?>
