<?php
include '../Back-end/config.php';
header('Content-Type: application/json');

$database = new database();
$input = json_decode(file_get_contents('php://input'), true);

date_default_timezone_set('Asia/Jakarta');

try {
    if (isset($input['id'])) {
        // Jika ada parameter ID, update status_notif
        $notifId = $input['id'];
        $query = "UPDATE kejadian SET status_notif = 1 WHERE id_kejadian = ?";
        $stmt = $database->koneksi->prepare($query);

        if ($stmt) {
            $stmt->bind_param('i', $notifId);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Status notifikasi diperbarui']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status notifikasi']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan query']);
        }
    } else {
        // Jika tidak ada parameter ID, kembalikan daftar notifikasi
        $data = mysqli_query($database->koneksi, "
            SELECT a.*, g.nama_kejadian, c.nama AS nama_user, d.nama_status_kehilangan, e.nama_status_pengaduan, f.nama_jenis_pengaduan, h.nama_instansi
            FROM kejadian a
            LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
            INNER JOIN user c ON c.id_user = a.id_user
            LEFT JOIN status_kehilangan d ON d.id_status_kehilangan = a.status_kehilangan
            LEFT JOIN status_pengaduan e ON e.id_status_pengaduan = a.status_pengaduan
            LEFT JOIN jenis_pengaduan f ON f.id_jenis_pengaduan = a.id_jenis_pengaduan
            LEFT JOIN instansi h ON h.id_instansi = a.id_instansi
            ORDER BY a.tanggal DESC
        ");

        $notifications = [];

        function timeAgo($timestamp) {
            $timeAgo = strtotime($timestamp); // Convert to UNIX timestamp
            $currentTime = time();
            $timeDifference = $currentTime - $timeAgo;

            if ($timeDifference < 60) {
                return $timeDifference . 'm ago'; // Minutes
            } elseif ($timeDifference < 3600) {
                return floor($timeDifference / 60) . 'm ago'; // Minutes
            } elseif ($timeDifference < 86400) {
                return floor($timeDifference / 3600) . 'h ago'; // Hours
            } else {
                return floor($timeDifference / 86400) . 'd ago'; // Days
            }
        }

        foreach ($data as $row) {
            $notifications[] = [
                'id' => (int)$row['id_kejadian'],
                'title' => $row['judul'],
                'category' => $row['nama_kejadian'],
                'time' => timeAgo($row['tanggal']),
                'tanggal' => $row['tanggal'],
                'description' => $row['deskripsi'],
                'rating' => (int)$row['skala_bintang'] ?? 0,
                'status_notif' => (int)$row['status_notif'],
                'nama_user' => $row['nama_user'] ?? 'Tidak ada nama pengguna',
                'location' => $row['lokasi'] ?? 'Tidak ada lokasi',
                'lampiran' => $row['lampiran'] ?? 'Tidak ada lampiran',
                'jenis_barang' => $row['jenis_barang'] ?? 'Tidak ada jenis barang',
                'status_kehilangan' => $row['nama_status_kehilangan'] ?? 'Tidak ada status kehilangan',
                'status_pengaduan' => $row['nama_status_pengaduan'] ?? 'Tidak ada status pengaduan',
                'jenis_pengaduan' => $row['nama_jenis_pengaduan'] ?? 'Tidak ada jenis pengaduan',
                'instansi' => $row['nama_instansi'] ?? 'Tidak ada instansi'
            ];
        }

        echo json_encode(['success' => true, 'data' => $notifications]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
