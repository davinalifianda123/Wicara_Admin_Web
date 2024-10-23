<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    <h1>Kejadian Pengaduan</h1>
    <?php
        include 'config.php';
        $db = new Database();
    ?>
    <table border="1">
    <tr>
        <th>No</th>  
        <th>Id Kejadian</th>
        <th>Nama Kejadian</th>             
        <th>Judul</th>        
        <th>Deskripsi</th>               
        <th>Lokasi</th>   
        <th>Tanggal</th>
        <th>Lampiran</th>
        <th>Id User</th>
        <th>Jenis Pengaduan</th>
        <th>Status Pengaduan</th>
        <th>Instansi Terkait</th>
    
    </tr>
    <?php
    $no = 1;
    foreach($db->tampil_data_pengaduan() as $x){
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $x['id_kejadian']; ?></td>
        <td><?php echo $x['nama_kejadian'];?> </td>
        <td><?php echo $x['judul'];?> </td>
        <td><?php echo $x['deskripsi']; ?></td>
        <td><?php echo $x['lokasi']; ?></td>
        <td><?php echo $x['tanggal']; ?></td>
        <td><?php echo $x['lampiran'];?> </td>
        <td><?php echo $x['nama']; ?></td>
        <td><?php echo $x['nama_jenis_pengaduan']; ?></td>
        <td><?php echo $x['nama_status_pengaduan']; ?></td>
        <td><?php echo $x['nama_instansi'];?> </td>
        <td><a href="edit_kejadian_pengaduan.php?id=<?php echo $x['id_kejadian']; ?>">Edit</a></td>
        <td><a href="hapus_kejadian_pengaduan.php?id=<?php echo $x['id_kejadian']; ?>">Hapus</a></td>
    </tr>
    <?php
    }
    ?>
    </table>
    <h1></h1>
    <button>Tambah</button>
</body>
</html>
