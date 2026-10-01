<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM equipment WHERE available > 0");
$equipment = $stmt->fetchAll();
?>
<h2>Available Equipment</h2>
<table>
    <tr>
        <th>Name</th><th>Category</th><th>Available</th><th>Description</th><th>Action</th>
    </tr>
    <?php foreach ($equipment as $item): ?>
    <tr>
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td><?php echo htmlspecialchars($item['category']); ?></td>
        <td><?php echo $item['available']; ?></td>
        <td><?php echo htmlspecialchars($item['description']); ?></td>
        <td><a href="request.php?id=<?php echo $item['id']; ?>" class="btn">Borrow</a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>