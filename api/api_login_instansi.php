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

    $stmt = $conn->prepare("SELECT * FROM instansi WHERE email_pic = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "message" => "Login berhasil",
            "id_instansi" => $user['id_instansi'],
            "nama_instansi" => $user['nama_instansi'],
            "email_pic" => $user['email_pic'],
            "password" => $user['password'],
            "gambar_instansi" => $user['gambar_instansi']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Email, password, atau role tidak sesuai"]);
    }

    $stmt->close();
}

$conn->close();
