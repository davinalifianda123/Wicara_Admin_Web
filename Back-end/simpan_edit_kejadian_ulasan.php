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

        $instansi = $_POST['id_instansi'];
        if($instansi== "Poliklinik"){
            $instansi = 1;
        }
        else if($instansi == "PPKS"){
            $instansi = 2;
        }
        else if($instansi == "UPA Bahasa"){
            $instansi = 3;
        }
        else if($instansi == "UPA Perpustakaan"){
            $instansi = 4;
        }
        else if($instansi == "UPA TIK"){
            $instansi = 5;
        }
    

    $koneksi = new database();
    $koneksi->edit_kejadian_ulasan($_POST['id_kejadian'],$_POST['id_jenis_kejadian'],
                        $id_user, $instansi, $_POST['isi_komentar'], $_POST['tanggal'], 
                        $_POST['skala_bintang']);
    header('location:tampil_kejadian_ulasan.php');
?>