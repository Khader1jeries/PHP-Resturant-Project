<?php
$host = "localhost"; // Your database host
$username = "root"; // Your MySQL username (default for most setups)
$password = "1234"; // Your MySQL password (leave empty for XAMPP/MAMP)
$dbname = "user_authentication"; // The name of your database

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
