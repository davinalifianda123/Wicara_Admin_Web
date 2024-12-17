<?php
// Include file database
include 'config.php';

// Buat instance kelas database
$db = new database();
$conn = $db->koneksi;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query untuk mendapatkan file berdasarkan ID
    $query = "SELECT poster_url FROM instansi WHERE id_instansi = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $posterPath = '../posters/'.$row['poster_url'];

        // Cek apakah file ada
        if (file_exists($posterPath)) {
            // Unduh file
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($posterPath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($posterPath));
            readfile($posterPath);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "No data found for the given ID.";
    }
} else {
    echo "Invalid request.";
}
?>
