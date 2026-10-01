<?php
session_start();
session_destroy();
header("Location: /campus-equipment/auth/login.php");
exit;
?>