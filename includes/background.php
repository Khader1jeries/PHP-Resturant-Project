
<?php
// Define the path for images and resources
$imagesPath = "../photos/index_images/";
$cssPath = "../css_files/maincss.css";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?= $cssPath ?>" />
    <title>Restaurant</title>
    <style>
      /* Inline CSS for max height and max width */
      body {
        max-width: 400px;
        max-height: 400px;
        margin: 0 auto; /* Center the body */
        overflow: hidden; /* Prevent overflow */
        background-size: cover;
        background-position: center;
      }
    </style>
  </head>
  <body id="restaurantBody" style="background-image: url('<?= $imagesPath ?>bg99.jpg') ">   
  </body>
</html>

<script>
      document.addEventListener("DOMContentLoaded", function () {
        var images = [
          "../photos/index_images/bg3.jpg",
          "../photos/index_images/bg1.jpg",
          "../photos/index_images/bg2.jpg"
        ];
        var currentIndex = 0;
        var bodyElement = document.getElementById("restaurantBody");

        function changeBackground() {
          currentIndex = (currentIndex + 1) % images.length;
          bodyElement.style.backgroundImage = "url('" + images[currentIndex] + "')";
        }

        changeBackground();

        setInterval(changeBackground, 3000);
      });
    </script>

