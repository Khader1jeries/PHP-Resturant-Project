<?php
session_start();
include "../config/phpdb.php"; // Ensure $conn is correctly initialized

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// user ID based on the session username
$userQuery = "SELECT id FROM clientusers WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

if (mysqli_num_rows($userResult) > 0) {
    $userId = mysqli_fetch_assoc($userResult)['id'];

    // cart items for the user
    $cartQuery = "
        SELECT c.product_id, c.quantity, p.price, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = '$userId'";
    
    $cartResult = mysqli_query($conn, $cartQuery);
    $cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);

    if (!empty($cartItems)) {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Calculate total amount
            $totalAmount = array_reduce($cartItems, function ($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // Insert a new purchase record
            $purchaseQuery = "INSERT INTO purchases (user_id, total_amount, purchase_date) VALUES ('$userId', '$totalAmount', NOW())";
            mysqli_query($conn, $purchaseQuery);
            $purchaseId = mysqli_insert_id($conn);

            // Insert cart items into purchase_details and update stock
            foreach ($cartItems as $item) {
                // Check if there's enough stock
                if ($item['quantity'] > $item['stock']) {
                    throw new Exception("Not enough stock for product ID " . $item['product_id']);
                }

                // Insert purchase details
                $detailsQuery = "INSERT INTO purchase_details (purchase_id, product_id, quantity, price) VALUES ('$purchaseId', '{$item['product_id']}', '{$item['quantity']}', '{$item['price']}')";
                mysqli_query($conn, $detailsQuery);

                // Update product stock
                $updateStockQuery = "UPDATE products SET stock = stock - '{$item['quantity']}' WHERE id = '{$item['product_id']}'";
                mysqli_query($conn, $updateStockQuery);
            }

            // Clear the cart
            $clearCartQuery = "DELETE FROM cart WHERE user_id = '$userId'";
            mysqli_query($conn, $clearCartQuery);

            // Commit transaction
            mysqli_commit($conn);

            // Redirect to success page
            header("Location: purchase_success.php?purchase_id=" . $purchaseId);
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            die("Checkout failed: " . $e->getMessage());
        }
    } else {
        die("Your cart is empty.");
    }
} else {
    die("User not found.");
}
?>
