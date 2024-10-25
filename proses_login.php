<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "wicara";
    $koneksi;

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error); 

    }

    $username = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email ='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
    // Mulai session dan simpan data pengguna
        session_start();
        $_SESSION["email"] = $username;
        header("Location: Dashboard.php");
    } else {
        echo $err;
    }

    $conn->close();
?>