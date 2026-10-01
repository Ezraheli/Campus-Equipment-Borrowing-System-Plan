<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Equipment Borrowing</title>
    <link rel="stylesheet" href="/campus-equipment/assets/css/style.css">
</head>
<body>
<nav>
    <div class="nav-container">
        <a href="/campus-equipment/index.php" class="logo">Campus Equip</a>
        <ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] == 'staff'): ?>
                    <li><a href="/campus-equipment/staff/dashboard.php">Dashboard</a></li>
                    <li><a href="/campus-equipment/staff/equipment.php">Equipment</a></li>
                    <li><a href="/campus-equipment/staff/requests.php">Requests</a></li>
                    <li><a href="/campus-equipment/staff/transactions.php">Transactions</a></li>
                <?php else: ?>
                    <li><a href="/campus-equipment/student/dashboard.php">Dashboard</a></li>
                    <li><a href="/campus-equipment/student/equipment.php">Equipment</a></li>
                    <li><a href="/campus-equipment/student/my_borrowings.php">My Borrowings</a></li>
                <?php endif; ?>
                <li><a href="/campus-equipment/auth/logout.php">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a></li>
            <?php else: ?>
                <li><a href="/campus-equipment/auth/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main class="container">