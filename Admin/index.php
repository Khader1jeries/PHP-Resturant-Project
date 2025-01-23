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
                <h1>Welcome, <?= htmlspecialchars($user['firstname'] ?? 'Admin') ?></h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </header>

            <div class="content">
                <h2>Dashboard Overview</h2>
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <p>1,234</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Orders</h3>
                        <p>567</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Products</h3>
                        <p>89</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>