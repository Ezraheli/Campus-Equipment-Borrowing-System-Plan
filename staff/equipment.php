<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
require_staff();
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $quantity = (int)$_POST['quantity'];
    $description = trim($_POST['description']);

    if (empty($name) || $quantity < 1) {
        $error = "Name and valid quantity are required.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO equipment (name, category, quantity, available, description) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $category, $quantity, $quantity, $description])) {
            $success = "Equipment added.";
        } else {
            $error = "Failed to add equipment.";
        }
    }
}

$equipment = $pdo->query("SELECT * FROM equipment ORDER BY id DESC")->fetchAll();
?>
<h2>Manage Equipment</h2>
<?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
<h3>Add New Equipment</h3>
<form method="POST">
    <input type="hidden" name="add" value="1">
    <label>Name:</label><input type="text" name="name" required>
    <label>Category:</label><input type="text" name="category">
    <label>Quantity:</label><input type="number" name="quantity" min="1" required>
    <label>Description:</label><textarea name="description"></textarea>
    <button type="submit">Add Equipment</button>
</form>
<h3>Equipment List</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Category</th><th>Total</th><th>Available</th><th>Description</th></tr>
    <?php foreach ($equipment as $e): ?>
    <tr>
        <td><?php echo $e['id']; ?></td>
        <td><?php echo htmlspecialchars($e['name']); ?></td>
        <td><?php echo htmlspecialchars($e['category']); ?></td>
        <td><?php echo $e['quantity']; ?></td>
        <td><?php echo $e['available']; ?></td>
        <td><?php echo htmlspecialchars($e['description']); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>