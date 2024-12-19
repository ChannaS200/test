<?php
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $emp_no = $_POST['emp_no'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if passwords match
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (name, emp_no, address, email, mobile, password, role, status) 
                               VALUES (?, ?, ?, ?, ?, ?, 'user', 'pending')");
        if ($stmt->execute([$name, $emp_no, $address, $email, $mobile, $hashed_password])) {
            $message = "Registration successful! Wait for admin approval.";
        } else {
            $message = "Registration failed!";
        }
    } else {
        $message = "Passwords do not match!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <?php if ($message): ?>
            <p class="<?= strpos($message, 'successful') ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="emp_no">Employee Number:</label>
            <input type="text" id="emp_no" name="emp_no" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <button type="submit">Sign Up</button>
            <br>
            <br>
            <a href="login.php">login</a>
        </form>
    </div>
</body>
</html>
