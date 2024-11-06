<?php

$conn = mysqli_connect("localhost", "root", "", "wicara");

$tanggal_hari_ini = strtotime(date("Y-m-d"));

$pengaduan_line = "
    SELECT DATE(tanggal) AS hari_p, COUNT(*) AS jumlah_pengaduan
    FROM kejadian
    WHERE id_jenis_kejadian = '2'
    AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY hari_p
    ORDER BY hari_p DESC
";
$hasil_pengaduanline = $conn->query($pengaduan_line);

$pengaduan_harian = array_fill(0, 7, 0);

// Memeriksa hasil query dan menampilkannya
if ($hasil_pengaduanline->num_rows > 0) {
    while ($row = $hasil_pengaduanline->fetch_assoc()) { // Hapus tanda titik koma setelah while
        if ($row['hari_p'] !== null) {
            // Hitung selisih hari dari hari ini ke tanggal pengaduan
            $selisih_hari = (int)(($tanggal_hari_ini - strtotime($row['hari_p'])) / (60 * 60 * 24));
            
            // Pastikan selisih hari berada dalam rentang 0 hingga 6 (7 hari terakhir)
            if ($selisih_hari >= 0 && $selisih_hari < 7) {
                $pengaduan_harian[6 - $selisih_hari] = $row['jumlah_pengaduan'];
            }
        }
    }
}

$kehilangan_line = "
    SELECT DATE(tanggal) AS hari_k, COUNT(*) AS jumlah_kehilangan
    FROM kejadian
    WHERE id_jenis_kejadian = '1'
    AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY hari_k
    ORDER BY hari_k DESC
";
$hasil_kehilanganline = $conn->query($kehilangan_line);

$kehilangan_harian = array_fill(0, 7, 0);

// Memeriksa hasil query dan menampilkannya
if ($hasil_kehilanganline->num_rows > 0) {
    while ($row = $hasil_kehilanganline->fetch_assoc()) { // Hapus tanda titik koma setelah while
        if ($row['hari_k'] !== null) {
            // Hitung selisih hari dari hari ini ke tanggal pengaduan
            $selisih_hari_k = (int)(($tanggal_hari_ini - strtotime($row['hari_k'])) / (60 * 60 * 24));
            
            // Pastikan selisih hari berada dalam rentang 0 hingga 6 (7 hari terakhir)
            if ($selisih_hari_k >= 0 && $selisih_hari_k < 7) {
                $kehilangan_harian[6 - $selisih_hari_k] = $row['jumlah_kehilangan'];
            }
        }
    }
}

$rating_line = "
    SELECT DATE(tanggal) AS hari_r, COUNT(*) AS jumlah_rating
    FROM kejadian
    WHERE id_jenis_kejadian = '3'
    AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY hari_r
    ORDER BY hari_r DESC
";
$hasil_ratingline = $conn->query($rating_line);

$rating_harian = array_fill(0, 7, 0);

// Memeriksa hasil query dan menampilkannya
if ($hasil_ratingline->num_rows > 0) {
    while ($row = $hasil_ratingline->fetch_assoc()) { // Hapus tanda titik koma setelah while
        if ($row['hari_r'] !== null) {
            // Hitung selisih hari dari hari ini ke tanggal pengaduan
            $selisih_hari_r = (int)(($tanggal_hari_ini - strtotime($row['hari_r'])) / (60 * 60 * 24));
            
            // Pastikan selisih hari berada dalam rentang 0 hingga 6 (7 hari terakhir)
            if ($selisih_hari_r >= 0 && $selisih_hari_r < 7) {
                $rating_harian[6 - $selisih_hari_r] = $row['jumlah_rating'];
            }
        }
    }
}

$tanggal_array = [];
$tanggal_sekarang = new DateTime();

for ($i = 0; $i < 7; $i++) {
    $tanggal = clone $tanggal_sekarang; 
    $tanggal->modify("-$i day");

    $tanggal_format = $tanggal->format('d') . ' ' . strtolower($tanggal->format('M'));

    $tanggal_array[] = $tanggal_format;
}
$tanggal_array = array_reverse($tanggal_array);