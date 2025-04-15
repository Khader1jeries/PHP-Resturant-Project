<?php
session_start();
include_once '../config/phpdb.php'; // Correct the path to your database configuration

// Ensure the user is logged in and the purchase ID is valid
if (!isset($_GET['purchase_id']) || !isset($_SESSION['user_id'])) {
    header("Location: orders.php");
    exit();
}

$purchaseId = intval($_GET['purchase_id']);
$userId = $_SESSION['user_id'];

// the order details from the database
$sql = "SELECT pd.product_id, pd.quantity, pd.price, p.name AS product_name
        FROM purchase_details pd
        JOIN products p ON pd.product_id = p.id
        WHERE pd.purchase_id = '$purchaseId'";

$orderDetails = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Update CSS path -->
    <link rel="stylesheet" href="css_files/order_details.css"> 
</head>
<body>
    <div class="order-details-container">
        <h1>Order Details</h1>
        
        <?php if (mysqli_num_rows($orderDetails) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0;
                    while ($row = mysqli_fetch_assoc($orderDetails)) {
                        $total = $row['quantity'] * $row['price'];
                        $totalAmount += $total;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?> USD</td>
                            <td><?php echo htmlspecialchars($total); ?> USD</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3"><strong>Total Amount</strong></td>
                        <td><strong><?php echo htmlspecialchars($totalAmount); ?> USD</strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found for this order.</p>
        <?php endif; ?>

        <!-- Updated button link -->
        <a href="orders.php" class="cta-button">Back to Orders</a>
        <a href="generate_invoice.php?order_id=<?php echo $purchaseId ?>" class="cta-button">
    Download Invoice
</a>

    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
