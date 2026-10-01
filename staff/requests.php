<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_staff();
include '../includes/header.php';

$error = '';
$success = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    if ($action == 'approve') {
        $stmt = $pdo->prepare("SELECT * FROM borrowings WHERE id = ? AND status = 'pending'");
        $stmt->execute([$id]);
        $b = $stmt->fetch();
        if ($b) {
            $stmt = $pdo->prepare("SELECT available FROM equipment WHERE id = ?");
            $stmt->execute([$b['equipment_id']]);
            $avail = $stmt->fetchColumn();
            if ($avail >= $b['quantity']) {
                $pdo->beginTransaction();
                try {
                    $pdo->prepare("UPDATE borrowings SET status = 'borrowed' WHERE id = ?")->execute([$id]);
                    $pdo->prepare("UPDATE equipment SET available = available - ? WHERE id = ?")->execute([$b['quantity'], $b['equipment_id']]);
                    $pdo->commit();
                    $success = "Request approved and marked as borrowed.";
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = "Failed to approve request.";
                }
            } else {
                $error = "Not enough available quantity.";
            }
        }
    } elseif ($action == 'reject') {
        $stmt = $pdo->prepare("UPDATE borrowings SET status = 'rejected' WHERE id = ? AND status = 'pending'");
        if ($stmt->execute([$id])) {
            $success = "Request rejected.";
        } else {
            $error = "Failed to reject request.";
        }
    }
}

$requests = $pdo->query("SELECT b.*, u.name AS user_name, e.name AS equipment_name FROM borrowings b JOIN users u ON b.user_id = u.id JOIN equipment e ON b.equipment_id = e.id WHERE b.status = 'pending' ORDER BY b.id ASC")->fetchAll();
?>
<h2>Pending Borrow Requests</h2>
<?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
<table>
    <tr><th>User</th><th>Equipment</th><th>Qty</th><th>Borrow Date</th><th>Due Date</th><th>Action</th></tr>
    <?php foreach ($requests as $r): ?>
    <tr>
        <td><?php echo htmlspecialchars($r['user_name']); ?></td>
        <td><?php echo htmlspecialchars($r['equipment_name']); ?></td>
        <td><?php echo $r['quantity']; ?></td>
        <td><?php echo $r['borrow_date']; ?></td>
        <td><?php echo $r['due_date']; ?></td>
        <td>
            <a href="?action=approve&id=<?php echo $r['id']; ?>" class="btn success" onclick="return confirm('Approve this request?')">Approve</a>
            <a href="?action=reject&id=<?php echo $r['id']; ?>" class="btn danger" onclick="return confirm('Reject this request?')">Reject</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>