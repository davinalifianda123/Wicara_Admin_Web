<?php
include('config.php');
$koneksi = new database();

$password = "Polines123*"; // Password default

// Role 2 (dosen) diatur langsung
$role = 2;

$image = isset($_POST['image']) ? $_POST['image'] : null;

$koneksi->tambah_user($_POST['nama'], $_POST['nomor_induk'], $_POST['nomor_telepon'], $_POST['email'], $password, $role, $image);
header('location: ../dosen.php');
?>
