<?php
session_start();
include 'config.php'; // Include konfigurasi database

$id_user = $_POST['id_user'];
$nama = $_POST['nama'];
$nomor_induk = $_POST['nomor_induk'];
$nomor_telepon = $_POST['nomor_telepon'];

if (isset($_POST['delete'])) {
    // Query delete data
    $query = "DELETE FROM user WHERE id_user=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_user);
} else {
    if (isset($_POST['reset_password'])) {
        // Set password default menjadi "polines123*" sesuai NIM pengguna
        $default_password = "Polines123*";
        $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=?, password=? WHERE id_user=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssi", $nama, $nomor_induk, $nomor_telepon, $default_password, $id_user);
    } else {
        // Query untuk update data tanpa reset password
        $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=? WHERE id_user=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssi", $nama, $nomor_induk, $nomor_telepon, $id_user);
    }
}

$stmt->execute();
$stmt->close();
$mysqli->close();

// Redirect ke halaman mahasiswa.php jika berhasil
header("Location: ../mahasiswa.php");
exit();

?>
