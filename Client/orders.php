<?php
session_start();

// Ensure the correct path to phpdb.php
include_once "../config/phpdb.php"; // Include your database configuration file

// Check if the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user ID based on the session username
$userStmt = $conn->prepare("SELECT id FROM clientusers WHERE username = ?");
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows > 0) {
    $userId = $userResult->fetch_assoc()['id'];

    // Fetch all orders for the user
    $ordersStmt = $conn->prepare("
        SELECT id, total_amount, purchase_date 
        FROM purchases 
        WHERE user_id = ?
        ORDER BY purchase_date DESC
    ");
    $ordersStmt->bind_param("i", $userId);
    $ordersStmt->execute();
    $ordersResult = $ordersStmt->get_result();

    // Store the orders in an array
    $orders = [];
    while ($order = $ordersResult->fetch_assoc()) {
        $orders[] = $order;
    }
} else {
    die("User not found.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="css_files/orders.css">
</head>
<body>
    <div class="orders-container">
        <h1>Your Orders</h1>
        
        <?php if (count($orders) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Amount</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                            <td>
                                <a href="order_details.php?purchase_id=<?php echo htmlspecialchars($order['id']); ?>" class="cta-button">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not made any orders yet. <a href="food.php">Browse Products</a> and make your first purchase!</p>
        <?php endif; ?>
        
        <a href="food.php" class="cta-button">Continue Shopping</a>
    </div>
</body>
</html>
