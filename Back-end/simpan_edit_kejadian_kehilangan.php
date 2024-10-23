<?php
    include('config.php');
        $id_user = $_POST['id_user'];
        if($id_user == "Fitriana"){
            $id_user = 1;
        }
        else if($id_user == "Ahmad"){
            $id_user = 2;
        }
        else if($id_user == "Agung"){
            $id_user = 3;
        }
    
        $status_kehilangan = $_POST['status_kehilangan'];
        if($status_kehilangan== "Belum Ditemukan"){
            $status_kehilangan = 1;
        }
        else if($status_kehilangan == "Ditemukan"){
            $status_kehilangan = 2;
        }
        else if($status_kehilangan == "Hilang"){
            $status_kehilangan = 3;
        }
    $koneksi = new database();
    $koneksi->edit_kejadian_kehilangan($_POST['id_kejadian'],$_POST['id_jenis_kejadian'],
                        $id_user, $_POST['jenis_barang'], $_POST['deskripsi'], $_POST['tanggal'], 
                        $_POST['lokasi'], $status_kehilangan, $_POST['tanggal_kadaluwarsa']);
    header('location:tampil_kejadian_kehilangan.php');
?>