<?php
// Define the path for images and resources
$imagesPath = "photos/index_images/";
$cssPath = "css_files/index.css";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $cssPath ?>" />
    <title>Restaurant</title>
  </head>
  <body id="restaurantBody">
    <div class="Project">
    <?php require 'includes/header.php'; ?>
    <?php require 'includes/navbar.php'; ?>
    <?php require 'includes/iframe.php'; ?>
      <?php require 'includes/footer.php'; ?>
    </div>
  </body>
</html>