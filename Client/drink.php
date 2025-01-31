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

// Initialize feedback message
$feedback = "";

// Handle form submission to add products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity
    if ($quantity <= 0) {
        $feedback = "Quantity must be greater than 0.";
    } else {
        // Check if the product is already in the cart
        $checkStmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $checkStmt->bind_param("ii", $userId, $productId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // If the product is already in the cart, update the quantity
            $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $updateStmt->bind_param("iii", $quantity, $userId, $productId);
            $updateStmt->execute();
            $feedback = "Product quantity updated in your cart.";
        } else {
            // Otherwise, insert the product into the cart
            $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iii", $userId, $productId, $quantity);
            $insertStmt->execute();
            $feedback = "Product has been saved to your cart.";
        }
        $checkStmt->close();
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
    <link rel="stylesheet" type="text/css" href="css_files/drink.css" />
    <title>Drink Menu</title>
  </head>
  <body class="drink-page">
    <?php require 'navbar.php'; ?>
    <h1>Drink Menu</h1>

    <!-- Display feedback message -->
    <?php if (!empty($feedback)): ?>
      <div class="alert" style="
        margin: 10px auto;
        padding: 10px;
        background-color: #dff0d8; /* Light green for success */
        color: #3c763d; /* Dark green for text */
        border: 1px solid #d6e9c6;
        border-radius: 4px;
        text-align: center;
        max-width: 600px;
        margin-top: 35px;">
        <?= htmlspecialchars($feedback) ?>
      </div>
    <?php endif; ?>

    <div class="drink">
      <?php foreach ($products as $product): ?>
        <?php if (isset($product['kind']) && $product['kind'] == 1): ?> <!-- Check if 'kind' exists and equals 1 for drinks -->
          <div class="item">
            <img
              src="../photos/drinks_images/<?= $product['path'] ?>"
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