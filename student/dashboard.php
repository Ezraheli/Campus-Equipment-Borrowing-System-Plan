<?php
require_once '../includes/auth.php';
include '../includes/header.php';
?>
<h2>Student/Faculty Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
<ul>
    <li><a href="equipment.php">View Available Equipment</a></li>
    <li><a href="my_borrowings.php">My Borrowings</a></li>
</ul>
<?php include '../includes/footer.php'; ?>