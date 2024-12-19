<?php 
session_start();
require '../HomePage/db.php';

// Ensure the admin is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../HomePage/login.php");
    exit();
}


// Fetch active users for removal
$activeStmt = $pdo->prepare("SELECT id, name, emp_no, email, mobile FROM users WHERE status = 'approved'");
$activeStmt->execute();
$activeUsers = $activeStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'], $_POST['action'])) {
        $user_id = (int)$_POST['user_id'];
        $action = $_POST['action'];
        if ($action === 'remove') {
    // Prevent deletion of the admin with Employee No 'EMP0001'
    $empStmt = $pdo->prepare("SELECT emp_no FROM users WHERE id = ?");
    $empStmt->execute([$user_id]);
    $empNo = $empStmt->fetchColumn();

    if ($empNo === 'EMP0001') {
        $_SESSION['message'] = "Admin user cannot be removed!";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $_SESSION['message'] = "User removed successfully!";
    }
}
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../CSS/style3.css">
    <script>
        // JavaScript function to confirm removal
        function confirmRemoval(event, userName) {
            event.preventDefault(); // Prevent form submission
            if (confirm(`Are you sure you want to remove ${userName}?`)) {
                event.target.closest('form').submit(); // Submit the form if confirmed
            }
        }
    </script>
</head>
<body>

<ul>
    <li><a href="admin_dashboard.php">Dashboard</a></li>
    <li><a href="user_registration.php">Users Registration</a></li>
    <li><a href="send-message.php">Send Message</a></li>
    <li><a href="manage_leaves.php">Manage Leaves</a></li>
    <li><a class="active" href="user_delete.php">Remove Users</a></li>
    <li><a href="User_details.php">User Details</a></li>
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
    <h2>Remove Users</h2>
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
        <?php foreach ($activeUsers as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['emp_no']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['mobile']) ?></td>
                <td>
                    <?php if ($user['emp_no'] === 'EMP0001'): ?>
                        <button class="btn btn-danger btn-sm" disabled>Cannot Remove</button>
                    <?php else: ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                            <button type="submit" name="action" value="remove" class="btn btn-success btn-sm"
                                onclick="confirmRemoval(event, '<?= htmlspecialchars($user['name']) ?>')">
                                Remove
                            </button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
