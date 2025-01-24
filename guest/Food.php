<?php
include "../config/productsConfig.php"; // Use the new products database connection

// Fetch all products from the database
$photosPath = "../photos/food_images/"; // Path to your images folder
$stmt = $conn->prepare("SELECT id, name, price, path, kind FROM products"); // Ensure 'kind' is selected
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
    <link rel="stylesheet" type="text/css" href="../css_files/food.css" />
    <title>Food Menu</title>
  </head>
  <body class="food-page">
    <?php require 'includes/navbar.php'; ?>
    <h1>Food Menu</h1>
    <div class="food">
      <?php foreach ($products as $product): ?>
        <?php if (isset($product['kind']) && $product['kind'] == 2): ?> <!-- Check if 'kind' exists and equals 2 -->
          <div class="item">
            <img
              src="../photos/food_images/<?= $product['path'] ?>"
              alt="<?= htmlspecialchars($product['name']) ?>"
            />
            <div class="tooltip">
              <?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>₪
            </div>
            <form method="POST">
              <input type="hidden" name="productDetails" value="ID: <?= $product['id'] ?>, Name: <?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>">
              <button type="submit">Save Product</button>
            </form>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </body>
</html>