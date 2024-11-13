<?php
include('config.php');
$koneksi = new database();

$password = "Polines123*"; // Password default

// Role 2 (dosen) diatur langsung
$role = 2;

$image = isset($_POST['image']) ? $_POST['image'] : null;
$id_instansi = isset($_POST['id_instansi']) && $_POST['id_instansi'] !== '' ? $_POST['id_instansi'] : 0;

$koneksi->tambah_user($_POST['nama'], $_POST['nomor_induk'], $_POST['nomor_telepon'], $_POST['email'], $password, $role, $image, $id_instansi);
header('location: ../dosen.php');
?>
