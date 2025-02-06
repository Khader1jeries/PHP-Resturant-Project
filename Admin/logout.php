<?php
session_start();
session_unset();
session_destroy();

// Redirect to the login page or home page
header("Location: ../guest/index.php"); 
exit();
?>
