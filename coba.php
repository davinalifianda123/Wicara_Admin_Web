<?php
include './Back-end/api_dashboard.php';

$pengaduan_line = "
    SELECT DATE(tanggal) AS hari, COUNT(*) AS jumlah_pengaduan
    FROM kejadian
    WHERE id_jenis_kejadian = '2'
    AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY hari
    ORDER BY hari DESC
";
$hasil_pengaduanline = $conn->query($pengaduan_line);

$pengaduan_harian = array_fill(0, 7, 0);

$tanggal_hari_ini = strtotime(date("Y-m-d"));

// Memeriksa hasil query dan menampilkannya
if ($hasil_pengaduanline->num_rows > 0) {
    while ($row = $hasil_pengaduanline->fetch_assoc()) { // Hapus tanda titik koma setelah while
        if ($row['hari'] !== null) {
            // Hitung selisih hari dari hari ini ke tanggal pengaduan
            $selisih_hari = (int)(($tanggal_hari_ini - strtotime($row['hari'])) / (60 * 60 * 24));
            
            // Pastikan selisih hari berada dalam rentang 0 hingga 6 (7 hari terakhir)
            if ($selisih_hari >= 0 && $selisih_hari < 7) {
                $pengaduan_harian[6 - $selisih_hari] = $row['jumlah_pengaduan'];
            }
        }
    }
}

//buat coba ngetest api
// print_r($pengaduan_harian);
// echo "{" . implode(", ", $pengaduan_harian) . "}";