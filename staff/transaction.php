<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_staff();
include '../includes/header.php';

$error = '';
$success = '';

if (isset($_GET['return_id'])) {
    $id = (int)$_GET['return_id'];
    $stmt = $pdo->prepare("SELECT * FROM borrowings WHERE id = ? AND status = 'borrowed'");
    $stmt->execute([$id]);
    $b = $stmt->fetch();
    if ($b) {
        $pdo->beginTransaction();
        try {
            $pdo->prepare("UPDATE borrowings SET status = 'returned', return_date = CURDATE() WHERE id = ?")->execute([$id]);
            $pdo->prepare("UPDATE equipment SET available = available + ? WHERE id = ?")->execute([$b['quantity'], $b['equipment_id']]);
            $pdo->commit();
            $success = "Item returned successfully.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to process return.";
        }
    } else {
        $error = "Borrowing record not found or already returned.";
    }
}

$transactions = $pdo->query("SELECT b.*, u.name AS user_name, e.name AS equipment_name FROM borrowings b JOIN users u ON b.user_id = u.id JOIN equipment e ON b.equipment_id = e.id WHERE b.status IN ('borrowed', 'returned') ORDER BY b.id DESC")->fetchAll();
?>
<h2>Borrow/Return Transactions</h2>
<?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
<table>
    <tr><th>User</th><th>Equipment</th><th>Qty</th><th>Borrow Date</th><th>Due Date</th><th>Status</th><th>Return Date</th><th>Action</th></tr>
    <?php foreach ($transactions as $t): ?>
    <tr>
        <td><?php echo htmlspecialchars($t['user_name']); ?></td>
        <td><?php echo htmlspecialchars($t['equipment_name']); ?></td>
        <td><?php echo $t['quantity']; ?></td>
        <td><?php echo $t['borrow_date']; ?></td>
        <td><?php echo $t['due_date']; ?></td>
        <td><?php echo $t['status']; ?></td>
        <td><?php echo $t['return_date'] ? $t['return_date'] : '—'; ?></td>
        <td>
            <?php if ($t['status'] == 'borrowed'): ?>
                <a href="?return_id=<?php echo $t['id']; ?>" class="btn" onclick="return confirm('Mark as returned?')">Return</a>
            <?php else: ?>
                —
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>