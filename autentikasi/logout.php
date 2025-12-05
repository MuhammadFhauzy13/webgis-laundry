<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

session_start();
$_SESSION['flash_logout'] = true;

header("Location: ../landing-page/index.php");
exit();
