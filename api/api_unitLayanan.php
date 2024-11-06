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

// Fungsi untuk mengatur jeda waktu rating
function setJedaRating($db, $unit_id, $jeda_waktu) {
    $query = "UPDATE instansi SET jeda_waktu_rating = '$jeda_waktu' WHERE id_instansi = '$unit_id'";
    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Jeda waktu rating berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui jeda waktu rating."];
    }
}

// Fungsi untuk memeriksa apakah user dapat memberi rating berdasarkan jeda waktu
function canGiveRating($db, $unit_id, $user_id) {
    $query_instansi = "SELECT jeda_waktu_rating FROM instansi WHERE id_instansi = '$unit_id'";
    $result_instansi = mysqli_query($db->koneksi, $query_instansi);
    $instansi = mysqli_fetch_assoc($result_instansi);

    if (!$instansi) {
        return ["success" => false, "message" => "Instansi tidak ditemukan"];
    }

    $jeda_waktu_rating = (int)$instansi['jeda_waktu_rating'] * 86400; // Konversi hari ke detik

    $query_kejadian = "SELECT tanggal FROM kejadian WHERE id_instansi = '$unit_id' AND id_user = '$user_id' ORDER BY tanggal DESC LIMIT 1";
    $result_kejadian = mysqli_query($db->koneksi, $query_kejadian);
    $last_rating = mysqli_fetch_assoc($result_kejadian);

    if (!$last_rating) {
        return ["success" => true, "message" => "User dapat memberi rating"];
    }

    $last_rating_time = strtotime($last_rating['tanggal']);
    $current_time = time();

    if (($current_time - $last_rating_time) >= $jeda_waktu_rating) {
        return ["success" => true, "message" => "User dapat memberi rating lagi"];
    } else {
        $remaining_time = $jeda_waktu_rating - ($current_time - $last_rating_time);
        $remaining_days = ceil($remaining_time / 86400); // Hitung sisa waktu dalam hari
        return ["success" => false, "message" => "Tunggu $remaining_days hari lagi sebelum memberi rating"];
    }
}

// Fungsi untuk mengedit data unit layanan
function editUnitLayanan($db, $unit_id, $nama_instansi, $email_pic) {
    $query = "UPDATE instansi SET nama_instansi = '$nama_instansi', email_pic = '$email_pic' WHERE id_instansi = '$unit_id'";
    if (mysqli_query($db->koneksi, $query)) {
        return ["success" => true, "message" => "Data unit layanan berhasil diperbarui."];
    } else {
        return ["success" => false, "message" => "Gagal memperbarui data unit layanan."];
    }
}

// Menangani request GET dan POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['checkRating']) && isset($_GET['unit_id']) && isset($_GET['user_id'])) {
        $unit_id = $_GET['unit_id'];
        $user_id = $_GET['user_id'];
        $response = canGiveRating($db, $unit_id, $user_id);
        echo json_encode($response);
    } else {
        $data = getUnitLayanan($db);
        echo json_encode($data);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['unit_id']) && isset($data['jeda_waktu'])) {
        $response = setJedaRating($db, $data['unit_id'], $data['jeda_waktu']);
        echo json_encode($response);

    } elseif (isset($data['unit_id']) && isset($data['nama_instansi']) && isset($data['email_pic'])) {
        // Mengedit data unit layanan
        $response = editUnitLayanan($db, $data['unit_id'], $data['nama_instansi'], $data['email_pic']);
        echo json_encode($response);

    } else {
        echo json_encode(["success" => false, "message" => "Parameter tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
