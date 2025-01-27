<?php
session_start();
include "../config/phpdb.php"; // Ensure $conn is correctly initialized

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

    // Fetch cart items for the user
    $cartStmt = $conn->prepare("
        SELECT c.product_id, c.quantity, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $cartStmt->bind_param("i", $userId);
    $cartStmt->execute();
    $cartItems = $cartStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (!empty($cartItems)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert a new purchase record
            $totalAmount = array_reduce($cartItems, function ($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            $purchaseStmt = $conn->prepare("INSERT INTO purchases (user_id, total_amount, purchase_date) VALUES (?, ?, NOW())");
            $purchaseStmt->bind_param("id", $userId, $totalAmount);
            $purchaseStmt->execute();
            $purchaseId = $conn->insert_id;

            // Insert cart items into purchase_details
            $detailsStmt = $conn->prepare("
                INSERT INTO purchase_details (purchase_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($cartItems as $item) {
                $detailsStmt->bind_param("iiid", $purchaseId, $item['product_id'], $item['quantity'], $item['price']);
                $detailsStmt->execute();
            }

            // Clear the cart
            $clearCartStmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $clearCartStmt->bind_param("i", $userId);
            $clearCartStmt->execute();

            // Commit transaction
            $conn->commit();

            // Redirect to success page
            header("Location: purchase_success.php?purchase_id=" . $purchaseId);
            exit();
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            die("Checkout failed: " . $e->getMessage());
        }
    } else {
        die("Your cart is empty.");
    }
} else {
    die("User not found.");
}
?>
