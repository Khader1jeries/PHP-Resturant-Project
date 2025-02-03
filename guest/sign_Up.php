<?php
include "../config/phpdb.php";
include "Service/sign_up_service.php";

initializeFormData();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signup'])) {
    $formData = [
        'username' => $_POST['username'] ?? '',
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'password' => $_POST['password'] ?? '',
        'dob' => $_POST['dob'] ?? '',
        'repassword' => $_POST['repassword'] ?? ''
    ];
    
    $errorMessage = validateSignUp($conn, $formData);
    
    if (!$errorMessage) {
        $errorMessage = registerUser($conn, $formData);
    }
}
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
            <input type="text" name="username" required placeholder="Username" value="<?php echo htmlspecialchars($_SESSION['form_data']['username']); ?>">
            <input type="text" name="firstname" required placeholder="First Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['firstname']); ?>">
            <input type="text" name="lastname" required placeholder="Last Name" value="<?php echo htmlspecialchars($_SESSION['form_data']['lastname']); ?>">
            <input type="email" name="email" required placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['form_data']['email']); ?>">
            Date of birth: <input type="date" name="dob" required value="<?php echo htmlspecialchars($_SESSION['form_data']['dob']); ?>">
            <input type="text" name="phone" required placeholder="Phone Number (10 digits)" value="<?php echo htmlspecialchars($_SESSION['form_data']['phone']); ?>">
            <input type="password" name="password" required placeholder="Password">
            <input type="password" name="repassword" required placeholder="Re-enter Password">
            <input type="submit" name="signup" value="Sign Up">
        </form>
        
        <?php if ($errorMessage): ?>
            <p style="color: red; text-align: center;"> <?php echo $errorMessage; ?> </p>
        <?php endif; ?>
    </div>
</body>
</html>