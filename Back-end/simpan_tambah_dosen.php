<?php
include('config.php');
$koneksi = new database();

// Mengambil NIM dari input dan menggabungkannya dengan string "polines"
$nim = $_POST['nomor_induk'];
$password = "polines" . $nim; // Password default

// Role 2 (dosen) diatur langsung
$role = 2;

$koneksi->tambah_user($_POST['nama'], $_POST['nomor_induk'], $_POST['nomor_telepon'],
    $_POST['email'], $password, $role, $_POST['image']); // Menambahkan parameter $role
header('location: ../dosen.php');
?>
