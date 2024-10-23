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
<h3>Tambah User</h3>
    <form action="simpan_tambah_user.php" method="POST">
        <table>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" ></td>
            </tr>
            <tr>
                <td>Nomer Induk</td>
                <td><input type="text" name="nomor_induk" ></td>
            </tr>
            <tr>
                <td>Nomer Telepon</td>
                <td><input type="text" name="nomor_telepon" ></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" ></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="text" name="password" ></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>
                    <select name="role">
                    <option value=""></option>
                    <?php 
                        foreach($db->tampil_role() as $x){
                            echo '<option value="'.$x['id_role'].'">'.$x['nama_role'].'</option>';
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
