<?php
session_start();
require '../HomePage/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../HomePage/login.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>
<ul>
            <li><a class="active" href="user_dashboard.php">Home</a></li>
            <li><a href="see_message.php">See Message</a></li>
            <li><a href="request_leave.php">Request Leave</a></li>
            <li><a href="view_leaves.php">See Leaves</a></li>
            <li><a href="view_user_details.php">User Details</a></li>
            <li><a href="update_profile.php">update profile</a></li>
            
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
    <p>Use the navigation bar to See Message, Request Leave, and See Leaves.</p>
</div>

    

</body>
</html>
