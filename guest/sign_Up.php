<?php
session_start();
include "../config/phpdb.php";

// Initialize session variables for form data
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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup'])) {
    // Retrieve form values
    $username = $_POST['username'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $password = $_POST['password'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $repassword = $_POST['repassword'] ?? null;

    // Store form data in session
    $_SESSION['form_data'] = [
        'username' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'phone' => $phone,
        'password' => $password,
        'dob' => $dob
    ];

    // Validate required fields
    $required_fields = [$username, $firstname, $lastname, $email, $phone, $password, $dob, $repassword];
    if (in_array(null, $required_fields, true)) {
        echo "<p class='error-message'>Please fill out all required fields.</p>";
    } else {
        // Validate password match
        if ($password !== $repassword) {
            echo "<p class='error-message'>Error: Passwords do not match.</p>";
        }
        // Validate phone number
        elseif (!preg_match("/^\d{10}$/", $phone)) {
            echo "<p class='error-message'>Error: Phone number must be exactly 10 digits.</p>";
        }
        // Validate email
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error-message'>Error: Invalid email format.</p>";
        }
        // Validate date of birth (basic format check)
        elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
            echo "<p class='error-message'>Error: Invalid date format. Please use YYYY-MM-DD.</p>";
        }
        // Validate date of birth (not in the future and at least 18 years old)
        else {
            $today = new DateTime(); // Current date
            $birthdate = new DateTime($dob); // User's birthdate
            $age = $today->diff($birthdate)->y; // Calculate age

            if ($birthdate > $today) {
                echo "<p class='error-message'>Error: Date of birth cannot be in the future.</p>";
            } elseif ($age < 18) {
                echo "<p class='error-message'>Error: You must be at least 18 years old to sign up.</p>";
            } else {
                // Check username availability
                $stmt = $conn->prepare("SELECT username FROM clientusers WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo "<p class='error-message'>Error: Username already taken.</p>";
                } else {
                    // Check email availability
                    $stmt = $conn->prepare("SELECT email FROM clientusers WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        echo "<p class='error-message'>Error: Email already registered.</p>";
                    } else {
                        // Insert new user with date of birth
                        $stmt = $conn->prepare("INSERT INTO clientusers (username, firstname, lastname, email, phone, password, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $username, $firstname, $lastname, $email, $phone, $password, $dob);

                        if ($stmt->execute()) {
                            // Clear form data after successful registration
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
                            echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
                        }
                    }
                }
                $stmt->close();
            }
        }
    }
}

// Close the connection safely
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
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
            <input type="text" name="username" required placeholder="Username" value="<?php echo htmlspecialchars($_SESSION['form_data']['username']); ?>">
            <input type="text" name="firstname" required placeholder="First Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['firstname']); ?>">
            <input type="text" name="lastname" required placeholder="Last Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['lastname']); ?>">
            <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email']); ?>">
            Date of birth:     <input type="date" name="dob" required value="<?php echo htmlspecialchars($_SESSION['form_data']['dob']); ?>">
            <input type="text" name="phone" required placeholder="Phone Number (10 digits)" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone']); ?>">
            <input type="password" name="password" required placeholder="Password">
            <input type="password" name="repassword" required placeholder="Re-enter Password">
            <input type="submit" name="signup" value="Sign Up">
        </form>
    </div>
</body>
</html>