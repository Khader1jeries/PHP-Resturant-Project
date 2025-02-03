<?php

include "../config/phpdb.php";
include "Service/login_service.php";

$errorMessage = "";
$lockMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['signin'])) {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if (!empty($username) && !empty($password)) {
            $errorMessage = signIn($conn, $username, $password);
        } else {
            $errorMessage = "Please fill in all fields.";
        }
    }
    
    if (isset($_POST['forgotPassword'])) {
        $username = trim($_POST['username'] ?? '');
        
        if (!empty($username)) {
            $errorMessage = forgotPassword($conn, $username);
        } else {
            $errorMessage = "Please enter your username.";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css_files/sign_in.css" />
    <title>Sign In</title>
</head>
<body class="signin-page">
    <?php require 'includes/navbar.php'; ?>
    <div class="container" id="container">
        <h2>Sign In</h2>
        <?php if ($lockMessage): ?>
            <p style="color: red; text-align: center;"><?php echo $lockMessage; ?></p>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="signin" value="Sign In">
            </form>

            <form method="POST">
                <span class="forgot-link" onclick="togglePasswordForm()">Forgot Password?</span>
                <div id="passwordForm" style="display: none;">
                    <input type="text" name="username" placeholder="Enter your username" required>
                    <input type="submit" name="forgotPassword" value="Reset Password">
                </div>
            </form>
        <?php endif; ?>

        <div id="errorMessage" style="text-align: center; margin-top: 20px;">
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById('passwordForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
