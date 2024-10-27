<?php
// Initialize $kejadian as an empty array if it's not set
$kejadian = isset($kejadian) ? $kejadian : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
    include 'config.php';
    $db = new Database();
?>
<h3>Buat Laporan Pengaduan</h3>
    <form action="simpan_kejadian_pengaduan.php" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Jenis Kejadian</td>
                <td>
                    <input type="text" name="id_jenis_kejadian" value="2" hidden >
                Laporan Pengaduan</td> 
            </tr>
            <tr>
                <td>Nama</td>
                <td>
                    <input type="text" list="namauser" name="id_user"/>
                    <datalist id="namauser">
                        <?php
                            foreach($db->tampil_user() as $x){
                                echo '<option value="'.$x['id_user'].'">'.$x['nama'].' '.$x['nomor_induk'].'</option>';
                            ?>
                            <?php
                            }
                            ?>
                    </datalist>   
                </td> 
            </tr>
            <tr>
                <td>Judul</td>
                <td><textarea name="judul" cols="25" rows="5"></textarea></td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td><textarea name="deskripsi" cols="30" rows="10"></textarea></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input type="datetime-local" name="tanggal" ></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td><input type="text" name="lokasi" placeholder="(Opsional)"></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td><input type="file" name="lampiran" id="lampiran" accept="image/png, image/jpeg, image/jpg"></td>
            </tr>
            <tr>
                <td>Jenis Pengaduan</td>
                <td>
                    <select name="id_jenis_pengaduan" >
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_jenis_pengaduan() as $x){
                            echo '<option value="'.$x['id_jenis_pengaduan'].'">'.$x['nama_jenis_pengaduan'].'</option>';
                        ?>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status Pengaduan</td>
                <td>
                    <select name="status_pengaduan" >
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_status_pengaduan() as $x){
                            echo '<option value="'.$x['id_status_pengaduan'].'">'.$x['nama_status_pengaduan'].'</option>';
                        ?>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Instansi Terkait</td>
                <td>
                    <select name="id_instansi" >
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_instansi() as $x){
                            echo '<option value="'.$x['id_instansi'].'">'.$x['nama_instansi'].'</option>';
                        ?>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Simpan"></td>
            </tr>   
        </table>
    </form>
</body>
</html>
