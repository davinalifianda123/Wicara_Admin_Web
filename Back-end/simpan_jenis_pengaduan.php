<?php
    include('config.php');
    $koneksi = new Database();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_jenis_pengaduan = $_POST['nama_jenis_pengaduan'];
        $koneksi->tambah_jenis_pengaduan($nama_jenis_pengaduan);
        header('location:tampil_jenis_pengaduan.php');
}
?>
