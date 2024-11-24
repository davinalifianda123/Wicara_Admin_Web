<?php
include '../Back-end/config.php';
header('Content-Type: application/json');

$database = new database();

try {
    $data = mysqli_query($database->koneksi, "
        SELECT a.*, g.nama_kejadian, c.nama AS nama_user
        FROM kejadian a
        LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
        INNER JOIN user c ON c.id_user = a.id_user
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
            'id' => $row['id_kejadian'], // Ganti dengan kolom primary key dari tabel kejadian
            'title' => $row['judul'], // Ganti 'judul' sesuai dengan kolom yang sesuai untuk judul
            'category' => $row['nama_kejadian'], // Sesuaikan dengan kolom kategori
            'time' => timeAgo($row['tanggal']), // Ganti 'waktu' dengan kolom yang berisi waktu
            'description' => $row['deskripsi'], // Ganti 'deskripsi' dengan kolom deskripsi
            'rating' => $row['skala_bintang'] ?? 0,
            'status_notif' => $row['status_notif'],
            'nama_user' => $row['nama_user'] ?? 'Tidak ada nama pengguna', // Gunakan kolom 'rating' jika ada
        ];
    }

    echo json_encode(['success' => true, 'data' => $notifications]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
