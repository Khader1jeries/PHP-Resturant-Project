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
  <body class="index-page">
    <div class="Project">
      <?php require 'includes/navbar.php'; ?>

      <!-- New Content Section -->
      <div class="content-section">
        <div class="text-content">
          <h1>HUMAN CONNECTION IN A DIGITAL-FIRST WORLD.</h1>
          <p>Resident experiences are designed to deepen relationships.</p>
          <p>We host events in private luxury apartments and exclusive members-only clubs, featuring top-class culinary talent from renowned restaurants like Blue Hill, Carbone, Eleven Madison Park, Home, and Per Se.</p>
          <p>Together, we create one-of-a-kind, engaging, and memorable evenings that drive conversation and create connection.</p>
          <p>Reserve your tickets to a dinner below, or book a private experience <a href="#">here</a>.</p>
          <a href="Food.php" class="cta-button">VIEW UPCOMING DINNERS</a>
        </div>
        <div class="image-content">
          <img src="photos/index_images/indexStaick.jpg" alt="Restaurant Image">
        </div>
      </div>

      <?php require 'includes/footer.php'; ?>
    </div>
  </body>
</html>