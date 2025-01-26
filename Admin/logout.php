<?php
// Start the session to access session variables
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to the login page or home page
header("Location: ../guest/index.php"); // Or redirect to home.php
exit();
?>
