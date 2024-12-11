<?php
include('config.php');
$koneksi = new database();

// Tentukan folder untuk menyimpan lampiran
$upload_dir = './foto-kehilangan/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Cek apakah ada file yang diunggah
$lampiran = null;
if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == 0) {
    // Validasi tipe file
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (in_array($_FILES['lampiran']['type'], $allowed_types)) {
        // Buat nama file unik
        $lampiran_name = uniqid() . '-' . basename($_FILES['lampiran']['name']);
        $target_file = $upload_dir . $lampiran_name;

        if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $target_file)) {
            // Hanya simpan nama file, bukan path lengkap
            $lampiran = $lampiran_name;
        } else {
            echo "Gagal mengunggah file. Error: " . $_FILES['lampiran']['error'];
            exit(); // Berhenti jika gagal mengunggah
        }
    } else {
        echo "Tipe file tidak diizinkan.";
        exit(); // Berhenti jika tipe file tidak diizinkan
    }
}

// Panggil fungsi untuk menyimpan data
$koneksi->tambah_kejadian_kehilangan(
    $_POST['id_jenis_kejadian'],
    $_POST['id_user'],
    $_POST['judul'],
    $_POST['deskripsi'],
    $_POST['tanggal'],
    $_POST['lokasi'],
    $lampiran, // Kirim nama file lampiran
    $_POST['jenis_barang'],
    $_POST['status_kehilangan'],
    // $_POST['tanggal_kadaluwarsa'],
    $_POST['status_notif']
);

// Redirect ke halaman tampilan
header('Location: tampil_kejadian_kehilangan.php');
?>
