<?php
session_start();
include "../config/phpdb.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to add products to your cart.");
}

// Fetch user ID from session
$username = $_SESSION['username'];
$userStmt = $conn->prepare("SELECT id FROM clientusers WHERE username = ?");
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();
if ($userResult->num_rows === 0) {
    die("Invalid user.");
}
$userId = $userResult->fetch_assoc()['id'];

// Initialize feedback message and type
$feedback = "";
$feedbackType = "success"; // Default to success

// Handle form submission to add products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity
    if ($quantity <= 0) {
        $feedback = "Quantity must be greater than 0.";
        $feedbackType = "error";
    } else {
        // Fetch the product's stock
        $stockStmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
        $stockStmt->bind_param("i", $productId);
        $stockStmt->execute();
        $stockResult = $stockStmt->get_result();
        $stock = $stockResult->fetch_assoc()['stock'];

        if ($quantity > $stock) {
            $feedback = "Requested quantity exceeds available stock. Only $stock items are available.";
            $feedbackType = "error";
        } else {
            // Check if the product is already in the cart
            $checkStmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
            $checkStmt->bind_param("ii", $userId, $productId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // If the product is already in the cart, check if the updated quantity exceeds stock
                $cartItem = $checkResult->fetch_assoc();
                $newQuantity = $cartItem['quantity'] + $quantity;

                if ($newQuantity > $stock) {
                    $feedback = "Adding this quantity would exceed available stock. Only $stock items are available.";
                    $feedbackType = "error";
                } else {
                    // Update the quantity in the cart
                    $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
                    $updateStmt->bind_param("iii", $quantity, $userId, $productId);
                    $updateStmt->execute();
                    $feedback = "Product quantity updated in your cart.";
                }
            } else {
                // Otherwise, insert the product into the cart
                $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
                $insertStmt->bind_param("iii", $userId, $productId, $quantity);
                $insertStmt->execute();
                $feedback = "Product has been saved to your cart.";
            }
            $checkStmt->close();
        }
        $stockStmt->close();
    }
}

// Fetch all products from the database
$stmt = $conn->prepare("SELECT id, name, price, path, kind, stock FROM products");
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css_files/food.css" />
    <title>Food Menu</title>
</head>
<body class="food-page">
    <?php require 'navbar.php'; ?>
    
    <h1>Food Menu</h1>

    <!-- Display feedback message -->
    <?php if (!empty($feedback)): ?>
        <p style="color: <?= $feedbackType === 'error' ? 'red' : 'green' ?>; font-weight: bold; text-align: center; margin-top: 30px;">
            <?= htmlspecialchars($feedback) ?>
        </p>
    <?php endif; ?>

    <div class="food">
        <?php foreach ($products as $product): ?>
            <?php if (isset($product['kind']) && $product['kind'] == 2): ?>
                <div class="item">
                    <img
                        src="../photos/food_images/<?= $product['path'] ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                    />
                    <div class="tooltip">
                        <?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>₪, Stock: <?= $product['stock'] ?>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <label for="quantity_<?= $product['id'] ?>">Quantity:</label>
                        <input type="number" id="quantity_<?= $product['id'] ?>" name="quantity" value="1" min="1" required>
                        <?php if ($product['stock'] > 0): ?>
                            <button type="submit">Add to cart</button>
                        <?php else: ?>
                            <p style="color: red; font-weight: bold;">Out of Stock</p>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>