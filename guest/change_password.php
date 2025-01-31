<?php
session_start();
include "../config/phpdb.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables for error messages and success messages
$errorMessage = "";
$successMessage = "";

// Validate temp password flow
if (!isset($_SESSION['temp_password'])) {
    header("Location: log_in.php");
    exit();
}

// Get username from URL or session
$username = $_GET['username'] ?? ($_SESSION['reset_user'] ?? null);

if (!$username) {
    $errorMessage = "Invalid access. Redirecting...";
    echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
    exit();
}

// Check if user is client or admin
$isAdmin = false;
$stmt = $conn->prepare("SELECT id FROM clientusers WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt = $conn->prepare("SELECT id FROM adminusers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $isAdmin = true;
}

if ($result->num_rows === 0) {
    $errorMessage = "Invalid user. Redirecting...";
    echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
    exit();
}

// Password Update Handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validations
    if ($newPassword !== $confirmPassword) {
        $errorMessage = "Passwords don't match.";
    } elseif (strlen($newPassword) < 8) {
        $errorMessage = "Password must be at least 8 characters.";
    } else {
        // Determine user table
        $table = $isAdmin ? 'adminusers' : 'clientusers';

        // Retrieve current password and previous passwords
        $stmt = $conn->prepare("SELECT password, password_1, password_2, password_3 FROM $table WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $previousPasswords = array($row['password_1'], $row['password_2'], $row['password_3']);

            // Check against previous passwords
            if (in_array($newPassword, $previousPasswords)) {
                $errorMessage = "You have used this password before. Please choose another one.";
            } else {
                // Prepare new password history values
                $currentPassword = $row['password'];
                $new_password_1 = $currentPassword;
                $new_password_2 = $row['password_1'];
                $new_password_3 = $row['password_2'];

                // Update password and password history
                $updateStmt = $conn->prepare("UPDATE $table SET password = ?, password_1 = ?, password_2 = ?, password_3 = ?, temp_password = NULL, failed_attempts = 0 WHERE username = ?");
                $updateStmt->bind_param("sssss", $newPassword, $new_password_1, $new_password_2, $new_password_3, $username);

                if ($updateStmt->execute()) {
                    $successMessage = "Password updated successfully! Redirecting...";
                    unset($_SESSION['temp_password']);
                    echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
                } else {
                    $errorMessage = "Error updating password. Please try again.";
                }
            }
        } else {
            $errorMessage = "Error retrieving user data.";
        }
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
