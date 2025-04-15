<?php
// Service/sign_up_service.php
session_start();
include "../config/phpdb.php";

function initializeFormData() {
    if (!isset($_SESSION['form_data'])) {
        $_SESSION['form_data'] = [
            'username' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'password' => '',
            'dob' => ''
        ];
    }
}

function validateSignUp($conn, $formData) {
    extract($formData);
    $_SESSION['form_data'] = $formData;
    
    if (in_array(null, $formData, true)) {
        return "Please fill out all required fields.";
    }
    
    if ($password !== $formData['repassword']) {
        return "Error: Passwords do not match.";
    }
    
    if (!preg_match("/^\d{10}$/", $phone)) {
        return "Error: Phone number must be exactly 10 digits.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Error: Invalid email format.";
    }
    
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
        return "Error: Invalid date format. Please use YYYY-MM-DD.";
    }
    
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $today->diff($birthdate)->y;
    
    if ($birthdate > $today) {
        return "Error: Date of birth cannot be in the future.";
    } elseif ($age < 18) {
        return "Error: You must be at least 18 years old to sign up.";
    }
    
    $query = "SELECT username FROM clientusers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return "Error: Username already taken.";
    }
    
    $query = "SELECT email FROM clientusers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return "Error: Email already registered.";
    }
    
    return null;
}

function registerUser($conn, $formData) {
    extract($formData);
    
    $query = "INSERT INTO clientusers (username, firstname, lastname, email, phone, password, dob) VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', '$password', '$dob')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['form_data'] = [
            'username' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'phone' => '',
            'password' => '',
            'dob' => ''
        ];
        header("Location: log_in.php");
        exit();
    } else {
        return "Error: " . mysqli_error($conn);
    }
}
?>
