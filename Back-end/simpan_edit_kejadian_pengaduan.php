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
    
        $status_pengaduan = $_POST['status_pengaduan'];
        if($status_pengaduan== "Diajukan"){
            $status_pengaduan = 1;
        }
        else if($status_pengaduan == "Dibatalkan"){
            $status_pengaduan = 2;
        }
        else if($status_pengaduan == "Diproses"){
            $status_pengaduan = 3;
        }
        else if($status_pengaduan == "Ditolak"){
            $status_pengaduan = 4;
        }
        else if($status_pengaduan == "Selesai"){
            $status_pengaduan = 5;
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

        $jenis_pengaduan = $_POST['id_jenis_pengaduan'];
        if($jenis_pengaduan== "Bullying"){
            $jenis_pengaduan = 1;
        }
        else if($jenis_pengaduan == "Kerusakan Fasilitas"){
            $jenis_pengaduan = 2;
        }
        else if($jenis_pengaduan == "Kekerasan Seksual"){
            $jenis_pengaduan = 3;
        }

    $koneksi = new database();
    $koneksi->edit_kejadian_pengaduan($_POST['id_kejadian'],$_POST['id_jenis_kejadian'],
                        $id_user, $_POST['judul'], $_POST['deskripsi'], $_POST['tanggal'], 
                        $_POST['lokasi'], $_POST['lampiran'], $status_pengaduan, $jenis_pengaduan,
                        $instansi);
    header('location:tampil_kejadian_pengaduan.php');
?>