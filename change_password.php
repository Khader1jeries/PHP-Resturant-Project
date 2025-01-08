<?php
session_start();
include "config/userAuthConfig.php";

// Check if user is logged in and using a temporary password
if (!isset($_SESSION['temp_password']) || !isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// Check if reset action is triggered
if (isset($_POST['resetPassword'])) {
    // Clear session and redirect to index.php
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session
    header('Location: index.php');  // Redirect to index.php
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST['newPassword'] ?? null;
    $confirmPassword = $_POST['confirmPassword'] ?? null;

    if ($newPassword && $confirmPassword) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ?, temp_password = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Clear the temporary password session flag
                unset($_SESSION['temp_password']);
                
                // JavaScript redirect after successful update
                echo "<script type='text/javascript'>
                        window.top.location.href = 'index.php';
                      </script>";
                exit();  // Exit to avoid further code execution
            } else {
                echo "<p style='color: red; text-align: center;'>Failed to update the password. Please try again.</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Passwords do not match. Please try again.</p>";
        }
    } else {
        echo "<p style='color: red; text-align: center;'>Please fill out all fields.</p>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_files/sign_in.css" />
    <title>Update Password</title>
</head>
<body>
<div class="container" id="container">
    <h2>Update Your Password</h2>
    <form method="POST">
        <input type="password" name="newPassword" placeholder="New Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <input type="submit" value="Update Password">
    </form>
</div>
</body>
</html>
