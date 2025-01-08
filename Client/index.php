<?php
session_start();
include "../config/userAuthConfig.php"; // Include the database connection

// Check if the user is logged in
$user = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query the database to get the user details
    $stmt = $conn->prepare("SELECT id, username, firstname, lastname, email, phone FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Binding the username to the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch the user details
    } else {
        // If no user is found, display an error message
        echo "<p style='color: red;'>No user found with the username provided.</p>";
    }

    $stmt->close(); // Close the database statement
} else {
    // If not logged in, redirect to the login page
    header("Location: signin.php");
    exit(); // Stop script execution
}

// Define paths for resources
$imagesPath = "../photos/index_images/";
$cssPath = "../css_files/maincss.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $cssPath ?>" />
    <title>Home</title>
</head>
<body id="restaurantBody">
    <div class="Project">
        <?php include 'header.php'; ?>
        <?php include '../includes/navbar.php'; ?>
        
        <div class="mainContent">
            <?php if ($user): ?>
                <h2>Welcome, <?= htmlspecialchars($user['firstname']) ?> <?= htmlspecialchars($user['lastname']) ?></h2>
                <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <?php endif; ?>
        </div>
        
        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>
