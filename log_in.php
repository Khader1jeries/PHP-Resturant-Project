<?php
session_start();
include "config/userAuthConfig.php"; // Include the database connection

// Initialize $lockMessage to avoid undefined variable warnings
$lockMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['signin'])) {
        // Handle Sign-In
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($username && $password) {
            // Query to fetch the user from the database
            $stmt = $conn->prepare("SELECT id, password, failed_attempts, temp_password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Lock the account if failed attempts >= 3
                if ($user['failed_attempts'] >= 3) {
                    // Set the password to NULL after 3 failed attempts
                    $stmtUpdate = $conn->prepare("UPDATE users SET password = NULL WHERE id = ?");
                    $stmtUpdate->bind_param("i", $user['id']);
                    $stmtUpdate->execute();

                    $lockMessage = "Your account is locked due to too many failed attempts. Please reset your password.";
                } else {
                    // Check if password matches the stored password (either the original password or temporary password)
                    if (
                        password_verify($password, $user['password']) ||  // Check for the original password
                        (isset($user['temp_password']) && password_verify($password, $user['temp_password']))  // Check for the temporary password
                    ) {
                        // If the temp password is used, set the temp password session flag
                        if (isset($user['temp_password']) && password_verify($password, $user['temp_password'])) {
                            $_SESSION['temp_password'] = true; // Temporary password flag
                        }

                        // Reset failed attempts
                        $resetStmt = $conn->prepare("UPDATE users SET failed_attempts = 0 WHERE id = ?");
                        $resetStmt->bind_param("i", $user['id']);
                        $resetStmt->execute();

                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $username;

                        // Redirect to home.php after successful login using window.top.location.href
                        // If user logged in with temp password, redirect to update password page
                        if (isset($_SESSION['temp_password'])) {
                            echo "<script>window.location.href = 'reset_password.php';</script>";
                        } else {
                            echo "<script>window.top.location.href = 'Client/index.php';</script>";
                        }
                        exit(); // Stop script execution to ensure redirect happens
                    } else {
                        // Increment failed attempts on invalid login
                        $incrementStmt = $conn->prepare("UPDATE users SET failed_attempts = failed_attempts + 1 WHERE id = ?");
                        $incrementStmt->bind_param("i", $user['id']);
                        $incrementStmt->execute();

                        echo "<p style='color: red; text-align: center;'>Invalid password. Please try again.</p>";
                    }
                }
            } else {
                echo "<p style='color: red; text-align: center;'>No user found with the username provided.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red; text-align: center;'>Please enter both username and password.</p>";
        }
    } elseif (isset($_POST['forgotPassword'])) {
        // Handle Forgot Password
        $username = $_POST['username'] ?? null;

        if ($username) {
            // Query to fetch the user's email from the database
            $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $userEmail = $user['email'];
        
                // Generate a random temporary password
                $tempPassword = bin2hex(random_bytes(4)); // 8-character random string
                $hashedTempPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
        
                // Update the user's temporary password
                $updateStmt = $conn->prepare("UPDATE users SET password = NULL, temp_password = ?, failed_attempts = 0 WHERE username = ?");
                $updateStmt->bind_param("ss", $hashedTempPassword, $username);
                $updateStmt->execute();
        
                if ($updateStmt->affected_rows > 0) {
                    // Generate the reset password link
                    $resetLink = "http://localhost/labs/PHP-Resturant-Project/PHP-Resturant-Project/change_password.php?username=" . urlencode($username);
        
                    // Send the reset password link via email
                    $to = $userEmail;
                    $subject = "Reset Your Password";
                    $message = "Hello,\n\nA password reset has been requested for your account. Use the link below to reset your password:\n\n$resetLink\n\nIf you did not request this, please ignore this email.";
                    $headers = "From: noreply@yourdomain.com";
        
                    if (mail($to, $subject, $message, $headers)) {
                        echo "<p style='color: green; text-align: center;'>A temporary password has been sent to your email.</p>";
                    } else {
                        echo "<p style='color: red; text-align: center;'>Failed to send the reset email. Please try again later.</p>";
                    }
                } else {
                    echo "<p style='color: red; text-align: center;'>Failed to generate a temporary password. Please try again later.</p>";
                }
                $updateStmt->close();
            } else {
                echo "<p style='color: red; text-align: center;'>No user found with the username provided.</p>";
            }
            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/sign_in.css" />
    <title>Sign In</title>
</head>
<body>
    <?php if ($lockMessage): ?>
        <div style="text-align:center; margin-top:20%; color:red;">
            <h1><?php echo $lockMessage; ?></h1>
            <button onclick="window.location.href='?reset=true'">Reset and Start Over</button>
        </div>
    <?php else: ?>
        <div class="container" id="container">
            <h2 style="color: black">Sign In</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="text" id="username" name="username" required placeholder="Username" />
                <input type="password" id="password" name="password" required placeholder="Password" />
                <input type="submit" name="signin" value="Sign In" />
            </form>

            <!-- Forgot Password Section -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="margin-top: 20px;">
                <!-- Default "Forgot your password?" text -->
                <span id="forgotPasswordText" onclick="toggleForgotPasswordForm()" style="color: black; cursor: pointer;">Forgot your password?</span>

                <!-- Hidden form (initially hidden) to input username and reset password -->
                <div id="forgotPasswordForm" style="display: none;">
                    <br />
                    <label for="forgotUsername" style="color: black;">Enter your username:</label>
                    <input type="text" id="forgotUsername" name="username" required placeholder="Username" />
                    <input type="submit" name="forgotPassword" value="Reset Password" />
                </div>
            </form>
            <script src="scripts/log_in.js"></script>
        </div>
    <?php endif; ?>
</body>
</html>
