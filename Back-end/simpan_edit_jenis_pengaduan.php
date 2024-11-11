<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_jenis_pengaduan = $_POST['id_jenis_pengaduan'];
    $nama_jenis_pengaduan = $_POST['nama_jenis_pengaduan'];

    $koneksi = new Database();
    $koneksi->edit_jenis_pengaduan($id_jenis_pengaduan, $nama_jenis_pengaduan);

    header('Location: tampil_jenis_pengaduan.php');
    exit;
}
?>
