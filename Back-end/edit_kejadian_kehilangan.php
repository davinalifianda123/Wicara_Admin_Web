<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include 'config.php';
        $db = new Database();
        if(isset($_GET['id'])){
            $id_kejadian = $_GET['id'];
            $data_jenis_kejadian = $db->kode_kejadian($id_kejadian);
        }
        else
        {
            echo "aaaa";
            header('Location: tampil_kejadian.php');
        }
    ?>
    <h3>Edit Data Kehilangan</h3>
    <form action="simpan_edit_kejadian_kehilangan.php" method="POST">
    <input type="hidden"  name="id_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_kejadian'];?>"/>
        <table>
            <tr>
                <td>Kode Peminjam</td>
                <td><input type="text" name="id_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_kejadian'];?>" disabled></td>
            </tr>
            <tr>
                <td>Jenis Kejadian</td>
                <td>
                    <input type="text" name="id_jenis_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_jenis_kejadian'];?>" hidden> Laporan Kehilangan
                </td> 
            </tr>
            <tr>
                <td>Nama</td>
                <td>
                    <select name="id_user" >
                            <?php
                                $no = 1;
                                $id_nama = $data_jenis_kejadian[0]['id_user'];
                                foreach($db->tampil_user() as $x){
                                    echo "<option value".$x['id_user'];
                                    if ($x['id_user'] == $id_nama){echo " selected=selected";}
                                    echo ">".$x['nama']." </option>";
                                }
                            ?>
                    </select>
                </td> 
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><input name="jenis_barang" cols="25" rows="5" value="<?php echo $data_jenis_kejadian[0]['jenis_barang'];?>"></></td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td><input name="deskripsi" cols="30" rows="10" value="<?php echo $data_jenis_kejadian[0]['deskripsi'];?>"></></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input type="datetime-local" name="tanggal" value="<?php echo $data_jenis_kejadian[0]['tanggal'];?>"></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td><input type="text" name="lokasi" placeholder="(Opsional)" value="<?php echo $data_jenis_kejadian[0]['lokasi'];?>"></td>
            </tr>
            <tr>
                <td>Status Kehilangan</td>
                <td>
                    <select name="status_kehilangan" >
                            <?php
                                $no = 1;
                                $skel = $data_jenis_kejadian[0]['status_kehilangan'];
                                foreach($db->tampil_status_kehilangan() as $x){
                                    echo "<option value".$x['id_status_kehilangan'];
                                    if($x['id_status_kehilangan']==$skel){echo " selected=selected";}
                                    echo ">".$x['nama_status_kehilangan']."</option>";
                                }
                            ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Tanggal Kadaluwarsa</td>
                <td><input type="datetime-local" name="tanggal_kadaluwarsa" value="<?php echo $data_jenis_kejadian[0]['tanggal_kadaluwarsa'];?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Simpan"></td>
            </tr>   
        </table>
    </form>
</body>
</html>