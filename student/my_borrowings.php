<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';

$stmt = $pdo->prepare("SELECT b.*, e.name AS equipment_name FROM borrowings b JOIN equipment e ON b.equipment_id = e.id WHERE b.user_id = ? ORDER BY b.id DESC");
$stmt->execute([$_SESSION['user_id']]);
$borrowings = $stmt->fetchAll();
?>
<h2>My Borrowings</h2>
<table>
    <tr>
        <th>Equipment</th><th>Qty</th><th>Borrow Date</th><th>Due Date</th><th>Status</th><th>Return Date</th>
    </tr>
    <?php foreach ($borrowings as $b): ?>
    <tr>
        <td><?php echo htmlspecialchars($b['equipment_name']); ?></td>
        <td><?php echo $b['quantity']; ?></td>
        <td><?php echo $b['borrow_date']; ?></td>
        <td><?php echo $b['due_date']; ?></td>
        <td><?php echo $b['status']; ?></td>
        <td><?php echo $b['return_date'] ? $b['return_date'] : '—'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>