<?php

session_start();
include "../config/phpdb.php";

function signIn($conn, $username, $password) {
    $errorMessage = "";
    $lockMessage = "";
    $isAdmin = false;
    
    // Check adminusers first
    $query = "SELECT id, password, failed_attempts, temp_password, email FROM adminusers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $isAdmin = true;
    } else {
        // Check clientusers
        $query = "SELECT id, password, failed_attempts, temp_password, email FROM clientusers WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $isAdmin = false;
        } else {
            return "Invalid username or password.";
        }
    }

    if ($user['failed_attempts'] >= 3) {
        return "Account locked. Please reset your password.";
    }

    if ($user['temp_password'] && $password === $user['temp_password']) {
        $_SESSION['temp_password'] = true;
        $_SESSION['reset_user'] = $username;
        $table = $isAdmin ? "adminusers" : "clientusers";
        mysqli_query($conn, "UPDATE $table SET failed_attempts = 0 WHERE id = {$user['id']}");
        logLoginAttempt($conn, $username, $user['email'], 1, $isAdmin);
        header("Location: change_password.php?username=" . urlencode($username));
        exit();
    }

    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        mysqli_query($conn, "UPDATE " . ($isAdmin ? "adminusers" : "clientusers") . " SET failed_attempts = 0 WHERE id = {$user['id']}");
        logLoginAttempt($conn, $username, $user['email'], 1, $isAdmin);
        header("Location: " . ($isAdmin ? "../Admin/index.php" : "../Client/index.php"));
        exit();
    } else {
        mysqli_query($conn, "UPDATE " . ($isAdmin ? "adminusers" : "clientusers") . " SET failed_attempts = failed_attempts + 1 WHERE id = {$user['id']}");
        logLoginAttempt($conn, $username, $user['email'], 0, $isAdmin);
        return "Invalid username or password.";
    }
}

function logLoginAttempt($conn, $username, $email, $success, $isAdmin) {
    $table = $isAdmin ? "admin_login_history" : "client_login_history";
    mysqli_query($conn, "INSERT INTO $table (username, email, date, success) VALUES ('$username', '$email', NOW(), $success)");
}

function forgotPassword($conn, $username) {
    $query = "SELECT id, email, password FROM clientusers WHERE username = '$username' UNION SELECT id, email, password FROM adminusers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $tempPassword = bin2hex(random_bytes(4));
        
        mysqli_query($conn, "UPDATE clientusers SET password_3 = password_2, password_2 = password_1, password_1 = password, temp_password = '$tempPassword', failed_attempts = 0 WHERE username = '$username'");
        mysqli_query($conn, "UPDATE adminusers SET password_3 = password_2, password_2 = password_1, password_1 = password, temp_password = '$tempPassword', failed_attempts = 0 WHERE username = '$username'");
        
        $subject = "Password Reset Request";
        $message = "Your temporary password is: $tempPassword\n\nPlease use this link to log in: http://localhost/PHP-Resturant-Project/guest/log_in.php";
        $headers = "From: no-reply@domain.com";
        
        if (mail($user['email'], $subject, $message, $headers)) {
            return "Temporary password sent to your email.";
        } else {
            return "Failed to send email. Please try again.";
        }
    }
    return "Username not found.";
}
?>