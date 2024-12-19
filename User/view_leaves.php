<?php
session_start();
require '../HomePage/db.php';

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../HomePage/login.php");
    exit();
}

$user = $_SESSION['user'];

// Fetch all leave requests and their statuses
try {
    $stmt = $pdo->query("
        SELECT 
            u.name, 
            l.leave_date, 
            l.status 
        FROM leave_requests l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.leave_date
    ");
    $leaveRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching leave requests: " . $e->getMessage());
}

// Fetch messages for the logged-in user
try {
    $stmt = $pdo->prepare("
        SELECT 
            messages.message, 
            messages.created_at, 
            users.name AS sender_name 
        FROM messages 
        JOIN users ON messages.sender_id = users.id 
        WHERE messages.receiver_id = ?
    ");
    $stmt->execute([$user['id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching messages: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>See Leaves </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>
<ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="see_message.php">See Message</a></li>
            <li><a href="request_leave.php">Request Leave</a></li>
            <li><a class="active" href="view_leaves.php">See Leaves</a></li>
            <li><a href="view_user_details.php">User Details</a></li>
            <li><a href="update_profile.php">update profile</a></li>
            
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
<h2>Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>

    <h3>All Leave Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Leave Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($leaveRequests) > 0): ?>
                    <?php foreach ($leaveRequests as $leave): ?>
                        <tr>
                            <td><?= htmlspecialchars($leave['name']) ?></td>
                            <td><?= htmlspecialchars($leave['leave_date']) ?></td>
                            <td><?= htmlspecialchars($leave['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No leave requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
   
</div>

    

</body>
</html>
