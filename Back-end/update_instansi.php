<?php
    header("Content-Type: application/json");

    include 'config.php';
    $db = new database();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id-layanan'];
        $nama = $_POST['nama-layanan'];
        $email = $_POST['email-pic'];
        $ratingJeda = $_POST['rating-jeda'];

        $query = "UPDATE instansi SET nama_instansi='$nama', email_pic='$email', jeda_waktu_rating='$ratingJeda' WHERE id_instansi='$id'";

        if (mysqli_query($db->koneksi, $query)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal mengupdate data"]);
        }
    }

    // Redirect ke halaman tampilan
    header('Location: ../rating.php');
?>
