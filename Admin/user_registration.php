<?php 
session_start();
require '../HomePage/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../HomePage/login.php");
    exit();
}

// Fetch pending users for approval
$stmt = $pdo->prepare("SELECT id, name, emp_no, email, mobile FROM users WHERE status = 'pending'");
$stmt->execute();
$pendingUsers = $stmt->fetchAll();

// Handle user approval or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $user_id = (int)$_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        $stmt = $pdo->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['message'] = "User approved successfully!";
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['message'] = "User rejected successfully!";
    }
    header("Location: user_registration.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>

<ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a class="active" href="user_registration.php">Users Registration</a></li>
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
    <h1>User Registration</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Employee No</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pendingUsers as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['emp_no']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['mobile']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
