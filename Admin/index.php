<?php

session_start();


include "../config/phpdb.php";

//  counts from the database
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM clientusers";
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM purchases";
$totalProductsQuery = "SELECT COUNT(*) AS total_products FROM products";
$totalAdminsQuery = "SELECT COUNT(*) AS total_admins FROM adminusers";

// Execute queries
$totalUsersResult = $conn->query($totalUsersQuery);
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalProductsResult = $conn->query($totalProductsQuery);
$totalAdminsResult = $conn->query($totalAdminsQuery);


$totalUsers = $totalUsersResult->fetch_assoc()['total_users'] ?? 0;
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'] ?? 0;
$totalProducts = $totalProductsResult->fetch_assoc()['total_products'] ?? 0;
$totalAdmins = $totalAdminsResult->fetch_assoc()['total_admins'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css_files/index.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar (Leftbar) -->
        <?php require 'navbar.php'; ?>
        <!-- Main Content Area -->
        <div class="main-content">
            <header class="admin-header">
                <h1>Welcome, <?= htmlspecialchars($_SESSION['firstname'] ?? 'Admin') ?></h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </header>

            <div class="content">
                <h2>Dashboard Overview</h2>
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total Clients</h3>
                        <p><?= $totalUsers ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Orders</h3>
                        <p><?= $totalOrders ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Products</h3>
                        <p><?= $totalProducts ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Admins</h3>
                        <p><?= $totalAdmins ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
