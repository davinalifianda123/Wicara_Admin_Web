<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    <h1>Kejadian Ulasan</h1>
    <?php
        include 'config.php';
        $db = new Database();
    ?>
    <table border="1">
    <tr>
        <th>No</th> 
        <th>Id Kejadian</th> 
        <th>Nama Kejadian</th>                   
        <th>Isi komentar</th>               
        <th>Skala Bintang</th>   
        <th>Tanggal</th>
        <th>Id User</th>
        <th>Instansi Terkait</th>
    
    </tr>
    <?php
    $no = 1;
    foreach($db->tampil_data_ulasan() as $x){
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $x['id_kejadian']; ?></td>
        <td><?php echo $x['nama_kejadian'];?> </td>
        <td><?php echo $x['isi_komentar']; ?></td>
        <td><?php echo $x['skala_bintang']; ?></td>
        <td><?php echo $x['tanggal']; ?></td>
        <td><?php echo $x['nama']; ?></td>
        <td><?php echo $x['nama_instansi']; ?></td>
        <td><a href="edit_kejadian_ulasan.php?id=<?php echo $x['id_kejadian']; ?>">Edit</a></td>
        <td><a href="hapus_kejadian_ulasan.php?id=<?php echo $x['id_kejadian']; ?>">Hapus</a></td>
    </tr>
    <?php
    }
    ?>
    </table>
    <h1></h1>
    <button>Tambah</button>
</body>
</html>
