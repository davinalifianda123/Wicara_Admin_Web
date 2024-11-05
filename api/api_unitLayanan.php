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
    // Ambil jeda waktu rating dari tabel instansi
    $query_instansi = "SELECT jeda_waktu_rating FROM instansi WHERE id_instansi = '$unit_id'";
    $result_instansi = mysqli_query($db->koneksi, $query_instansi);
    $instansi = mysqli_fetch_assoc($result_instansi);

    if (!$instansi) {
        return ["success" => false, "message" => "Instansi tidak ditemukan"];
    }

    $jeda_waktu_rating = (int)$instansi['jeda_waktu_rating'] * 86400; // Konversi hari ke detik

    // Ambil tanggal rating terakhir user untuk unit ini dari tabel kejadian
    $query_kejadian = "SELECT tanggal FROM kejadian WHERE id_instansi = '$unit_id' AND id_user = '$user_id' ORDER BY tanggal DESC LIMIT 1";
    $result_kejadian = mysqli_query($db->koneksi, $query_kejadian);
    $last_rating = mysqli_fetch_assoc($result_kejadian);

    // Jika user belum pernah memberi rating, izinkan memberi rating
    if (!$last_rating) {
        return ["success" => true, "message" => "User dapat memberi rating"];
    }

    $last_rating_time = strtotime($last_rating['tanggal']);
    $current_time = time();

    // Periksa apakah jeda waktu telah berlalu
    if (($current_time - $last_rating_time) >= $jeda_waktu_rating) {
        return ["success" => true, "message" => "User dapat memberi rating lagi"];
    } else {
        $remaining_time = $jeda_waktu_rating - ($current_time - $last_rating_time);
        $remaining_days = ceil($remaining_time / 86400); // Hitung sisa waktu dalam hari
        return ["success" => false, "message" => "Tunggu $remaining_days hari lagi sebelum memberi rating"];
    }
}

// Menangani request GET dan POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Cek jika ingin memeriksa apakah user bisa memberi rating (misalnya, dengan menambahkan parameter 'checkRating')
    if (isset($_GET['checkRating']) && isset($_GET['unit_id']) && isset($_GET['user_id'])) {
        $unit_id = $_GET['unit_id'];
        $user_id = $_GET['user_id'];
        $response = canGiveRating($db, $unit_id, $user_id);
        echo json_encode($response);
    } else {
        // Mengambil data unit layanan
        $data = getUnitLayanan($db);
        echo json_encode($data);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendekode data JSON yang dikirim pada request POST
    $data = json_decode(file_get_contents("php://input"), true);

    // Mengatur jeda waktu rating
    if (isset($data['unit_id']) && isset($data['jeda_waktu'])) {
        $response = setJedaRating($db, $data['unit_id'], $data['jeda_waktu']);
        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Parameter tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
