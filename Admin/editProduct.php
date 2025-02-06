<?php
include "../config/phpdb.php";

// products from the database
$query = "SELECT id, name, price, path, kind, stock FROM products";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Handle form submission for editing or deleting a product
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Handle product deletion
        $id = intval($_POST['id']);
        $query = "DELETE FROM products WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $message = "<p class='success'>Product deleted successfully!</p>";
        } else {
            $message = "<p class='error'>Error deleting product: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    } else {
        // Handle product update
        $id = intval($_POST['id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = floatval($_POST['price']);
        $kind = intval($_POST['kind']);
        $stock = intval($_POST['stock']);

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = ($kind == 2) ? "../photos/food_images/" : "../photos/drinks_images/";
            $targetFile = $targetDir . basename($_FILES['image']['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $path = basename($_FILES['image']['name']);
                    $query = "UPDATE products SET name = '$name', price = $price, path = '$path', kind = $kind, stock = $stock WHERE id = $id";
                } else {
                    $message = "<p class='error'>Error uploading image.</p>";
                }
            } else {
                $message = "<p class='error'>Invalid image file type.</p>";
            }
        } else {
            $query = "UPDATE products SET name = '$name', price = $price, kind = $kind, stock = $stock WHERE id = $id";
        }

        if (mysqli_query($conn, $query)) {
            $message = "<p class='success'>Product updated successfully!</p>";
        } else {
            $message = "<p class='error'>Error updating product: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
        }
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_files/navbar.css">
    <link rel="stylesheet" href="css_files/editProduct.css">
    <title>Edit Products</title>
</head>
<body style="margin-left: 17%;">

    <?php require 'navbar.php'; ?>

 
    <div id="message-container" style="text-align: center; margin-top: 20px;">
        <?php echo $message; ?>
    </div>

    <div class="container">
        <h1>Edit Products</h1>

      
        <h2>Food Menu</h2>
        <div class="food">
            <?php foreach ($products as $product): ?>
                <?php if (isset($product['kind']) && $product['kind'] == 2): ?>
                    <div class="item">
                        <img src="../photos/food_images/<?= $product['path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                        <button class="button" onclick="toggleEditForm(<?= $product['id'] ?>)">Edit Product</button>

                        <!-- Edit Form for this product -->
                        <div id="edit-form-<?= $product['id'] ?>" class="edit-form">
                            <span class="close-btn" onclick="toggleEditForm(<?= $product['id'] ?>)">&times;</span>
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

                                <div class="file-input-container">
                                    <label for="image">Upload New Image:</label>
                                    <input type="file" id="image" name="image">
                                </div>

                                <button type="submit">Update Product</button>
                                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete Product</button>
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
                <?php if (isset($product['kind']) && $product['kind'] == 1): ?>
                    <div class="item">
                        <img src="../photos/drinks_images/<?= $product['path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                        <div class="tooltip"><?= htmlspecialchars($product['name']) ?>, Price: <?= $product['price'] ?>₪</div>
                        <button onclick="toggleEditForm(<?= $product['id'] ?>)">Edit Product</button>

                        <!-- Edit Form for this product -->
                        <div id="edit-form-<?= $product['id'] ?>" class="edit-form">
                            <span class="close-btn" onclick="toggleEditForm(<?= $product['id'] ?>)">&times;</span>
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

                                <div class="file-input-container">
                                    <label for="image">Upload New Image:</label>
                                    <input type="file" id="image" name="image">
                                </div>
                                <button type="submit">Update Product</button>
                                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete Product</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleEditForm(productId) {
            const editForm = document.getElementById('edit-form-' + productId);
            if (editForm.style.display === 'none' || editForm.style.display === '') {
                editForm.style.display = 'block';
            } else {
                editForm.style.display = 'none';
            }
        }
    </script>
</body>
</html>