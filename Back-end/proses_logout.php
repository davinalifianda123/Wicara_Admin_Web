<?php
    // Hapus cookie email dan password dengan mengatur waktu kedaluwarsa ke masa lalu
    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");

    // Redirect ke halaman login setelah cookie dihapus
    header("Location: ../login.php");
    exit();
?>
