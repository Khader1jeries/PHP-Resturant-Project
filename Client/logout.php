<?php
session_start(); // Start the session
session_destroy(); // Destroy the session
header("Location: ../guest/log_in.php"); // Redirect to the login page
exit();
?>