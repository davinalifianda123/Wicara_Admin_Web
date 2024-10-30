<?php
include 'config.php';
if (isset($_GET['id'])) {
    $db = new Database();
    $id = $_GET['id'];
    $db->hapus_jenis_pengaduan($id);
    header("Location: tampil_jenis_pengaduan.php");
}
?>