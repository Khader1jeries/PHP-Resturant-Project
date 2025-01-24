<?php
session_start();
include "../config/userAuthConfig.php"; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['username'])) {
    $username = $_GET['username'];

    // Validate the username against the database
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>Invalid or non-existing username.</p>";
        exit();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate passwords
    if ($newPassword !== $confirmPassword) {
        echo "<p style='color: red;'>Passwords do not match. Please try again.</p>";
    } elseif (strlen($newPassword) < 8) {
        echo "<p style='color: red;'>Password must be at least 8 characters long.</p>";
    } else {
        // Hash the new password and update it in the database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, temp_password = NULL WHERE username = ?");
        $updateStmt->bind_param("ss", $hashedPassword, $username);

        if ($updateStmt->execute()) {
            // Redirect to index.php after successful update
            echo "<script>alert('Password updated successfully! Redirecting to home page.'); window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "<p style='color: red;'>Failed to update the password. Please try again later.</p>";
        }
        $updateStmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css_files/sign_in.css" />
    <style>
    </style>
    <title>Update Password</title>
</head>
<body>
<div class="container" id="container">
    <h2>Update Your Password</h2>
    <form action="change_password.php" method="POST">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" />
        <input type="password" name="new_password" placeholder="New Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <button type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
