<?php
session_start(); // Start the session
include "../config/userAuthConfig.php"; // Include the database connection

// Fetch user details if logged in
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Navbar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom premium brown color */
        .navbar-custom {
            background-color: #8B4513; /* Premium brown color */
        }

        .navbar-custom .btn-custom {
            color: #fff;
            background-color: #A0522D; /* Slightly lighter brown for buttons */
            border: none;
            margin: 0 5px;
        }

        .navbar-custom .btn-custom:hover {
            background-color: #D2691E; /* Lighter brown on hover */
        }

        .navbar-custom .btn-reserve {
            border: 2px solid #FFD700; /* Gold border for reservation button */
            border-radius: 5px;
        }

        .navbar-custom .logo {
            height: 50px; /* Adjust logo size as needed */
        }

        .navbar-custom .username {
            color: #FFF; /* White text for the username */
            margin-right: 15px; /* Space between username and logout button */
        }

        .navbar-custom .nav-link {
            color: #FFF; /* White text for navigation links */
            margin-right: 15px; /* Space between navigation links */
        }

        .navbar-custom .nav-link:hover {
            color: #D2B48C; /* Light brown on hover */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Logo on the left -->
            <a class="navbar-brand" href="index.php">
                <img src="../photos/logo_Images/Logo99.png" alt="Logo" class="logo">
            </a>

            <!-- Navigation links on the left with icons -->
            <div class="d-flex">
                <a href="../Food.php" target="main" class="nav-link">
                    <i class="fas fa-utensils"></i> Food
                </a>
                <a href="../drink.php" target="main" class="nav-link">
                    <i class="fas fa-glass-martini"></i> Drinks
                </a>
                <a href="../reservation.php" target="main" class="nav-link">
                    <i class="fas fa-calendar-check"></i> Reservation
                </a>
                <a href="../includes/location.php" target="main" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i> Our Place
                </a>
                <a href="../contact_us.php" target="main" class="nav-link">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>

            <!-- Display username and logout button if user is logged in -->
            <?php if (isset($_SESSION['username'])): ?>
                <div class="ms-auto d-flex align-items-center">
                    <span class="username">Welcome, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
                    <a href="logout.php" class="btn btn-custom">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>