<?php
header("Content-Type: application/json");

include './config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Retrieve the image path from the database
    $query = "SELECT image_instansi FROM instansi WHERE id_instansi='$id'";
    $result = mysqli_query($db->koneksi, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $imagePath = $data['image_instansi'];
        
        // Step 2: Delete the image file if it exists
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Step 3: Delete the database row
    $deleteQuery = "DELETE FROM instansi WHERE id_instansi='$id'";
    if (mysqli_query($db->koneksi, $deleteQuery)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete data"]);
    }
}

// Redirect to the display page
header('Location: ../rating.php');
?>
