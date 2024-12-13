<?php
header("Content-Type: application/json");

include './config.php';
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Retrieve the image path image_instansi from the database
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
    // Step 3: Retrieve the image path qr_code_url from the database
    $query2 = "SELECT qr_code_url FROM instansi WHERE id_instansi='$id'";
    $result2 = mysqli_query($db->koneksi, $query2);
    
    if ($result2 && mysqli_num_rows($result2) > 0) {
        $data2 = mysqli_fetch_assoc($result2);
        $imagePath2 = $data2['qr_code_url'];
        
        // Step 4: Delete the image file if it exists
        if ($imagePath2 && file_exists($imagePath2)) {
            unlink($imagePath2);
        }
    }
    // Step 5: Retrieve the image path poter_url from the database
    $query3 = "SELECT poster_url FROM instansi WHERE id_instansi='$id'";
    $result3 = mysqli_query3($db->koneksi, $query3);
    
    if ($result3 && mysqli_num_rows($result3) > 0) {
        $data3 = mysqli_fetch_assoc($result3);
        $imagePath3 = $data3['poster_url'];
        
        // Step 6: Delete the image file if it exists
        if ($imagePath3 && file_exists($imagePath3)) {
            unlink($imagePath3);
        }
    }

    // Step 7: Delete the database row
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
