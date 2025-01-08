<?php
$imagesPath = "photos/index_images/";
?>

<header class="header" style="background: linear-gradient(-135deg, rgb(63, 63, 63), rgb(182, 165, 132));">
  
    <!-- Login Button -->
    <a href="signin.php" target="main" class="button" style="position: relative; right:20px;">
        Login
    </a>
    
    <!-- Sign Up Button -->
    <a href="sign_Up.php" target="main" class="button" style="position: relative; right:60px;">
        Sign Up
    </a>
    
    <!-- Navbar Background Images -->
    <img src="<?= $imagesPath ?>navBarBackground.png" id="left" style="max-width:250px; max-height:250px;"/>
    
    <!-- Logo -->
    <img src="photos/logo_Images/Logo99.png" id="logo" />
    
    <!-- Navbar Background Image on Right -->
    <img src="<?= $imagesPath ?>navBarBackground.png" id="right" style="max-width:250px; max-height:250px; margin-right: 420px;"/>
    <a href="cart.php" target="main" class="button" style="position: relative; right:60px;">
        Cart
    </a>
</header>
<style>
    /* Buttons */
.button {
  display: inline-block;
  padding: 10px 24px;
  width: 130px;
  margin: 23px;
  background: linear-gradient(-135deg, rgb(60, 54, 35), rgb(150, 135, 109));
  color: #f5f5f5;
  border: 2px solid #666;
  border-radius: 25px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-decoration: none;
  transition: all 0.3s ease-in-out;
  cursor: pointer;
}

.button:hover {
  background: #f5f5f5;
  color: #333;
  border-color: #f5f5f5;
  transform: scale(1.05);
}

/* Button styling when active or focused */
.button:active, .button:focus {
  background: #333;
  color: #f5f5f5;
  outline: none;
}

</style>