<?php
include "Service/product_service.php";
$photosPath = "../photos/drinks_images/"; // Path to your images folder
$products = fetchProducts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="../css_files/drink.css" />
    <title>Drink Menu</title>
</head>
<body class="drink-page">
    <?php require 'includes/navbar.php'; ?>
    <h1>Drink Menu</h1>
    <div class="drink">
        <?php foreach ($products as $product): ?>
            <?php if (isset($product['kind']) && $product['kind'] == 1): ?> 
                <div class="item">
                    <img src="<?= $photosPath . $product['path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                    <div class="tooltip">
                        <?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
