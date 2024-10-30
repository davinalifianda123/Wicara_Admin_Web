<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include 'config.php';
        $db = new Database();
    ?>
    <table border="1">
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Jenis Pengaduan</th>
        </tr>
        <?php
        $no = 1;
        foreach($db->tampil_jenis_pengaduan() as $x){
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $x['id_jenis_pengaduan']; ?></td>
            <td><?php echo $x['nama_jenis_pengaduan']; ?></td>
            <td><a href="edit_jenis_pengaduan.php?id=<?php echo $x['id_jenis_pengaduan']; ?>">Edit</a></td>
            <td><a href="hapus_jenis_pengaduan.php?id=<?php echo $x['id_jenis_pengaduan']; ?>">Hapus</a></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>