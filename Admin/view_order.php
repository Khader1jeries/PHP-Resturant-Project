<?php
session_start();


include_once "../config/phpdb.php"; 


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if ($order_id === 0) {
    die("Invalid Order ID.");
}

//  order details
$order_query = "SELECT p.id, p.purchase_date, p.total_amount, c.firstname, c.lastname 
                FROM purchases p 
                JOIN clientusers c ON p.user_id = c.id
                WHERE p.id = $order_id";
$order_result = mysqli_query($conn, $order_query);

if (!$order_result || mysqli_num_rows($order_result) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);

//  purchase details (products)
$details_query = "SELECT pd.product_id, pr.name, pd.quantity, pd.price
                  FROM purchase_details pd
                  JOIN products pr ON pd.product_id = pr.id
                  WHERE pd.purchase_id = $order_id";
$details_result = mysqli_query($conn, $details_query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin</title>
    <link rel="stylesheet" href="css_files/view_order.css">
</head>
<body>
    <!-- Include the navbar -->
    <?php require 'navbar.php'; ?>

    <!-- Main content area -->
    <div class="order-details-container">
        <h1>Order Details</h1>

        <h2>Order #<?php echo htmlspecialchars($order['id']); ?></h2>
        <p>Date: <?php echo htmlspecialchars($order['purchase_date']); ?></p>
        <p>Total Amount: $<?php echo number_format($order['total_amount'], 2); ?></p>
        <p>Customer: <?php echo htmlspecialchars($order['firstname']) . ' ' . htmlspecialchars($order['lastname']); ?></p>

        <h3>Products</h3>
        <table class="order-details-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detail = mysqli_fetch_assoc($details_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detail['name']); ?></td>
                        <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                        <td>$<?php echo number_format($detail['price'], 2); ?></td>
                        <td>$<?php echo number_format($detail['quantity'] * $detail['price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="orders.php" class="cta-button">Back to Orders</a>
    </div>
</body>
</html>
