<?php
if (!isset($_GET['purchase_id'])) {
    header("Location: cart.php");
    exit();
}

$purchaseId = intval($_GET['purchase_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Success</title>
    <link rel="stylesheet" href="css_files/purchase_success.css">
</head>
<body>
    <div class="success-container">
        <h1>Thank You for Your Purchase!</h1>
        <p>Your order has been successfully placed.</p>
        <p>Your purchase ID is <strong>#<?php echo htmlspecialchars($purchaseId); ?></strong>.</p>
        <a href="orders.php" class="cta-button">View Your Orders</a>
        <a href="food.php" class="cta-button">Continue Shopping</a>
    </div>
</body>
</html>
