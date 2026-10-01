<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM equipment WHERE id = ?");
$stmt->execute([$id]);
$equip = $stmt->fetch();

if (!$equip) {
    echo "<div class='alert error'>Equipment not found.</div>";
    include '../includes/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = (int)$_POST['quantity'];
    $borrow_date = $_POST['borrow_date'];
    $due_date = $_POST['due_date'];
    $purpose = trim($_POST['purpose']);

    if ($quantity < 1 || $quantity > $equip['available']) {
        $error = "Quantity must be between 1 and {$equip['available']}.";
    } elseif (empty($borrow_date) || empty($due_date)) {
        $error = "Please select borrow and due dates.";
    } elseif (strtotime($due_date) <= strtotime($borrow_date)) {
        $error = "Due date must be after borrow date.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, equipment_id, quantity, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        if ($stmt->execute([$_SESSION['user_id'], $id, $quantity, $borrow_date, $due_date])) {
            $success = "Borrow request submitted! Waiting for staff approval.";
        } else {
            $error = "Failed to submit request.";
        }
    }
}
?>
<h2>Borrow Equipment: <?php echo htmlspecialchars($equip['name']); ?></h2>
<?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
<form method="POST">
    <input type="hidden" name="equipment_id" value="<?php echo $id; ?>">
    <label>Quantity (max <?php echo $equip['available']; ?>):</label>
    <input type="number" name="quantity" min="1" max="<?php echo $equip['available']; ?>" required>
    <label>Borrow Date:</label>
    <input type="date" name="borrow_date" required>
    <label>Due Date:</label>
    <input type="date" name="due_date" required>
    <label>Purpose:</label>
    <textarea name="purpose" required></textarea>
    <button type="submit">Submit Request</button>
</form>
<?php include '../includes/footer.php'; ?>