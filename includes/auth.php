<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /campus-equipment/auth/login.php");
    exit;
}
function require_staff() {
    if ($_SESSION['role'] != 'staff') {
        header("Location: /campus-equipment/index.php");
        exit;
    }
}
?>