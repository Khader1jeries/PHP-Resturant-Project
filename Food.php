<?php
include "config/productsConfig.php"; // Use the new products database connection

// Fetch all products from the database
$photosPath = "photos/food_images/"; // Path to your images folder
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
    <link rel="stylesheet" type="text/css" href="css_files/product.css" />
    <title>Food Menu</title>
 <style>
        /* General Flexbox Layout for Food, Drinks, Ice-Cream */
        .food,
        .drink,
        .ice-cream {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
            margin-top: 20px;
        }

        /* Styling Each Item Container */
        .item {
            position: relative;
            background-color: rgba(0, 0, 0, 0.7); /* Slight transparency */
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 200px;
            height: 400px;
            text-align: center;
            padding: 20px 10px; /* Increase padding to give space for tooltip */
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px); /* Adding blur effect behind the items */
        }

        /* Adjust image styling (optional) */
        img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10%;
            filter: grayscale(40%);
            transition: transform 0.7s ease, filter 0.7s ease;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
        }

        img:hover {
            transform: scale(0.95);
            filter: grayscale(0%);
        }

        /* Tooltip Styling */
        .tooltip {
            position: absolute;
            bottom: 10px; /* Adjust position to ensure tooltip doesn't go below the item */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px;
            border-radius: 5px;
            white-space: nowrap;
            z-index: 999;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0s 0.3s;
            border: 1px solid rgb(172, 156, 120);
        }

        .item:hover .tooltip {
            visibility: visible;
            opacity: 1;
            transition-delay: 0s;
        }

        /* Form and Button Styling */
        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Modern look for the select element */
        select {
            padding: 10px;
            width: 50%; /* Adjust width as needed */
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f0f0f0; /* Light background color */
            color: #333; /* Text color */
            font-size: 16px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        select:focus {
            border-color: #5c5c5c; /* Darker border color on focus */
            background-color: #e8e8e8; /* Slightly darker background */
        }

        form select {
            margin-bottom: 15px; /* Adjust the space between select and button */
        }

        button {
            cursor: pointer;
            background: linear-gradient(-135deg, rgb(46, 42, 28), rgb(82, 74, 61));
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 50%; /* Same width as select for consistency */
        }

        button:hover {
            background: linear-gradient(-135deg, rgb(46, 42, 28), rgb(82, 74, 61));
            transform: scale(1.05);
        }

        /* Page Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('photos/drinks_images/GoldBorderWallpaper.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        h2 {
            font-size: 1.8em;
            margin-top: 40px;
            color: #ccc;
        }

        label {
            margin-top: 10px;
            font-size: 16px;
        }

        select {
            margin-top: 5px;
        }

        #result {
            margin-top: 20px;
            font-size: 18px;
            color: #f4f4f4;
        }

        input[type="text"] {
            padding: 10px;
            width: 40%;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin: 10px 0;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
  </head>
  <body>
    <h1>Food Menu</h1>
    <div class="food">
      <?php foreach ($products as $product): ?>
        <?php if (isset($product['kind']) && $product['kind'] == 2): ?> <!-- Check if 'kind' exists and equals 1 -->
          <div class="item">
            <img
              src="photos/food_images/<?= $product['path'] ?>"
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