
<?php
session_start();
include "../config/phpdb.php";

function validateTempPassword() {
    if (!isset($_SESSION['temp_password'])) {
        header("Location: log_in.php");
        exit();
    }
}

function getUsername() {
    return $_GET['username'] ?? ($_SESSION['reset_user'] ?? null);
}

function checkUserType($conn, $username) {
    $query = "SELECT id FROM clientusers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) === 0) {
        $query = "SELECT id FROM adminusers WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) === 0) {
            return null;
        }
        return 'adminusers';
    }
    return 'clientusers';
}

function updatePassword($conn, $username, $newPassword, $table) {
    $query = "SELECT password, password_1, password_2, password_3 FROM $table WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $previousPasswords = array($row['password_1'], $row['password_2'], $row['password_3']);

        if (in_array($newPassword, $previousPasswords)) {
            return "You have used this password before. Please choose another one.";
        }

        $new_password_1 = $row['password'];
        $new_password_2 = $row['password_1'];
        $new_password_3 = $row['password_2'];

        $updateQuery = "UPDATE $table SET password = '$newPassword', password_1 = '$new_password_1', password_2 = '$new_password_2', password_3 = '$new_password_3', temp_password = NULL, failed_attempts = 0 WHERE username = '$username'";
        
        if (mysqli_query($conn, $updateQuery)) {
            unset($_SESSION['temp_password']);
            echo "<script>setTimeout(() => window.location.href='log_in.php', 3000);</script>";
            return "Password updated successfully! Redirecting...";
        } else {
            return "Error updating password. Please try again.";
        }
    }
    return "Error retrieving user data.";
}
?>
