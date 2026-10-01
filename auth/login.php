<?php
require_once '../config/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /campus-equipment/index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $password == $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header("Location: /campus-equipment/index.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<h2>Login</h2>
<?php if ($error): ?>
    <div class="alert error"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" action="">
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
<p>Demo: staff@example.com / staff123 | student@example.com / student123</p>
<?php include '../includes/footer.php'; ?>