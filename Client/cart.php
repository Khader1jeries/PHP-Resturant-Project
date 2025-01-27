<?php
session_start();
include "../config/phpdb.php"; // Ensure this file initializes $conn correctly

// Fetch cart items for the logged-in user
$cartItems = [];
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user ID based on username
    $userStmt = $conn->prepare("SELECT id FROM clientusers WHERE username = ?");
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result();

    if ($userResult->num_rows > 0) {
        $userId = $userResult->fetch_assoc()['id'];

        // Fetch cart items for the user
        $cartStmt = $conn->prepare("
            SELECT c.id AS cart_id, p.name AS product_name, p.price, c.quantity 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $cartStmt->bind_param("i", $userId);
        $cartStmt->execute();
        $cartItems = $cartStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css_files/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="index-page">
    <div class="cart-container">
        <?php require 'navbar.php'; ?>
        <h1 class="cart-title">Your Cart</h1>

        <?php if (!empty($cartItems)): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td>₪<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>₪<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form action="update_cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="action" value="increase" class="cta-button">+</button>
                                    <button type="submit" name="action" value="decrease" class="cta-button">-</button>
                                </form>
                                <form action="update_cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="action" value="remove" class="cta-button">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-actions">
                <span class="total-price">Total: ₪<?php echo number_format($total, 2); ?></span>
                <a href="checkout.php" class="cta-button">Proceed to Checkout</a>
                <a href="view_orders.php" class="cta-button">View Your Orders</a> <!-- New button for viewing orders -->
            </div>
        <?php else: ?>
            
            <p>Your cart is empty. <a href="food.php" class="cta-button">Shop Now</a><a href="orders.php" class="cta-button" style="margin-left: 5px;">View All Orders</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
