<?php
session_start();
require '../HomePage/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../HomePage/login.php");
    exit();
}

$admin = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>

<ul>
    <li><a class="active" href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="user_registration.php">Users Registration</a></li>
    <li><a href="send-message.php">Send Message</a></li>
    <li><a href="manage_leaves.php">Manage Leaves</a></li>
    <li><a href="user_delete.php">Remove Users</a></li>
    <li><a href="User_details.php">User Details</a></li>
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Use the navigation bar to Users Registration, Send Message, and Manage Leaves.</p>
</div>

</body>
</html>