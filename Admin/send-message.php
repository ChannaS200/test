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

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        if ($receiver_id === 'all') {
            // Send message to all approved users
            foreach ($users as $user) {
                $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
                $stmt->execute([$admin['id'], $user['id'], $message]);
            }
            $_SESSION['message'] = "Message sent to all users successfully!";
        } else {
            // Send message to a single user
            $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$admin['id'], $receiver_id, $message]);
            $_SESSION['message'] = "Message sent successfully!";
        }
    } else {
        $_SESSION['message'] = "Message cannot be empty!";
    }
    header("Location: send-message.php");
    exit();
}
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
    <li><a class="active" href="send-message.php">Send Message</a></li>
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
    <h1>Send Message</h1>

    <!-- Message form -->
    <h2>Send a Message</h2>
<form method="post">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td><label for="receiver_id">Select User:</label></td>
                <td>
                    <select id="receiver_id" name="receiver_id" required>
                        <option value="" disabled selected>-- Choose a User --</option>
                        <option value="all">-- Select All Users --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="message">Message:</label></td>
                <td>
                    <textarea id="message" name="message" rows="5" cols="30" required></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit">Send Message</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>

</div>

</body>
</html>
