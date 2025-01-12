<?php
session_start();
include "config/userAuthConfig.php"; // Include your database connection

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
    <link rel="stylesheet" href="css_files/sign_in.css" />
    <style>
        /* CSS from your request */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(-135deg, wheat, gray);
            height: 938px;
            margin: 0;
            overflow: scroll;
        }

        .container {
            width: 560px;
            margin: 0 auto;
            margin-top: 100px;
            margin-bottom: 18px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
            padding-bottom: 30px;
        }

        h2 {
            font-size: 35px;
            font-weight: 600;
            text-align: center;
            color: black;
            user-select: none;
            border-radius: 15px 15px 0 0;
            background: linear-gradient(-135deg, wheat, gray);
            padding: 10px 0;
        }

        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }

        button {
            color: black;
            border: none;
            width: 80%;
            border-radius: 20px;
            padding: 15px;
            margin-top: 20px;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(-135deg, wheat, gray);
            transition: all 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button:hover {
            box-shadow: rgba(49, 49, 49, 0.35) 0 -25px 18px -14px inset,
                rgba(49, 49, 49, 0.35) 0 1px 2px, rgba(49, 49, 49, 0.35) 0 2px 4px,
                rgba(49, 49, 49, 0.35) 0 4px 8px, rgba(81, 81, 81, 0.25) 0 8px 16px,
                rgba(97, 97, 97, 0.25) 0 16px 32px;
            transform: scale(0.95);
            transition: 0.8s ease;
        }
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
