<header class="header" style="background: linear-gradient(-135deg, rgb(63, 63, 63), rgb(182, 165, 132));">
    <!-- My Account Button -->
    <?php if (isset($_SESSION['username'])): ?>
        <div style="position: relative; right:20px; text-align: right;">
            <!-- Greeting with User's Name -->
            <button class="button" onclick="toggleAccountDetails()">My Account</button>
            <span style="color: white; font-size: 1.2rem;">Hi, <?= htmlspecialchars($user['firstname']) ?></span>

            <!-- Account Details and Logout -->
            <div id="accountDetails" style="display:none; background: rgba(0,0,0,0.8); padding: 15px; border-radius: 8px; color: white;">
                <h4>User Details:</h4>
                <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>First Name:</strong> <?= htmlspecialchars($user['firstname']) ?></p>
                <p><strong>Last Name:</strong> <?= htmlspecialchars($user['lastname']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
                
                <!-- Logout Option (Text or Button) -->
                <form action="logout.php" method="post" style="display:inline;">
                    <button type="submit" class="button" style="background-color: red; color: white;">Logout</button>
                    <!-- Or you can use a regular link -->
                    <!-- <a href="logout.php" style="color: red; text-decoration: none;">Logout</a> -->
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navbar Background Images -->
    <img src="<?= $imagesPath ?>navBarBackground.png" id="left" style="max-width:250px; max-height:250px;"/>

    <!-- Logo -->
    <img src="photos/logo_Images/Logo99.png" id="logo" />

    <!-- Navbar Background Image on Right -->
    <img src="<?= $imagesPath ?>navBarBackground.png" id="right" style="max-width:250px; max-height:250px; margin-right: 420px;"/>
</header>

<script>
function toggleAccountDetails() {
    const details = document.getElementById('accountDetails');
    details.style.display = details.style.display === 'block' ? 'none' : 'block';
}
</script>

<style>
/* My Account Dropdown Styling */
#accountDetails {
    position: absolute;
    top: 80px;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    background: rgba(0,0,0,0.8);
    color: white;
    z-index: 9999; /* Add a higher z-index */
    text-align: left; /* Ensures text aligns left to right */
    padding: 10px; /* Adds some padding to ensure the text is spaced well */
}
</style>
