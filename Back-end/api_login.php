<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";  // atau IP dari server database Anda
$username = "root";         // ganti dengan username database
$password = "";             // ganti dengan password database
$dbname = "wicara";         // ganti dengan nama database

$conn = new mysqli($servername, $username, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Menambahkan pengecekan role = 1
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND password = ? AND role = 1");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => true, "message" => "Login berhasil"]);
    } else {
        echo json_encode(["success" => false, "message" => "Email, password, atau role tidak sesuai"]);
    }

    $stmt->close();
}

$conn->close();
?>
