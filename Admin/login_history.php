<?php
session_start();
include "../config/phpdb.php";

// Get the username from the URL
$username = $_GET['username'] ?? null;

if (!$username) {
    echo "<p style='color: red; text-align: center;'>No username provided.</p>";
    exit();
}

// Fetch login history from both tables
$history = [];

// Query for client login history
$query = "SELECT date, success FROM client_login_history WHERE username = '$username' ORDER BY date DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $row['user_type'] = 'Client';
    $history[] = $row;
}

// Query for admin login history
$query = "SELECT date, success FROM admin_login_history WHERE username = '$username' ORDER BY date DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $row['user_type'] = 'Admin';
    $history[] = $row;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/userList.css" />
    <title>Login History - <?php echo htmlspecialchars($username); ?></title>
</head>
<body>
    <div class="container">
        <?php require 'navbar.php'; ?>
        
        <h3>Login History for <?php echo htmlspecialchars($username); ?></h3>

        <?php if (!empty($history)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Success</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['date']); ?></td>
                            <td><?php echo ($log['success'] == 1) ? "<span style='color: green;'>Success</span>" : "<span style='color: red;'>Not Success</span>"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No login history found for this user.</p>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="userList.php">
                <button style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Back to User List</button>
            </a>
        </div>
    </div>
</body>
</html>
