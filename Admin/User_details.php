<?php 
session_start();
require '../HomePage/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../HomePage/login.php");
    exit();
}

$admin = $_SESSION['user'];

// Fetch approved users for messaging
$stmt = $pdo->prepare("SELECT id, name, emp_no, email, mobile FROM users WHERE role = 'user' AND status = 'approved'");
$stmt->execute();
$users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>

<ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="user_registration.php">Users Registration</a></li>
    <li><a href="send-message.php">Send Message</a></li>
    <li><a href="manage_leaves.php">Manage Leaves</a></li>
    <li><a href="user_delete.php">Remove Users</a></li>
    <li><a class="active" href="User_details.php">User Details</a></li>

</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">

    <!-- Display approved users in a table -->
    <h1>Approved Users</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Employee No</th>
                <th>Email</th>
                <th>Mobile</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['emp_no']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['mobile']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
            </table>

</div>

</body>
</html>
