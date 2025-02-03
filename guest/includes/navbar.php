<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Navbar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom premium brown color */
        .navbar-custom {
            margin-bottom: 40px;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <!-- Logo on the left -->
            <a class="navbar-brand" href="index.php">
                <img src="../photos/logo_images/Logo99.png" alt="Logo" class="logo">
            </a>
            <!-- Buttons on the right -->
            <div class="d-flex">
                <a href="../guest/Food.php" target="main" class="btn btn-custom">Food</a>
                <a href="../guest/drink.php" target="main" class="btn btn-custom">Drinks</a>
                <a href="../guest/location.php" target="main" class="btn btn-custom">Our Place</a>
                <a href="../guest/contact_us.php" target="main" class="btn btn-custom">Contact Us</a>
            </div>

            <!-- Login and Sign Up buttons on the top right -->
            <div class="ms-auto">
                <a href="../guest/log_in.php" target="main" class="btn btn-custom">Login</a>
                <a href="../guest/sign_up.php" target="main" class="btn btn-custom">Sign Up</a>
            </div>
        </div>
    </nav>
</body>
</html>