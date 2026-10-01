<?php
require_once '../includes/auth.php';
require_staff();
include '../includes/header.php';

require_once '../config/db.php';
$pending = $pdo->query("SELECT COUNT(*) FROM borrowings WHERE status = 'pending'")->fetchColumn();
$borrowed = $pdo->query("SELECT COUNT(*) FROM borrowings WHERE status = 'borrowed'")->fetchColumn();
$available = $pdo->query("SELECT SUM(available) FROM equipment")->fetchColumn();
?>
<h2>Staff Dashboard</h2>
<div class="cards">
    <div class="card">Pending Requests: <?php echo $pending; ?></div>
    <div class="card">Currently Borrowed: <?php echo $borrowed; ?></div>
    <div class="card">Total Available Items: <?php echo $available; ?></div>
</div>
<ul>
    <li><a href="equipment.php">Manage Equipment</a></li>
    <li><a href="requests.php">Manage Requests</a></li>
    <li><a href="transactions.php">Borrow/Return Transactions</a></li>
</ul>
<?php include '../includes/footer.php'; ?>