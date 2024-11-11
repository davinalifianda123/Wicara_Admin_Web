<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis Pengaduan</title>
</head>
<body>
    <form action="" method="post">
        <label>Nama Jenis Pengaduan:</label>
        <input type="text" name="nama_jenis_pengaduan" required>
        <button type="submit" name="submit">Tambah</button>
    </form>

    <?php
    include 'config.php';
    if (isset($_POST['submit'])) {
        $nama_jenis_pengaduan = $_POST['nama_jenis_pengaduan'];
        $db = new Database();
        $db->tambah_jenis_pengaduan($nama_jenis_pengaduan);
        header("Location: tampil_jenis_pengaduan.php");
    }
    ?>
</body>
</html>
