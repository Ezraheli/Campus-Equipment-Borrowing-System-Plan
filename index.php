<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'staff') {
        header("Location: /campus-equipment/staff/dashboard.php");
    } else {
        header("Location: /campus-equipment/student/dashboard.php");
    }
} else {
    header("Location: /campus-equipment/auth/login.php");
}
exit;
?>