<?php
    include 'config.php';
    $db = new database();

    // api aktivitas card
    $pengaduan = mysqli_query($db->koneksi, "SELECT * FROM kejadian WHERE id_jenis_kejadian = 2");
    $kehilangan = mysqli_query($db->koneksi, "SELECT * FROM kejadian WHERE id_jenis_kejadian = 1");
    $rating = mysqli_query($db->koneksi, "SELECT * FROM kejadian WHERE id_jenis_kejadian = 3");

    // api data statistik user
    $mahasiswaCount = mysqli_query($db->koneksi, "SELECT * FROM user WHERE role = 3");
    $dosenTendikCount = mysqli_query($db->koneksi, "SELECT * FROM user WHERE role = 2 and 4");
    $unitLayananCount = mysqli_query($db->koneksi, "SELECT * FROM instansi");

    // api data donat
    
 