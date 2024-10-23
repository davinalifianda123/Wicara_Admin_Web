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
<h3>Buat Laporan Kehilangan</h3>
    <form action="simpan_kejadian_ulasan.php" method="POST">
        <table>
            <tr>
                <td>Jenis Kejadian</td>
                <td>
                    <input type="text" name="id_jenis_kejadian" value="3" hidden >
                Ulasan</td> 
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
                <td>Komentar</td>
                <td><textarea name="isi_komentar" cols="30" rows="10"></textarea></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input type="datetime-local" name="tanggal" ></td>
            </tr>
            <tr>
                <td>Skala Bintang</td>
                <td>
                    <input type="text" name="skala_bintang" list="bintang">
                    <datalist id="bintang">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </datalist>
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
