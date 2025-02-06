<?php
session_start();
include "../config/phpdb.php";

// Fetch cart items for the logged-in user
$cartItems = [];
$stockWarnings = [];

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch user ID based on username
    $userQuery = "SELECT id FROM clientusers WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $userId = mysqli_fetch_assoc($userResult)['id'];

        // Fetch cart items and product stock
        $cartQuery = "
            SELECT c.id AS cart_id, p.id AS product_id, p.name AS product_name, 
                   p.price, c.quantity, p.stock, p.kind
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = '$userId'
            ORDER BY p.kind ASC, p.id ASC";

        $cartResult = mysqli_query($conn, $cartQuery);
        if (!$cartResult) {
            die("Error in query: " . mysqli_error($conn));
        }

        $cartItems = [];
        while ($row = mysqli_fetch_assoc($cartResult)) {
            $cartItems[] = $row;
        }

        // Check stock availability
        foreach ($cartItems as &$item) {
            if ($item['quantity'] > $item['stock']) {
                $stockWarnings[$item['cart_id']] = "Not enough stock for " . htmlspecialchars($item['product_name']) . ". Available: " . $item['stock'];
                $item['quantity'] = $item['stock']; // Prevent exceeding stock
            }
        }
        unset($item); // Break reference with the last item
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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
                                    <button type="submit" name="action" value="increase" class="cta-button"
                                        <?php echo isset($stockWarnings[$item['cart_id']]) ? 'disabled' : ''; ?>>+</button>
                                    <button type="submit" name="action" value="decrease" class="cta-button">-</button>
                                </form>
                                <form action="update_cart.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                    <button type="submit" name="action" value="remove" class="cta-button">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php if (isset($stockWarnings[$item['cart_id']])): ?>
                            <tr>
                                <td colspan="5" style="color: red; text-align: center;">
                                    <?php echo $stockWarnings[$item['cart_id']]; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-actions">
                <span class="total-price">Total: ₪<?php echo number_format($total, 2); ?></span>
                <a href="checkout.php" class="cta-button">Proceed to Checkout</a>
                <a href="order_details.php" class="cta-button">View Your Orders</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="food.php" class="cta-button">Shop Now</a><a href="orders.php" class="cta-button" style="margin-left: 5px;">View All Orders</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
