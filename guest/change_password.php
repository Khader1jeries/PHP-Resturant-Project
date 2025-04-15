<?php

include "../config/phpdb.php";
include "Service/change_password_service.php";

// Initialize messages
$errorMessage = "";
$successMessage = "";

// Validate temp password
validateTempPassword();

// Get username
$username = getUsername();
if (!$username) {
    $errorMessage = "Invalid access. Redirecting...";
    echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
    exit();
}

// Check user type
$table = checkUserType($conn, $username);
if (!$table) {
    $errorMessage = "Invalid user. Redirecting...";
    echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
    exit();
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($newPassword !== $confirmPassword) {
        $errorMessage = "Passwords don't match.";
    } elseif (strlen($newPassword) < 8) {
        $errorMessage = "Password must be at least 8 characters.";
    } else {
        $successMessage = updatePassword($conn, $username, $newPassword, $table);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_files/changepassword.css">
    <title>Change Password</title>
</head>
<body>
    <div class="container">
        <h2>Change Password for <?php echo htmlspecialchars($username); ?></h2>

        <?php if ($errorMessage): ?>
            <p style="color: red; text-align: center;"> <?php echo $errorMessage; ?> </p>
        <?php elseif ($successMessage): ?>
            <p style="color: green; text-align: center;"> <?php echo $successMessage; ?> </p>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password (min 8 characters)" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
