<?php
include('config.php');
$koneksi = new database();

// Ambil tanggal atau gunakan tanggal sekarang jika tidak ada
$tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d H:i:s');

// Panggil function untuk menambah ulasan
$pesan = $koneksi->tambah_kejadian_ulasan(
    $_POST['id_jenis_kejadian'],
    $_POST['id_user'],
    $_POST['id_instansi'],
    $_POST['isi_komentar'],
    $tanggal,
    $tanggal,
    $_POST['skala_bintang']
);

// Redirect atau tampilkan pesan
if (strpos($pesan, 'berhasil') !== false) {
    header("location:tampil_kejadian_ulasan.php?status=success&message=" . urlencode($pesan));
} else {
    header("location:tampil_kejadian_ulasan.php?status=error&message=" . urlencode($pesan));
}
exit;
?>
