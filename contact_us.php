<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include "config/contactUsConfig.php"; // Use the same database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation
    $errors = [];

    // Validate name (Ensure it's not empty and contains only letters and spaces)
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name can only contain letters and spaces.";
    }

    // Validate phone number (Ensure it's exactly 10 digits)
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits (only numbers).";
    }

    // Validate email (Ensure it's a valid email format)
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate message (Ensure it's not empty)
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    // If there are no errors, process the form (e.g., save data to the database)
    if (empty($errors)) {
        // Prepare the SQL statement to insert data into the contact_us table
        $stmt = $conn->prepare("INSERT INTO contact_us (name, phone, email, message) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssss", $name, $phone, $email, $message);

        // Execute the statement
        if ($stmt->execute()) {
            $form_success = "Thank you for contacting us! We will get back to you soon.";
        } else {
            $errors[] = "An error occurred while submitting the form. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css_files/contact_us.css" />
   
</head>
<body>
    <div class="contact">
        <h3>How can we help you?</h3>
        <h1>Contact Us</h1>
        <p>We're here to help and answer any questions you might have.<br>
        We look forward to hearing from you!</p>
        <img src="photos/contact-us_images/hallo.png" class="contact-img" style="width: 400px; float: right;">
        <br>
        <img src="photos/contact-us_images/placeicon.jpg" class="icon">
        <br><br>
        <a href="https://maps.app.goo.gl/yToY4GeEwLhoiwpPA"> Karmiel, OrtBraude</a>
        <br><br>
        <img src="photos/contact-us_images/phoneicon.png" class="icon">
        <br><br>
        <a href="tel:04-20111000">04-20111000</a>
        <br><br>
        <img src="photos/contact-us_images/mailicon.png" class="icon">
        <br><br>
        <a href="mailto:name@name.com">name@name.com</a>
    </div>

    <!-- Display error messages or success message if form was submitted -->
    <?php if (isset($form_success)): ?>
        <p style="color: green; text-align: center;">
            <?php echo htmlspecialchars($form_success); ?>
        </p>
    <?php elseif (!empty($errors)): ?>
        <ul style="color: red; text-align: center;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<!-- Contact Form Box -->
<div class="contact-form">
    <h2>Send us a message</h2>
    <form action="" method="post" id="contactForm">
        <input type="text" name="name" placeholder="Your Name" id="name" required>
        <input type="text" name="phone" placeholder="Your Phone Number" id="phone" required>
        <input type="email" name="email" placeholder="Your Email Address" id="email" required>
        <textarea name="message" placeholder="Your Message" id="message" required></textarea>
        <button type="submit" class="submit-btn">Submit</button>
    </form>
</div>
<script src="scripts/contact_us.js"></script>
</body>
</html>
