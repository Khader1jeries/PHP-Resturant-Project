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
        'user_type' => 'client'
    ];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $repassword = $_POST['repassword'];
    $user_type = $_POST['user_type'] ?? 'client';

    $_SESSION['form_data'] = compact('username', 'firstname', 'lastname', 'email', 'phone', 'password', 'dob', 'user_type');

    if ($password !== $repassword) {
        echo "<p class='error-message'>Error: Passwords do not match.</p>";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        echo "<p class='error-message'>Error: Phone number must be exactly 10 digits.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error-message'>Error: Invalid email format.</p>";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) {
        echo "<p class='error-message'>Error: Invalid date format. Please use YYYY-MM-DD.</p>";
    } else {
        $today = new DateTime();
        $birthdate = new DateTime($dob);
        $age = $today->diff($birthdate)->y;

        if ($birthdate > $today) {
            echo "<p class='error-message'>Error: Date of birth cannot be in the future.</p>";
        } elseif ($age < 18) {
            echo "<p class='error-message'>Error: You must be at least 18 years old to sign up.</p>";
        } else {
            // Check if the username or email exists
            $query = "SELECT username, email FROM clientusers WHERE username = '$username' OR email = '$email'
                      UNION
                      SELECT username, email FROM adminusers WHERE username = '$username' OR email = '$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($row['username'] === $username) {
                    echo "<p class='error-message'>Error: Username already exists in the system.</p>";
                } elseif ($row['email'] === $email) {
                    echo "<p class='error-message'>Error: Email already exists in the system.</p>";
                }
            } else {
                $table = ($user_type === 'admin') ? 'adminusers' : 'clientusers';
                $query = "INSERT INTO $table (username, firstname, lastname, email, phone, password, dob) 
                          VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', '$password', '$dob')";
                
                if (mysqli_query($conn, $query)) {
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
                    echo "<p class='error-message'>Error: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/navbar.css">
    <link rel="stylesheet" href="css_files/signup.css" />
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
                <option value="client" <?php echo ($_SESSION['form_data']['user_type'] ?? 'client') === 'client' ? 'selected' : ''; ?>>Client</option>
                <option value="admin" <?php echo ($_SESSION['form_data']['user_type'] ?? 'client') === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <input type="submit" name="create_user" value="Create User">
        </form>
    </div>
</body>
</html>