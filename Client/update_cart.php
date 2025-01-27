<?php
session_start();
include "../config/phpdb.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to update your cart.");
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

// Check if the form was submitted and the required data is present
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['action'])) {
    $cartId = intval($_POST['cart_id']);
    $action = $_POST['action'];

    // Fetch current quantity of the item in the cart
    $checkStmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
    $checkStmt->bind_param("ii", $cartId, $userId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $currentQuantity = $checkResult->fetch_assoc()['quantity'];

        if ($action === "increase") {
            // Increase quantity by 1
            $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
            $updateStmt->bind_param("ii", $cartId, $userId);
            $updateStmt->execute();
            $message = "Product quantity increased.";
        } elseif ($action === "decrease") {
            // Decrease quantity by 1, remove if quantity reaches 0
            if ($currentQuantity > 1) {
                $updateStmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ? AND user_id = ?");
                $updateStmt->bind_param("ii", $cartId, $userId);
                $updateStmt->execute();
                $message = "Product quantity decreased.";
            } else {
                // Remove product from cart
                $deleteStmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
                $deleteStmt->bind_param("ii", $cartId, $userId);
                $deleteStmt->execute();
                $message = "Product removed from cart.";
            }
        } elseif ($action === "remove") {
            // Remove product from cart
            $deleteStmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
            $deleteStmt->bind_param("ii", $cartId, $userId);
            $deleteStmt->execute();
            $message = "Product removed from cart.";
        } else {
            $message = "Invalid action.";
        }
    } else {
        $message = "Product not found in your cart.";
    }

    $checkStmt->close();
} else {
    $message = "Invalid request.";
}

$conn->close();

// Redirect back to the cart page with a success or error message
header("Location: cart.php?message=" . urlencode($message));
exit;
