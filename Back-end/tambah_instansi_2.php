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
<h3>Tambah Anggota Instansi</h3>
    <form action="simpan_tambah_instansi.php" method="POST">
        <table>
            <tr>
                <td>Instansi</td>
                <td>
                    <select name="id_instansi">
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
                <td>Anggota PIC</td>
                <td>
                    <select name="id_user">
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_user() as $x){
                            echo '<option value="'.$x['id_user'].'">'.$x['nama'].'</option>';
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
