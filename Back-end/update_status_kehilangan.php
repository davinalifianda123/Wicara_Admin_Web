<?php
    include 'config.php';
    $db = new database();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idKejadian = $_POST['id_kejadian'];
        $action = $_POST['action'];

        if ($action === 'terima') {
            $statusKehilangan = 1; // ID status untuk "Belum Ditemukan"
        } elseif ($action === 'delete') {
            $image = "SELECT lampiran FROM kejadian WHERE id_kejadian='$idKejadian'";
            $result = mysqli_query($db->koneksi, $image);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $imagePath = $data['lampiran'];
                
                // Step 4: Delete the image file if it exists
                if ($imagePath && file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $query = "DELETE FROM kejadian WHERE id_kejadian = '$idKejadian'";
            mysqli_query($db->koneksi, $query);
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            exit;
        }

        if (isset($statusKehilangan)) {
            $query = "UPDATE kejadian SET status_kehilangan = '$statusKehilangan' WHERE id_kejadian = '$idKejadian'";
            mysqli_query($db->koneksi, $query);
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
            echo "<script>
                    alert('Status berhasil diperbarui');
                    window.location.href = '../kehilangan.php';
                </script>";
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
    }
?>
