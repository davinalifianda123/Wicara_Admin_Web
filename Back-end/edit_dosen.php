<?php
include 'config.php'; // Include database configuration

$id_user = $_POST['id_user'];
$nama = $_POST['nama'];
$nomor_induk = $_POST['nomor_induk'];
$nomor_telepon = $_POST['nomor_telepon'];

// Check if the delete action is triggered
if (isset($_POST['delete'])) {
    // Prepare delete query
    $query = "DELETE FROM user WHERE id_user=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_user);
} else {
    // Check if password needs to be reset
    if (isset($_POST['reset_password'])) {
        // Set default password to "Polines123*"
        $default_password = "Polines123*";

        // Query to update data with password reset
        $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=?, password=? WHERE id_user=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssi", $nama, $nomor_induk, $nomor_telepon, $default_password, $id_user);
    } else {
        // Query to update data without password reset
        $query = "UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=? WHERE id_user=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssi", $nama, $nomor_induk, $nomor_telepon, $id_user);
    }
}

// Execute the query
$stmt->execute();
$stmt->close();
$mysqli->close();

// Redirect to dosen.php page
header("Location: ../dosen.php");
exit(); // Ensure to end the script after redirect
?>
