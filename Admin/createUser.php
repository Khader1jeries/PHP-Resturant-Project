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
        'dob' => '',
        'user_type' => 'client' // Default user type
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_user'])) {
    // Retrieve form values
    $username = $_POST['username'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $password = $_POST['password'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $repassword = $_POST['repassword'] ?? null;
    $user_type = $_POST['user_type'] ?? 'client'; // Default to 'client' if not set

    // Store form data in session
    $_SESSION['form_data'] = [
        'username' => $username,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'phone' => $phone,
        'password' => $password,
        'dob' => $dob,
        'user_type' => $user_type
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
                // Check if the username or email exists in the other table
                $stmt = $conn->prepare("
                    SELECT username, email FROM clientusers WHERE username = ? OR email = ?
                    UNION
                    SELECT username, email FROM adminusers WHERE username = ? OR email = ?
                ");
                $stmt->bind_param("ssss", $username, $email, $username, $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    // Fetch the results to determine which table the user exists in
                    $stmt->bind_result($existing_username, $existing_email);
                    $stmt->fetch();

                    if ($existing_username === $username) {
                        echo "<p class='error-message'>Error: Username already exists in the system.</p>";
                    } elseif ($existing_email === $email) {
                        echo "<p class='error-message'>Error: Email already exists in the system.</p>";
                    }
                } else {
                    // Insert new user into the appropriate table
                    if ($user_type === 'admin') {
                        $stmt = $conn->prepare("INSERT INTO adminusers (username, firstname, lastname, email, phone, password, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    } else {
                        $stmt = $conn->prepare("INSERT INTO clientusers (username, firstname, lastname, email, phone, password, dob) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    }
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
                            'dob' => '',
                            'user_type' => 'client'
                        ];
                        echo "<p class='success-message'>User created successfully!</p>";
                    } else {
                        echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
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
    <link rel="stylesheet" href="css_files/navbar.css">
    <link rel="stylesheet" href="../css_files/sign_up.css" />
    <title>Create User</title>
</head>
<body class="signup-page">
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h2>Create New User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" name="username" required placeholder="Username" value="<?php echo htmlspecialchars($_SESSION['form_data']['username']); ?>">
            <input type="text" name="firstname" required placeholder="First Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['firstname']); ?>">
            <input type="text" name="lastname" required placeholder="Last Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['lastname']); ?>">
            <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email']); ?>">
            Date of birth: <input type="date" name="dob" required value="<?php echo htmlspecialchars($_SESSION['form_data']['dob']); ?>">
            <input type="text" name="phone" required placeholder="Phone Number (10 digits)" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone']); ?>">
            <input type="password" name="password" required placeholder="Password">
            <input type="password" name="repassword" required placeholder="Re-enter Password">
            <label for="user_type">User Type:</label>
            <select name="user_type" id="user_type" required>
                <option value="client" <?php echo ($_SESSION['form_data']['user_type'] === 'client') ? 'selected' : ''; ?>>Client</option>
                <option value="admin" <?php echo ($_SESSION['form_data']['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <input type="submit" name="create_user" value="Create User">
        </form>
    </div>
</body>
</html>