<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "wicara";

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

        if (isset($_POST['remember'])) {
            setcookie("email", $username, time() + (15 * 24 * 60 * 60), "/"); // 15 hari
            setcookie("password", $password, time() + (15 * 24 * 60 * 60), "/"); // 15 hari
        }

        header("Location: Dashboard.php");
    } else {
        header("Location: login.php?login_failed=1");
    }

    $conn->close();
?>
