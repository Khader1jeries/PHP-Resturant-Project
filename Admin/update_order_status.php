<?php
session_start();

include_once "../config/phpdb.php"; // Include your database configuration file

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $done = intval($_POST['done']);

    // Use mysqli_query instead of prepared statements
    $query = "UPDATE purchases SET done = $done WHERE id = $order_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: orders.php"); // Redirect back to the orders page
        exit();
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
