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
    <form action="simpan_kejadian_kehilangan.php" method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Jenis Kejadian</td>
                <td>
                    <input type="text" name="id_jenis_kejadian" value="1" hidden >
                Laporan Kehilangan</td> 
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
                <td>Nama Barang</td>
                <td><textarea name="nama_barang" cols="25" rows="5"></textarea></td>
            </tr>
            <tr>
                <td>Deskripsi</td>
                <td><textarea name="deskripsi" cols="30" rows="10"></textarea></td>
            </tr>
            <tr>
                <td>
                    <label for="tanggal">Tanggal</label>
                </td>
                <td><input type="datetime-local" name="tanggal" id="tanggal" onchange="setTanggalKadaluwarsa()" required></td>
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
                <td>Status Kehilangan</td>
                <td>
                    <select name="status_kehilangan" >
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_status_kehilangan() as $x){
                            echo '<option value="'.$x['id_status_kehilangan'].'">
                            '.$x['nama_status_kehilangan'].'</option>';
                        ?>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <!-- <tr>
                <td>
                    <label for="tanggal_kadaluwarsa">Tanggal Kadaluwarsa</label>
                </td>
                <td><input type="date" id="tanggal_kadaluwarsa" name="tanggal_kadaluwarsa" readonly></td>
            </tr> -->
            <tr>
                <td></td>
                <td><input type="submit" value="Simpan"></td>
            </tr>   
        </table>
    </form>
    <script>
    // function setTanggalKadaluwarsa() {
    //     const tanggal = new Date(document.getElementById("tanggal").value);
    //     if (!isNaN(tanggal)) {
    //         // Tambahkan 7 hari (1 minggu)
    //         tanggal.setDate(tanggal.getDate() + 7);
            
    //         // Format ke input date
    //         const tanggalKadaluwarsa = tanggal.toISOString().split('T')[0];
    //         document.getElementById("tanggal_kadaluwarsa").value = tanggalKadaluwarsa;
    //     }
    // }
    </script>
</body>
</html>
