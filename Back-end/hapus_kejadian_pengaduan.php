<?php
    include('config.php');
    $db = new Database();
    if(isset($_GET['id'])){
        $id_kejadian = $_GET['id'];
        $db->hapus_data($id_kejadian);
        header('location:tampil_kejadian_pengaduan.php');
    }
    else{
        header('Location : tampil_kejadian.php');
    }
?>