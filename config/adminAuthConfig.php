<?php
$host = "localhost"; // Your database host
$username = "root"; // Your MySQL username (default for most setups)
$password = "1234"; // Your MySQL password (leave empty for XAMPP/MAMP)
$dbname = "adminUser_authentication"; // The name of your database

// Connect to the database
$adminConn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($adminConn->connect_error) {
    die("Connection failed: " . $adminConn->connect_error);
}
?>
