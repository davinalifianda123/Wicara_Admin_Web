<?php
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Regenerasi ID session untuk keamanan tambahan
session_regenerate_id(true);

// Redirect ke halaman login
header("Location: ../../Wicara_User_Web/index.php");
exit();
?>
