<?php
session_start();
require '../HomePage/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../HomePage/login.php");
    exit();
}

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_date = $_POST['leave_date'];
    $reason = $_POST['reason'];
    $user_id = $_SESSION['user']['id']; // Use the user ID from session

    try {
        $stmt = $pdo->prepare("INSERT INTO leave_requests (user_id, leave_date, reason) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $leave_date, $reason]);
        $message = "Leave request submitted successfully.";
    } catch (PDOException $e) {
        $message = "Failed to submit leave request: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Leave </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style3.css">
</head>
<body>
<ul>
            <li><a href="user_dashboard.php">Home</a></li>
            <li><a href="see_message.php">See Message</a></li>
            <li><a class="active" href="request_leave.php">Request Leave</a></li>
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

    <h1>Request Leave</h1>
    <form method="POST" action="">
        <table class="table table-bordered">
            <tr>
                <th>
                    <label for="leave_date">Leave Date:</label>
                </th>
                <td>
                    <input type="date" name="leave_date" id="leave_date" required>
                </td>
                </tr>
                <tr>
                <th>
                    <label for="reason">Reason:</label>
                </th>
                <td>
                    <textarea name="reason" id="reason" rows="4" required></textarea>
                </td>
                </tr>
                <tr>
                 <td>
                </td>
                <td>   
                    <button type="submit">Submit</button>
                </td>
            </tr>
    </form>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
   
</div>

    

</body>
</html>

