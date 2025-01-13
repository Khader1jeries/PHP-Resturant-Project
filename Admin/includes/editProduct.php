<?php
include "../../config/productsConfig.php"; // Include database connection

// Fetch all products from the database
$stmt = $conn->prepare("SELECT id, name, price, path, kind, stock FROM products"); // Added 'stock' to the query
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle form submission for editing a product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $kind = $_POST['kind'];
    $stock = $_POST['stock']; // Added 'stock' to the form submission

    // Handle image upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = ($kind == 2) ? "../../photos/food_images/" : "../../photos/drinks_images/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate image file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Update the product's image path in the database
                $path = basename($_FILES['image']['name']);
                $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, path = ?, kind = ?, stock = ? WHERE id = ?");
                $stmt->bind_param("sdsiii", $name, $price, $path, $kind, $stock, $id);
            } else {
                die("Error uploading image.");
            }
        } else {
            die("Invalid image file type.");
        }
    } else {
        // No new image uploaded, update only name, price, kind, and stock
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, kind = ?, stock = ? WHERE id = ?");
        $stmt->bind_param("sdiii", $name, $price, $kind, $stock, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!');</script>";
        // Refresh the page to reflect changes
        echo "<script>window.location.href = 'editProduct.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css_files/product.css" />
    <link rel="stylesheet" type="text/css" href="../../css_files/product_v2.css" />
    <title>Edit Products</title>
    <style>
        /* My Account Dropdown Styling */
        #accountDetails {
            top: 80px;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: left; /* Ensures text aligns left to right */
            padding: 10px; /* Adds some padding to ensure the text is spaced well */
        }

        /* Edit Form Styling */
        .edit-form {
            display: none;
            position: absolute; /* Position the form absolutely */
            
            top: 250px; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust for exact centering */
            z-index: 1000; /* Ensure it appears above other elements */
            background: #000;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.95);
            width: 200px; /* Set a fixed width */
        }

        .edit-form.active {
            display: block; /* Show the form when active */
        }
    </style>
</head>
<body>
    <h1>Edit Products</h1>

    <!-- Food Menu -->
    <h2>Food Menu</h2>
    <div class="food">
        <?php foreach ($products as $product): ?>
            <?php if (isset($product['kind']) && $product['kind'] == 2): ?> <!-- Check if 'kind' exists and equals 2 (food) -->
                <div class="item">
                    <img
                        src="../../photos/food_images/<?= $product['path'] ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                    />
                    <button class="button" onclick="toggleEditForm(<?= $product['id'] ?>)">Edit Product</button>

                    <!-- Edit Form for this product -->
                    <div id="edit-form-<?= $product['id'] ?>" class="edit-form">
                        <form action="editProduct.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="kind" value="<?= $product['kind'] ?>">

                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required>

                            <label for="stock">Stock:</label>
                            <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" required>

                            <label for="kind">Kind:</label>
                            <select id="kind" name="kind" required>
                                <option value="1" <?= $product['kind'] == 1 ? 'selected' : '' ?>>Drink</option>
                                <option value="2" <?= $product['kind'] == 2 ? 'selected' : '' ?>>Food</option>
                            </select>

                            <label for="image">Upload New Image:</label>
                            <input type="file" id="image" name="image">

                            <button type="submit">Update Product</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Drink Menu -->
    <h2>Drink Menu</h2>
    <div class="drink">
        <?php foreach ($products as $product): ?>
            <?php if (isset($product['kind']) && $product['kind'] == 1): ?> <!-- Check if 'kind' exists and equals 1 (drinks) -->
                <div class="item">
                    <img
                        src="../../photos/drinks_images/<?= $product['path'] ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>"
                    />
                    <div class="tooltip">
                        <?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>₪
                    </div>
                    <button onclick="toggleEditForm(<?= $product['id'] ?>)">Edit Product</button>

                    <!-- Edit Form for this product -->
                    <div id="edit-form-<?= $product['id'] ?>" class="edit-form">
                        <form action="editProduct.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="kind" value="<?= $product['kind'] ?>">

                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required>

                            <label for="stock">Stock:</label>
                            <input type="number" id="stock" name="stock" value="<?= $product['stock'] ?>" required>

                            <label for="kind">Kind:</label>
                            <select id="kind" name="kind" required>
                                <option value="1" <?= $product['kind'] == 1 ? 'selected' : '' ?>>Drink</option>
                                <option value="2" <?= $product['kind'] == 2 ? 'selected' : '' ?>>Food</option>
                            </select>

                            <label for="image">Upload New Image:</label>
                            <input type="file" id="image" name="image">

                            <button type="submit">Update Product</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <script>
        function toggleEditForm(productId) {
            // Get the edit form for the specific product
            const editForm = document.getElementById('edit-form-' + productId);

            // Toggle the visibility of the edit form
            if (editForm.style.display === 'none' || editForm.style.display === '') {
                editForm.style.display = 'block';
            } else {
                editForm.style.display = 'none';
            }
        }
    </script>
</body>
</html>