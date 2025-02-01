<?php
session_start();

include_once "../config/phpdb.php"; // Include your database configuration file

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $done = intval($_POST['done']);

    $query = "UPDATE purchases SET done = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $done, $order_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: orders.php"); // Redirect back to the orders page
        exit();
    } else {
        echo "Error updating status.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>