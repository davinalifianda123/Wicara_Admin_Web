<?php
    include('config.php');
    $koneksi = new database();
    $koneksi->tambah_kejadian_pengaduan($_POST['id_jenis_kejadian'],$_POST['id_user'],$_POST['judul'],
            $_POST['deskripsi'],$_POST['tanggal'],$_POST['lokasi'],$_POST['lampiran'],
            $_POST['id_jenis_pengaduan'], $_POST['status_pengaduan'], $_POST['id_instansi']);
    header('location:tampil_kejadian_pengaduan.php');
?>