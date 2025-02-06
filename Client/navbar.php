<?php
// Check if a session is already active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../config/phpdb.php"; // Ensure this file initializes $conn correctly

// user details if logged in
$user = null;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Query the database to get the user details from the clientusers table
    $query = "SELECT id, username, firstname, lastname, email, phone FROM clientusers WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result); // Fetch the user details
    } else {
        // If no user is found, display an error message
        echo "<p style='color: red;'>No user found with the username provided.</p>";
    }
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
        .navbar-custom {
            background-color: #8B4513; /* Premium brown color */
        }

        .navbar-custom .btn-custom {
            color: #fff;
            background-color: #A0522D;
            border: none;
            margin: 0 5px;
        }

        .navbar-custom .btn-custom:hover {
            background-color: #D2691E;
        }

        .navbar-custom .username {
            color: #FFF;
            margin-right: 15px;
        }

        .navbar-custom .nav-link {
            color: #FFF;
            margin-right: 15px;
        }

        .navbar-custom .nav-link:hover {
            color: #D2B48C;
        }

        .navbar-custom .logo {
            height: 50px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="../photos/logo_Images/Logo99.png" alt="Logo" class="logo">
            </a>

            <!-- Navigation links -->
            <div class="d-flex">
                <a href="food.php" target="main" class="nav-link">
                    <i class="fas fa-utensils"></i> Food
                </a>
                <a href="drink.php" target="main" class="nav-link">
                    <i class="fas fa-glass-martini"></i> Drinks
                </a>
                <a href="location.php" target="main" class="nav-link">
                    <i class="fas fa-map-marker-alt"></i> Our Place
                </a>
                <a href="contact_us.php" target="main" class="nav-link">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>

            <!-- Right-side buttons -->
            <div class="ms-auto d-flex align-items-center">
                <?php if (isset($_SESSION['username'])): ?>
                    <span class="username">Welcome, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></span>
                    <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#accountModal">
                        <i class="fas fa-user"></i> My Account
                    </button>
                    <a href="cart.php" class="btn btn-custom">
                        <i class="fas fa-shopping-cart"></i> Cart
                    </a>
                    <a href="logout.php" class="btn btn-custom">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-custom">Login</a>
                <?php endif; ?>
            </div>
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
