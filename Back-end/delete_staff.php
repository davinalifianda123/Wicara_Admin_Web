<?php
session_start();
include 'config.php'; // Include konfigurasi database

$id_anggota_instansi = $_POST['id_anggota_instansi'];

if (isset($_POST['delete'])) {
    // Query delete data
    $query = "DELETE FROM anggota_instansi WHERE id_anggota_instansi=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_anggota_instansi);
}

$stmt->execute();
$stmt->close();
$mysqli->close();

// Redirect ke halaman mahasiswa.php jika berhasil
header("Location: ../staff_instansi.php");
exit();

?>
