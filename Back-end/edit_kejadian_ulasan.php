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
    <h3>Edit Data Ulasan</h3>
    <form action="simpan_edit_kejadian_ulasan.php" method="POST">
    <input type="hidden"  name="id_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_kejadian'];?>"/>
        <table>
            <tr>
                <td>Kode Peminjam</td>
                <td><input type="text" name="id_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_kejadian'];?>" disabled></td>
            </tr>
            <tr>
                <td>Jenis Kejadian</td>
                <td>
                    <input type="text" name="id_jenis_kejadian" value="<?php echo $data_jenis_kejadian[0]['id_jenis_kejadian'];?>" hidden> Ulasan
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
                <td>Instansi Terkait</td>
                <td>
                    <select name="id_instansi" >
                            <?php
                                $no = 1;
                                $skel = $data_jenis_kejadian[0]['id_instansi'];
                                foreach($db->tampil_instansi() as $x){
                                    echo "<option value".$x['id_instansi'];
                                    if($x['id_instansi']==$skel){echo " selected=selected";}
                                    echo ">".$x['nama_instansi']."</option>";
                                }
                            ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Isi Komentar</td>
                <td><input name="isi_komentar" cols="30" rows="10" value="<?php echo $data_jenis_kejadian[0]['isi_komentar'];?>"></></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input type="datetime-local" name="tanggal" value="<?php echo $data_jenis_kejadian[0]['tanggal'];?>"></td>
            </tr>
            <tr>
                <td>Skala Bintang</td>
                <td>
                    <input type="text" name="skala_bintang" list="bintang" value="<?php echo $data_jenis_kejadian[0]['skala_bintang'];?>" >
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