<?php
session_start();
require '../HomePage/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../HomePage/login.php");
    exit();
}

$user = $_SESSION['user'];
$message = '';

// Fetch user details
$stmt = $pdo->prepare("SELECT id, name, emp_no, address, email, mobile FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$userDetails = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    // Update user details in the database
    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, address = ?, email = ?, mobile = ? WHERE id = ?");
    if ($updateStmt->execute([$name, $address, $email, $mobile, $user['id']])) {
        $message = "Profile updated successfully!";
        // Update session with new user details
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['mobile'] = $mobile;
    } else {
        $message = "Failed to update profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>

<ul>
    <li><a href="user_dashboard.php">Home</a></li>
    <li><a href="see_message.php">See Message</a></li>
    <li><a href="request_leave.php">Request Leave</a></li>
    <li><a href="view_leaves.php">See Leaves</a></li>
    <li><a href="view_user_details.php">User Details</a></li>
    <li><a class="active" href="update_profile.php">Update Profile</a></li>
</ul>

<div class="top-bar">
    <form method="POST" action="../HomePage/logout.php">
        <button type="submit" class="logout-button">Logout</button>
    </form>
</div>

<div class="content" style="margin-top: 70px;">
    <h2>Update Profile</h2>
    <?php if ($message): ?>
        <p class="<?= strpos($message, 'successfully') ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post">
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>
                    <label for="name">Name:</label>
                </th>
                <td>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($userDetails['name']) ?>" required>
                </td>
            </tr>
            <tr>
                <th>
                <label for="address">Address:</label>
                </th>
                <td>
                    <input type="text" id="address" name="address" value="<?= htmlspecialchars($userDetails['address']) ?>" required>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="email">Email:</label>
                </th>
                <td>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userDetails['email']) ?>" required>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="mobile">Mobile:</label>
                </th>
                <td>
                    <input type="text" id="mobile" name="mobile" value="<?= htmlspecialchars($userDetails['mobile']) ?>" required>
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td>
                <button type="submit">Update Profile</button>
                </td>
            </tr>
    </tbody>
        </table>
          </form>
</div>

</body>
</html>
