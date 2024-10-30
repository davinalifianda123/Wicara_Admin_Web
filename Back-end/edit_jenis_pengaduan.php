<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jenis Pengaduan</title>
</head>
<body>
    <?php
        include 'config.php';
        $db = new Database();
        
        // Cek apakah id pengaduan ada di URL
        if (isset($_GET['id'])) {
            $id_jenis_pengaduan = $_GET['id'];
            $data_jenis_pengaduan = $db->kode_pengaduan($id_jenis_pengaduan);
        } else {
            echo "Data tidak ditemukan!";
            header('Location: tampil_jenis_pengaduan.php');
            exit;
        }
    ?>
    
    <h3>Edit Data Jenis Pengaduan</h3>
    <form action="simpan_edit_jenis_pengaduan.php" method="POST">
        <input type="hidden" name="id_jenis_pengaduan" value="<?php echo $data_jenis_pengaduan[0]['id_jenis_pengaduan']; ?>" />
        
        <table>
            <tr>
                <td>ID Jenis Pengaduan</td>
                <td><input type="text" name="id_jenis_pengaduan" value="<?php echo $data_jenis_pengaduan[0]['id_jenis_pengaduan']; ?>" disabled></td>
            </tr>
            <tr>
                <td>Nama Jenis Pengaduan</td>
                <td><input type="text" name="nama_jenis_pengaduan" value="<?php echo $data_jenis_pengaduan[0]['nama_jenis_pengaduan']; ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Simpan"></td>
            </tr>
        </table>
    </form>
</body>
</html>
