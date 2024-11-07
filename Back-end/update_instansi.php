<?php
header("Content-Type: application/json");

include 'config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_instansi = $_POST['id_instansi'];
    $nama_instansi = $_POST['nama_instansi'];
    $email_pic = $_POST['email_pic'];
    $password = $_POST['password'];

    // Tentukan folder untuk menyimpan gambar
    $upload_dir = './foto-instansi/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Ambil nama file gambar lama dari database
    $old_image = $db->get_instansi_image($id_instansi);

    // Cek apakah ada file gambar yang diupload
    if (isset($_FILES['image_instansi']) && $_FILES['image_instansi']['error'] == 0) {
        // Buat nama file unik
        $image_name = time() . '_' . basename($_FILES['image_instansi']['name']);
        $target_file = $upload_dir . $image_name;
        
        // Pindahkan file ke server
        if (move_uploaded_file($_FILES['image_instansi']['tmp_name'], $target_file)) {
            // Hapus gambar lama jika ada dan berbeda dari yang baru
            if ($old_image && file_exists($old_image) && $old_image !== $target_file) {
                unlink($old_image);
            }

            // Update data user dengan gambar baru
            $db->edit_instansi_with_image($id_instansi, $nama_instansi, $email_pic, $password, $target_file);
        } else {
            echo json_encode(["error" => "Gagal mengunggah file."]);
            exit;
        }
    } else {
        // Update data user tanpa gambar
        $db->edit_instansi_with_image($id_instansi, $nama_instansi, $email_pic, $password, null);
    }
}

// Redirect ke halaman tampilan
header('Location: ../rating.php');
?>
