<?php
    include('config.php');
    $koneksi = new database();
    $koneksi->tambah_kejadian_ulasan($_POST['id_jenis_kejadian'],$_POST['id_user'],$_POST['id_instansi'],
            $_POST['isi_komentar'],$_POST['tanggal'],$_POST['skala_bintang']);
    header('location:tampil_kejadian_ulasan.php');
?>