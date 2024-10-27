<?php
session_start();
include 'config.php';
$db = new database();

if (isset($_POST['update'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $nomor_induk = $_POST['nomor_induk'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Tentukan folder untuk menyimpan gambar
    $upload_dir = './foto-profile/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Ambil nama file gambar lama dari database
    $old_image = $db->get_user_image($id_user);

    // Cek apakah ada file gambar yang diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Buat nama file unik
        $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $image_name;
        
        // Pindahkan file ke server
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Hapus gambar lama jika ada
            if ($old_image && file_exists($old_image)) {
                unlink($old_image);
            }

            // Update data user dengan gambar baru
            $db->edit_user_with_image($id_user, $nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $target_file);
        } else {
            echo "Gagal mengunggah file.";
        }
    } else {
        // Update data user tanpa gambar
        $db->edit_user_with_image($id_user, $nama, $nomor_induk, $nomor_telepon, $email, $password, $role, null);
    }
    
    // Redirect ke profile setelah update
    header("Location: ../Dashboard.php?message=Profile updated successfully");
}
?>
