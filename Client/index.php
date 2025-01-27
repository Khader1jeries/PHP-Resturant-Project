<?php
session_start();
include "../config/phpdb.php";

// Check if the user is logged in
$user = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query the database to get the user details from the clientusers table
    $stmt = $conn->prepare("SELECT id, username, firstname, lastname, email, phone FROM clientusers WHERE username = ?");
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

                <!-- User Details Section -->
                <?php if ($user): ?>
                    <div class="user-profile">
                        <div class="profile-header">
                            <h2>Welcome Back, <?= htmlspecialchars($user['firstname']) ?>!</h2>
                            <p>Here are your account details:</p>
                        </div>
                        <div class="profile-details">
                            <div class="detail-item">
                                <span class="detail-label">Username:</span>
                                <span class="detail-value"><?= htmlspecialchars($user['username']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Full Name:</span>
                                <span class="detail-value"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email:</span>
                                <span class="detail-value"><?= htmlspecialchars($user['email']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Phone:</span>
                                <span class="detail-value"><?= htmlspecialchars($user['phone']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="image-content">
                <img src="<?= $imagesPath ?>indexStaick.jpg" alt="Restaurant Image">
            </div>
        </div>

        <?php include '../guest/includes/footer.php'; ?>
    </div>
</body>
</html>