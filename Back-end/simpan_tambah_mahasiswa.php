<?php
include('config.php');
$koneksi = new database();

$password = "Polines123*"; // Password default

// Role 3 (mahasiswa) diatur langsung
$role = 3;

$koneksi->tambah_user($_POST['nama'], $_POST['nomor_induk'], $_POST['nomor_telepon'],
    $_POST['email'], $password, $role, NULL); // Menambahkan parameter $role
header('location: ../mahasiswa.php');
?>
