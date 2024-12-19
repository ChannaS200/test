<?php
session_start();
require 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] !== 'approved' && $user['role'] === 'user') {
            $message = "Your account is not approved yet.";
        } else {
            $_SESSION['user'] = $user;
            header("Location: " . ($user['role'] === 'admin' ? '../Admin/admin_dashboard.php' : '../User/user_dashboard.php'));
            exit();
        }
    } else {
        $message = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
            <br>
            <br>
            <a href="signup.php">Signup</a>
        </form>
    </div>
</body>
</html>
