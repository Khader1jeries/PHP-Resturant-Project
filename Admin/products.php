<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link rel="stylesheet" href="css_files/product.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="css_files/navbar.css">
</head>
<body>
    <!-- Include Navbar -->
    <?php require 'navbar.php'; ?>
    <div style="margin-left:11%;margin-top:2%;">
    <!-- Logo Section -->
    <div class="logo-container">
        <img src="../photos/logo_Images/adminicon.png" id="icon" alt="Admin Icon">
    </div>

    <!-- Buttons Section -->
    <div class="container">
        <div class="button-container">
            <button onclick="window.location.href='createProduct.php'">Create Product</button>
            <button onclick="window.location.href='editProduct.php'">Edit Product</button>
        </div>
    </div>
    </div>
</body>
</html>