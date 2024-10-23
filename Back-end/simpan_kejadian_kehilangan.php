<?php
    include('config.php');
    $koneksi = new database();
    $koneksi->tambah_kejadian_kehilangan($_POST['id_jenis_kejadian'],$_POST['id_user'],$_POST['jenis_barang'],
            $_POST['deskripsi'],$_POST['tanggal'],$_POST['lokasi'],$_POST['status_kehilangan'],$_POST['tanggal_kadaluwarsa']);
    header('location:tampil_kejadian_kehilangan.php');
?>