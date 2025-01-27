<?php
include "../config/phpdb.php";
// Handle form submission for creating a product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $kind = $_POST['kind'];
    $stock = $_POST['stock'];

    // Handle image upload
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = ($kind == 2) ? "../photos/food_images/" : "../photos/drinks_images/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate image file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Insert the new product into the database
                $path = basename($_FILES['image']['name']);
                $stmt = $conn->prepare("INSERT INTO products (name, price, path, kind, stock) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sdsii", $name, $price, $path, $kind, $stock);

                if ($stmt->execute()) {
                    echo "<script>alert('Product created successfully!');</script>";
                    // Refresh the page to reflect changes
                    echo "<script>window.location.href = 'createProduct.php';</script>";
                } else {
                    echo "<script>alert('Error creating product: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            } else {
                die("Error uploading image.");
            }
        } else {
            die("Invalid image file type.");
        }
    } else {
        die("No image uploaded.");
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css_files/createProduct.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="css_files/navbar.css">
    <title>Create Product</title>
</head>
<body style="margin-left: 16.5%;">
    <!-- Include Navbar -->
    <?php require 'navbar.php'; ?>

    <div class="container">
        <h1>Create Product</h1>

        <!-- Create Product Form -->
        <div class="create-form">
            <form action="createProduct.php" method="POST" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>

                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>

                <label for="kind">Kind:</label>
                <select id="kind" name="kind" required>
                    <option value="1">Drink</option>
                    <option value="2">Food</option>
                </select>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" required>

                <button type="submit">Create Product</button>
            </form>
        </div>
    </div>
</body>
</html>