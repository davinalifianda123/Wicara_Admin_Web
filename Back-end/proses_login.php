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
        // Ambil data pengguna
        $user = $result->fetch_assoc();

        // Mulai session dan simpan data pengguna
        session_start();
        $_SESSION["email"] = $username;
        $_SESSION["role"] = $user['role'];
        $_SESSION['id_user'] = $user['id_user'];

        if (isset($_POST['remember'])) {
            setcookie("email", $username, time() + (15 * 24 * 60 * 60), "/"); // 15 hari
            setcookie("password", $password, time() + (15 * 24 * 60 * 60), "/"); // 15 hari
        }

         // Arahkan berdasarkan role
        if ($user['role'] == 1) {
            // Role admin
            header("Location: ../Dashboard.php");
        } else {
            // Role selain admin (misalnya dosen atau mahasiswa)
            header("Location: ../user.php");
        }

    } else {
        header("Location: ../login.php?login_failed=1");
    }

    $conn->close();
?>
