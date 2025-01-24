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

        /* Modal styling */
        .modal-content {
            background-color: #333; /* Dark background for modal */
            color: white; /* Light text color */
        }

        .modal-header {
            border-bottom: 1px solid #8B4513; /* Premium brown border */
        }

        .modal-footer {
            border-top: 1px solid #8B4513; /* Premium brown border */
        }

        .modal-body p {
            margin: 10px 0;
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
                <a href="food.php" target="main" class="nav-link">
                    <i class="fas fa-utensils"></i> Food
                </a>
                <a href="drink.php" target="main" class="nav-link">
                    <i class="fas fa-glass-martini"></i> Drinks
                </a>
                <a href="reservation.php" target="main" class="nav-link">
                    <i class="fas fa-calendar-check"></i> Reservation
                </a>
                <a href="location.php" target="main" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i> Our Place
                </a>
                <a href="contact_us.php" target="main" class="nav-link">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>

            <!-- Display username, My Account button, and logout button if user is logged in -->
            <?php if (isset($_SESSION['username'])): ?>
                <div class="ms-auto d-flex align-items-center">
                    <span class="username">Welcome, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
                    <!-- My Account Button -->
                    <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#accountModal">
                        <i class="fas fa-user"></i> My Account
                    </button>
                    <a href="logout.php" class="btn btn-custom">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- My Account Modal -->
    <div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accountModalLabel">My Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($user): ?>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['firstname']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['lastname']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <?php else: ?>
                        <p>No user data found.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>