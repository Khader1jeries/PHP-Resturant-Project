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
$cssPath = "../css_files/index.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $cssPath ?>" />
    <title>Home</title>
</head>
<body class="index-page">
    <div class="Project">
        <?php require 'navbar.php'; ?>

        <!-- New Content Section -->
        <div class="content-section">
            <div class="text-content">
                <h1>HUMAN CONNECTION IN A DIGITAL-FIRST WORLD.</h1>
                <p>Resident experiences are designed to deepen relationships.</p>
                <p>We host events in private luxury apartments and exclusive members-only clubs, featuring top-class culinary talent from renowned restaurants like Blue Hill, Carbone, Eleven Madison Park, Home, and Per Se.</p>
                <p>Together, we create one-of-a-kind, engaging, and memorable evenings that drive conversation and create connection.</p>
                <p>Reserve your tickets to a dinner below, or book a private experience <a href="#">here</a>.</p>
                <a href="Food.php" class="cta-button">VIEW UPCOMING DINNERS</a>
            </div>
            <div class="image-content">
                <img src="<?= $imagesPath ?>indexStaick.jpg" alt="Restaurant Image">
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
    </div>
</body>
</html>