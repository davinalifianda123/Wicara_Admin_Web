<?php
    include 'config.php';
    $db = new database();

    $pengaduan = mysqli_query($db->koneksi, "SELECT * FROM kejadian WHERE id_jenis_kejadian = 2");
?>