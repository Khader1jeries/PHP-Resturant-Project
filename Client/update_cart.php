<?php
session_start();
include "../config/phpdb.php";

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to update your cart.");
}

//  user ID from session
$username = $_SESSION['username'];
$userQuery = "SELECT id FROM clientusers WHERE username = '$username'";
$userResult = mysqli_query($conn, $userQuery);

if (mysqli_num_rows($userResult) === 0) {
    die("Invalid user.");
}
$userId = mysqli_fetch_assoc($userResult)['id'];

// Check if the form was submitted and the required data is present
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['action'])) {
    $cartId = intval($_POST['cart_id']);
    $action = $_POST['action'];

    //  current quantity of the item in the cart
    $checkQuery = "SELECT quantity FROM cart WHERE id = '$cartId' AND user_id = '$userId'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $currentQuantity = mysqli_fetch_assoc($checkResult)['quantity'];

        if ($action === "increase") {
            // Increase quantity by 1
            $updateQuery = "UPDATE cart SET quantity = quantity + 1 WHERE id = '$cartId' AND user_id = '$userId'";
            mysqli_query($conn, $updateQuery);
            $message = "Product quantity increased.";
        } elseif ($action === "decrease") {
            // Decrease quantity by 1, remove if quantity reaches 0
            if ($currentQuantity > 1) {
                $updateQuery = "UPDATE cart SET quantity = quantity - 1 WHERE id = '$cartId' AND user_id = '$userId'";
                mysqli_query($conn, $updateQuery);
                $message = "Product quantity decreased.";
            } else {
                // Remove product from cart
                $deleteQuery = "DELETE FROM cart WHERE id = '$cartId' AND user_id = '$userId'";
                mysqli_query($conn, $deleteQuery);
                $message = "Product removed from cart.";
            }
        } elseif ($action === "remove") {
            // Remove product from cart
            $deleteQuery = "DELETE FROM cart WHERE id = '$cartId' AND user_id = '$userId'";
            mysqli_query($conn, $deleteQuery);
            $message = "Product removed from cart.";
        } else {
            $message = "Invalid action.";
        }
    } else {
        $message = "Product not found in your cart.";
    }
} else {
    $message = "Invalid request.";
}

mysqli_close($conn);

// Redirect back to the cart page with a success or error message
header("Location: cart.php?message=" . urlencode($message));
exit;
