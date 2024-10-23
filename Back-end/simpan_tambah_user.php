<?php
    include('config.php');
    $koneksi = new database();
    $koneksi->tambah_user($_POST['nama'],$_POST['nomor_induk'],$_POST['nomor_telepon'],
            $_POST['email'],$_POST['password'],$_POST['role']);
    header('location:tampil_user.php');
?>