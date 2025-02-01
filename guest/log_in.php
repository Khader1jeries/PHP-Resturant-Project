<?php
session_start();
include "../config/phpdb.php";

$errorMessage = "";
$lockMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['signin'])) {
        // Sign-In Handling
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            // Check adminusers first
            $stmt = $conn->prepare("SELECT id, password, failed_attempts, temp_password, email FROM adminusers WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $isAdmin = true;
            } else {
                // Check clientusers
                $stmt = $conn->prepare("SELECT id, password, failed_attempts, temp_password, email FROM clientusers WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $isAdmin = false;
                } else {
                    $errorMessage = "Invalid username or password.";
                }
            }

            if (!$errorMessage) {
                // Account lock check
                if ($user['failed_attempts'] >= 3) {
                    $lockMessage = "Account locked. Please reset your password.";
                    $success = 0; // Login failed due to locked account
                } else {
                    // Temporary password check
                    if ($user['temp_password']) {
                        if ($password === $user['temp_password']) {
                            // Temporary password is valid, redirect to password reset
                            $_SESSION['temp_password'] = true;
                            $_SESSION['reset_user'] = $username;

                            // Reset failed attempts
                            $resetStmt = $conn->prepare($isAdmin
                                ? "UPDATE adminusers SET failed_attempts = 0 WHERE id = ?"
                                : "UPDATE clientusers SET failed_attempts = 0 WHERE id = ?");
                            $resetStmt->bind_param("i", $user['id']);
                            $resetStmt->execute();

                            $success = 1; // Login successful (temporary password)
                            if($isAdmin){
                                $logStmt = $conn->prepare("INSERT INTO admin_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                                $logStmt->bind_param("ssi", $username, $user['email'], $success);
                                $logStmt->execute();
    
                            }
                            else{
                            // Log the login attempt
                            $logStmt = $conn->prepare("INSERT INTO client_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                            $logStmt->bind_param("ssi", $username, $user['email'], $success);
                            $logStmt->execute();
                            }
                            header("Location: change_password.php?username=" . urlencode($username));
                            exit();
                        } else {
                            $errorMessage = "Invalid temporary password.";
                            $success = 0; // Login failed
                        }
                    } else {
                        // Normal password validation
                        if ($password === $user['password']) {
                            // Reset failed attempts
                            $resetStmt = $conn->prepare($isAdmin
                                ? "UPDATE adminusers SET failed_attempts = 0 WHERE id = ?"
                                : "UPDATE clientusers SET failed_attempts = 0 WHERE id = ?");
                            $resetStmt->bind_param("i", $user['id']);
                            $resetStmt->execute();

                            // Set session variables
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $username;

                            $success = 1; // Login successful

                            // Log the login attempt
                            if($isAdmin){
                                $logStmt = $conn->prepare("INSERT INTO admin_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                                $logStmt->bind_param("ssi", $username, $user['email'], $success);
                                $logStmt->execute();  
                            }
                            else{

                            
                            $logStmt = $conn->prepare("INSERT INTO client_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                            $logStmt->bind_param("ssi", $username, $user['email'], $success);
                            $logStmt->execute();
                            }
                            // Redirect based on user type
                            if ($isAdmin) {
                                header("Location: ../Admin/index.php");
                                exit();
                            } else {
                                header("Location: ../Client/index.php");
                                exit();
                            }
                        } else {
                            // Increment failed attempts
                            $incrementStmt = $conn->prepare($isAdmin
                                ? "UPDATE adminusers SET failed_attempts = failed_attempts + 1 WHERE id = ?"
                                : "UPDATE clientusers SET failed_attempts = failed_attempts + 1 WHERE id = ?");
                            $incrementStmt->bind_param("i", $user['id']);
                            $incrementStmt->execute();

                            $errorMessage = "Invalid username or password.";
                            $success = 0; // Login failed
                        }
                    }
                }
                if($isAdmin){
                     // Log the login attempt (for failed attempts or locked accounts)
                $logStmt = $conn->prepare("INSERT INTO admin_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                $logStmt->bind_param("ssi", $username, $user['email'], $success);
                $logStmt->execute();
                }
                else{
                // Log the login attempt (for failed attempts or locked accounts)
                $logStmt = $conn->prepare("INSERT INTO client_login_history (username, email, date, success) VALUES (?, ?, NOW(), ?)");
                $logStmt->bind_param("ssi", $username, $user['email'], $success);
                $logStmt->execute();
                }
            }
        } else {
            $errorMessage = "Please fill in all fields.";
        }
    }

    if (isset($_POST['forgotPassword'])) {
        // Forgot Password Handling
        $username = trim($_POST['username'] ?? '');

        if (!empty($username)) {
            $stmt = $conn->prepare("SELECT id, email, password FROM clientusers WHERE username = ? UNION SELECT id, email, password FROM adminusers WHERE username = ?");
            $stmt->bind_param("ss", $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $tempPassword = bin2hex(random_bytes(4));

                // Save the current password to history and set the temp password
                $updateStmt = $conn->prepare("UPDATE clientusers SET 
                    password_3 = password_2, 
                    password_2 = password_1, 
                    password_1 = password, 
                    temp_password = ?, 
                    failed_attempts = 0 WHERE username = ?");

                $updateStmt->bind_param("ss", $tempPassword, $username);

                // If the update for `clientusers` fails, try `adminusers`
                if (!$updateStmt->execute()) {
                    $updateStmt = $conn->prepare("UPDATE adminusers SET 
                        password_3 = password_2, 
                        password_2 = password_1, 
                        password_1 = password, 
                        temp_password = ?, 
                        failed_attempts = 0 WHERE username = ?");
                    $updateStmt->bind_param("ss", $tempPassword, $username);
                    $updateStmt->execute();
                }

                // Send email to the user with the temporary password
                mail($user['email'], "Password Reset Request", "Your temporary password is: $tempPassword", "From: no-reply@domain.com");
                $errorMessage = "Temporary password sent to your email.";
            } else {
                $errorMessage = "Username not found.";
            }
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