<?php
include "../Back-end/config.php"; // Sesuaikan dengan file koneksi Anda
$db = new database();


header('Content-Type: application/json');

// Query untuk mengambil semua data instansi
$query = "SELECT * FROM instansi";
$results = mysqli_query($db->koneksi, $query);

// Array untuk menyimpan hasil
$data = [];

while ($x = mysqli_fetch_assoc($results)) {
    $id_instansi = $x['id_instansi'];

    // Query untuk jumlah review dan total rating
    $review_count_query = "SELECT COUNT(*) AS total_reviews FROM kejadian WHERE id_instansi = '$id_instansi' AND skala_bintang IS NOT NULL";
    $review_count_result = mysqli_query($db->koneksi, $review_count_query);
    $review_count = mysqli_fetch_assoc($review_count_result)['total_reviews'];

    $total_rating_query = "SELECT SUM(skala_bintang) AS total_rating FROM kejadian WHERE id_instansi = '$id_instansi' AND skala_bintang IS NOT NULL";
    $total_rating_result = mysqli_query($db->koneksi, $total_rating_query);
    $total_rating = mysqli_fetch_assoc($total_rating_result)['total_rating'];

    $rata_review = $review_count > 0 ? round($total_rating / $review_count, 2) : 0;
    
    // Tambahkan data instansi ke array items
    $data[] = [
        'id_instansi' => $x['id_instansi'],
        'nama_instansi' => $x['nama_instansi'],
        'email_pic' => $x['email_pic'] ?? '-',
        'image_instansi' => $x['gambar_instansi'] != null ? "../Wicara_User_Web/assets/images/instansi/".$x['gambar_instansi'] : 'assets/laptop.jpg',
        'qr_code_url' => $x['qr_code_url'],
        'average_rating' => $rata_review,
        'review_count' => $review_count,
    ];
}

// Kembalikan hasil sebagai JSON
echo json_encode($data);
?>
