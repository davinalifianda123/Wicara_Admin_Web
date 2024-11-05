<?php
include 'config.php'; // Include konfigurasi database

$id_user = $_POST['id_user'];
$nama = $_POST['nama'];
$nomor_induk = $_POST['nomor_induk'];
$nomor_telepon = $_POST['nomor_telepon'];

// Cek apakah password perlu di-reset
if (isset($_POST['reset_password'])) {
    // Set password default menjadi "polines{NIM}" sesuai NIM pengguna
    $default_password = "polines{$nomor_induk}";

    // Query untuk update data dengan reset password
    $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=?, password=? WHERE id_user=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssi", $nama, $nomor_induk, $nomor_telepon, $default_password, $id_user);
} else {
    // Query untuk update data tanpa reset password
    $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=? WHERE id_user=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssi", $nama, $nomor_induk, $nomor_telepon, $id_user);
}

$stmt->execute();
$stmt->close();
$mysqli->close();

// Redirect ke halaman mahasiswa.php
header("Location: ../mahasiswa.php");
exit(); // Pastikan untuk mengakhiri skrip setelah redirect
?>
