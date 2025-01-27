<?php
session_start();
include "../config/phpdb.php";

// Initialize session variables for form data if they don't exist
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = [
        'username' => '',
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'phone' => '',
        'password' => ''
    ];
}

// Handle form submission to add a new user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup'])) {
    // Safely retrieve form values using null coalescing operator
    $username = $_POST['username'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $password = $_POST['password'] ?? null;

    // Store form data in session
    $_SESSION['form_data'] = [
        'username' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'phone' => $phone,
        'password' => $password
    ];

    // Check for null values before proceeding
    if ($username && $firstname && $lastname && $email && $phone && $password) {
        // Validate phone number (must be exactly 10 digits)
        if (!preg_match("/^\d{10}$/", $phone)) {
            echo "<p class='error-message'>Error: Phone number must be exactly 10 digits.</p>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error-message'>Error: Invalid email format.</p>";
        } else {
            // Check if the username already exists in the users table
            $stmt = $conn->prepare("SELECT username FROM clientusers WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Username already exists, display an error message
                echo "<p class='error-message'>Error: The username is already taken. Please choose a different one.</p>";
            } else {
                // Insert the user into the database if username doesn't exist (plain text password)
                $stmt = $conn->prepare("INSERT INTO clientusers (username, firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $phone, $password);

                if ($stmt->execute()) {
                    // Clear form data from session after successful sign-up
                    $_SESSION['form_data'] = [
                        'username' => '',
                        'firstname' => '',
                        'lastname' => '',
                        'email' => '',
                        'phone' => '',
                        'password' => ''
                    ];

                    // Redirect to log_in.php after successful sign-up
                    header("Location: log_in.php");
                    exit(); // Stop script execution to ensure redirect happens
                } else {
                    echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                }
            }

            $stmt->close();
        }
    } else {
        echo "<p class='error-message' style='margin-top: 80px;'>Please fill out all required fields.</p>";
    }
}

// Close the connection after all operations
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css_files/sign_up.css" />
    <title>Sign Up</title>
</head>
<body class="signup-page">
    <?php require 'includes/navbar.php'; ?>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" name="username" required placeholder="Username" value="<?php echo htmlspecialchars($_SESSION['form_data']['username']); ?>" />
            <input type="text" name="firstname" required placeholder="First Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['firstname']); ?>" />
            <input type="text" name="lastname" required placeholder="Last Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['lastname']); ?>" />
            <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email']); ?>" />
            <input type="text" name="phone" required placeholder="Phone Number (10 digits)" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone']); ?>" />
            <input type="password" name="password" required placeholder="Password" value="<?php echo htmlspecialchars($_SESSION['form_data']['password']); ?>" />
            <input type="submit" name="signup" value="Sign Up" />
        </form>
    </div>
</body>
</html>