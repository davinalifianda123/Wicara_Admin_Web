<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Document</title>
</head>
<body>
    <h1>Data Master</h1>
    <?php
        include 'config.php';
        $db = new Database();
    ?>
    <table border="1">
    <tr>
        <th>No</th>   
        <td>Jenis Kejadian</td>            
        <th>Judul</th>        
        <th>Deskripsi</th>               
        <th>Lokasi</th>   
        <th>Tanggal</th>
        <th>Lampiran</th>
        <th>Jenis Barang</th>               
        <th>Isi Komentar</th>        
        <th>Skala Bintang</th>               
        <th>Id Jenis Pengaduan</th>   
        <th>Id User</th>
        <th>Id Instansi</th>
        <th>Status Pengaduan</th>
        <th>Status Kehilangan</th>
        <th>Tanggal Kadaluarsa</th>
    
    </tr>
    <?php
    $no = 1;
    foreach($db->tampil_data() as $x){
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $x['nama_kejadian'];?> </td>
        <td><?php echo $x['judul'];?> </td>
        <td><?php echo $x['deskripsi']; ?></td>
        <td><?php echo $x['lokasi']; ?></td>
        <td><?php echo $x['tanggal']; ?></td>
        <td><?php echo $x['lampiran']; ?></td>
        <td><?php echo $x['jenis_barang']; ?></td>
        <td><?php echo $x['isi_komentar']; ?></td>
        <td><?php echo $x['skala_bintang']; ?></td>
        <td><?php echo $x['nama_jenis_pengaduan']; ?></td>
        <td><?php echo $x['nama']; ?></td>
        <td><?php echo $x['nama_instansi']; ?></td>
        <td><?php echo $x['nama_status_pengaduan']; ?></td>
        <td><?php echo $x['nama_status_kehilangan']; ?></td>
        <td><?php echo $x['tanggal_kadaluwarsa']; ?></td>
    </tr>
    <?php
    }
    ?>
    </table>
    

    <h1>Tabel kejadian Pengaduan</h1>
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
    <button><a href="tambah_kejadian_pengaduan.php">Tambah</a></button>

    <h1>Tabel Kejadian Kehilangan</h1>
    <table border="1">
    <tr>
        <th>No</th>
        <th>Id Kejadian</th>  
        <th>Nama Kejadian</th>                   
        <th>Deskripsi</th>               
        <th>Lokasi</th>   
        <th>Tanggal</th>
        <th>Jenis Barang</th>               
        <th>Id User</th>
        <th>Status Kehilangan</th>
        <th>Tanggal Kadaluarsa</th>
    
    </tr>
    <?php
    $no = 1;
    foreach($db->tampil_data_kehilangan() as $x){
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $x['id_kejadian'];?> </td>
        <td><?php echo $x['nama_kejadian'];?> </td>
        <td><?php echo $x['deskripsi']; ?></td>
        <td><?php echo $x['lokasi']; ?></td>
        <td><?php echo $x['tanggal']; ?></td>
        <td><?php echo $x['jenis_barang']; ?></td>
        <td><?php echo $x['nama']; ?></td>
        <td><?php echo $x['nama_status_kehilangan']; ?></td>
        <td><?php echo $x['tanggal_kadaluwarsa']; ?></td>
        <td><a href="edit_kejadian_kehilangan.php?id=<?php echo $x['id_kejadian']; ?>">Edit</a></td>
        <td><a href="hapus_kejadian_kehilangan.php?id=<?php echo $x['id_kejadian']; ?>">Hapus</a></td>
    </tr>
    <?php
    }
    ?>
    </table>
    <h1></h1>
    <button><a href="tambah_kejadian_kehilangan.php">Tambah</a></button>

    <h1>Tabel Kejadian Ulasan</h1>
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
    <button><a href="tambah_kejadian_ulasan.php">Tambah</a></button>
</body>
</html>
