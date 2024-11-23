<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "wicara";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Unable to connect to the database. Please try again later.");
}

$email = $_POST['email'];
$password = $_POST['password'];

// Query ke tabel user
$stmt_user = $conn->prepare("SELECT * FROM user WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();

    // Bandingkan password langsung (plaintext)
    if ($password === $user['password']) {
        session_start();
        $_SESSION["email"] = $user['email'];
        $_SESSION["role"] = $user['role'];
        $_SESSION['id_user'] = $user['id_user'];

        if (isset($_POST['remember'])) {
            setcookie("auth_token", bin2hex(random_bytes(16)), time() + (15 * 24 * 60 * 60), "/");
        }

        // Arahkan berdasarkan role
        if ($user['role'] == 1) {
            header("Location: ../Dashboard.php");
        } else {
            header("Location: ../user.php");
        }
        exit();
    }
}

// Jika tidak ditemukan di tabel user, cek tabel instansi
$stmt_instansi = $conn->prepare("SELECT * FROM instansi WHERE email_pic = ?");
$stmt_instansi->bind_param("s", $email);
$stmt_instansi->execute();
$result_instansi = $stmt_instansi->get_result();

if ($result_instansi->num_rows > 0) {
    $instansi = $result_instansi->fetch_assoc();

    // Bandingkan password langsung (plaintext)
    if ($password === $instansi['password']) {
        session_start();
        $_SESSION["email"] = $instansi['email_pic'];
        $_SESSION['id_instansi'] = $instansi['id_instansi'];

        if (isset($_POST['remember'])) {
            setcookie("auth_token", bin2hex(random_bytes(16)), time() + (15 * 24 * 60 * 60), "/");
        }

        // Arahkan ke halaman admin PIC
        header("Location: ../admin_pic.php");
        exit();
    }
}

// Jika login gagal
header("Location: ../login.php?login_failed=1");
$conn->close();
?>
