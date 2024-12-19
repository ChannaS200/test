<?php
session_start();
require '../HomePage/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../HomePage/login.php");
    exit();
}

$user = $_SESSION['user'];

// Fetch messages sent to the current user
$stmt = $pdo->prepare("SELECT m.message, m.created_at, u.name AS sender_name 
                       FROM messages m
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.receiver_id = ?");
$stmt->execute([$user['id']]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>See Message </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>
<ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a class="active" href="see_message.php">See Message</a></li>
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

        <h2>Messages for <?= htmlspecialchars($user['name']) ?></h2>
        <?php if (count($messages) > 0): ?>
            <ul>
                <?php foreach ($messages as $msg): ?>
                    <li>
                        <strong>From:</strong> <?= htmlspecialchars($msg['sender_name']) ?><br>
                        <strong>Message:</strong> <?= htmlspecialchars($msg['message']) ?><br>
                        <small><em>Received on: <?= htmlspecialchars($msg['created_at']) ?></em></small>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No messages yet!</p>
        <?php endif; ?>
   
</div>

    

</body>
</html>
