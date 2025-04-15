<?php
include "../config/phpdb.php";

function fetchProducts($conn) {
    $query = "SELECT id, name, price, path, kind, stock FROM products"; // Ensure 'kind' is selected
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $products = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $products;
}
?>
