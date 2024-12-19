<?php
session_start();
require '../HomePage/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../HomePage/login.php");
    exit();
}

// Handle leave request action (Accept/Reject)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $response_message = $_POST['response_message'];

    try {
        $stmt = $pdo->prepare("UPDATE leave_requests SET status = ?, response_message = ? WHERE id = ?");
        $stmt->execute([$action, $response_message, $request_id]);
    } catch (PDOException $e) {
        die("Error updating leave request: " . $e->getMessage());
    }
}

// Fetch all leave requests with status 'Pending'
try {
    $stmt = $pdo->query("
        SELECT l.id, u.name, l.leave_date, l.reason, l.status 
        FROM leave_requests l
        JOIN users u ON l.user_id = u.id
        WHERE l.status = 'Pending'
    ");
    $leaveRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching leave requests: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>

<ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="user_registration.php">Users Registration</a></li>
    <li><a href="send-message.php">Send Message</a></li>
    <li><a class="active" href="manage_leaves.php">Manage Leaves</a></li>
    <li><a href="user_delete.php">Remove Users</a></li>
    <li><a href="User_details.php">User Details</a></li>
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
    <h1>Manage Leave Requests</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Leave Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($leaveRequests) > 0): ?>
                <?php foreach ($leaveRequests as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['leave_date']) ?></td>
                        <td><?= htmlspecialchars($row['reason']) ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="Accepted">
                                <input type="text" name="response_message" placeholder="Response message" required>
                                <button type="submit">Accept</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="action" value="Rejected">
                                <input type="text" name="response_message" placeholder="Response message" required>
                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No pending leave requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
