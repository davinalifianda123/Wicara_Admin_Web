<?php
include './Back-end/config.php';
$db = new database();

date_default_timezone_set('Asia/Jakarta');

// Ambil id_instansi dari session
session_start(); // Tambahkan ini jika belum ada session_start di file ini
$id_instansi = isset($_SESSION['id_instansi']) ? $_SESSION['id_instansi'] : null;

// Jika id_instansi tidak ada, keluar dari script
if (!$id_instansi) {
    die('Session id_instansi tidak ditemukan.');
}

// Fungsi untuk menghitung waktu relatif
function timeAgo($timestamp) {
    $timeAgo = strtotime($timestamp); // Convert to UNIX timestamp
    if (!$timeAgo) {
        return "Invalid date"; // Tampilkan pesan jika format waktu salah
    }

    $currentTime = time();
    $timeDifference = $currentTime - $timeAgo;

    if ($timeDifference < 60) {
        return floor($timeDifference) . 's ago'; // Seconds
    } elseif ($timeDifference < 3600) {
        return floor($timeDifference / 60) . 'm ago'; // Minutes
    } elseif ($timeDifference < 86400) {
        return floor($timeDifference / 3600) . 'h ago'; // Hours
    } else {
        return floor($timeDifference / 86400) . 'd ago'; // Days
    }
}

// Query untuk notifikasi rating
$ratingQuery = "
    SELECT 
        kejadian.id_kejadian, 
        kejadian.tanggal, 
        kejadian.skala_bintang, 
        instansi.nama_instansi, 
        kejadian.flag_notifikasi AS status_notif,
        user.profile AS user_image
    FROM kejadian
    JOIN instansi ON kejadian.id_instansi = instansi.id_instansi
    JOIN user ON kejadian.id_user = user.id_user
    WHERE kejadian.id_jenis_kejadian = 3 AND kejadian.id_instansi = '$id_instansi'
";
$ratingResult = mysqli_query($db->koneksi, $ratingQuery);

$ratingNotifications = [];
while ($row = mysqli_fetch_assoc($ratingResult)) {
    $ratingNotifications[] = [
        'type' => 'rating',
        'title' => $row['nama_instansi'], // Gunakan nama instansi sebagai judul
        'time' => timeAgo($row['tanggal']), // Hitung waktu relatif
        'raw_time' => $row['tanggal'], // Kirim waktu asli untuk sorting
        'id' => $row['id_kejadian'],
        'rating' => (int)$row['skala_bintang'], // Ambil nilai bintang sebagai integer
        'image' => $row['user_image'] ? 'Wicara_Admin_Web/' . $row['user_image'] : 'assets/user.png', // Tambahkan gambar pengguna
        'status_notif' => $row['status_notif'], // Tambahkan status_notif
    ];
}

// Query untuk notifikasi pengaduan
$pengaduanQuery = "
    SELECT 
        kejadian.id_kejadian, 
        kejadian.judul, 
        kejadian.tanggal, 
        user.profile AS user_image,
        kejadian.flag_notifikasi AS status_notif
    FROM kejadian
    JOIN user ON kejadian.id_user = user.id_user
    WHERE kejadian.id_jenis_kejadian = 2 AND kejadian.id_instansi = '$id_instansi' AND kejadian.status_pengaduan = 3
";
$pengaduanResult = mysqli_query($db->koneksi, $pengaduanQuery);

$pengaduanNotifications = [];
while ($row = mysqli_fetch_assoc($pengaduanResult)) {
    $pengaduanNotifications[] = [
        'type' => 'pengaduan',
        'title' => $row['judul'],
        'time' => timeAgo($row['tanggal']), // Hitung waktu relatif
        'raw_time' => $row['tanggal'], // Kirim waktu asli untuk sorting
        'id' => $row['id_kejadian'],
        'image' => $row['user_image'] ? 'Wicara_Admin_Web/' . $row['user_image'] : 'assets/user.png', // Tambahkan gambar pengguna
        'status_notif' => $row['status_notif'], // Tambahkan status_notif
    ];
}

// Gabungkan semua notifikasi
$notifications = array_merge($pengaduanNotifications, $ratingNotifications);

// Encode data notifikasi ke dalam JSON agar dapat digunakan oleh JavaScript
$notificationsJSON = json_encode($notifications, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
