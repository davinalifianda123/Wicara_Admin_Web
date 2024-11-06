<?php
    header("Content-Type: application/json");

    include './config.php';
    $db = new database();

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = "DELETE FROM instansi WHERE id_instansi='$id'";

        if (mysqli_query($db->koneksi, $query)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus data"]);
        }
    }

    // Redirect ke halaman tampilan
    header('Location: ../rating.php');
?>
