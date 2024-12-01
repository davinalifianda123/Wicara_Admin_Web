<?php
    include('config.php');
    $koneksi = new database();
    $koneksi->tambah_anggota_instansi($_POST['id_instansi'],$_POST['id_user']);
    header('location:../staff_instansi.php');
?>