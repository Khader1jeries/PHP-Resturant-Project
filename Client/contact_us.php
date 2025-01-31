<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session to access user data
include "../config/phpdb.php"; // Include the database connection file

// Check if the user is logged in (assuming you store the user's ID in the session)
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit the contact form.");
}

// Fetch the logged-in user's details from the clientusers table
$user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
$stmt = $conn->prepare("SELECT username, phone, email FROM clientusers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("User not found in the database.");
}

$stmt->bind_result($name, $phone, $email);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data (only the message is input by the user)
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation
    $errors = [];

    // Validate message
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

    // If no errors, send the email
    if (empty($errors)) {
        $to = "khader.jeryes@gmail.com"; // Replace with your email
        $subject = "Contact Form Submission from $name";
        $emailMessage = "
        Name: $name\n
        Phone: $phone\n
        Email: $email\n
        Message:\n$message
        ";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Use the built-in mail function
        if (mail($to, $subject, $emailMessage, $headers)) {
            $form_success = "Thank you for contacting us! Your message has been sent.";
        } else {
            $errors[] = "Failed to send email. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css_files/contact_us.css" />
    <title>Contact Us</title>
</head>
<body class="contact-page">
    <!-- Navbar at the top and centered -->
    <?php require 'navbar.php'; ?>

    <!-- Contact Section -->
    <div class="contact">
        <h3>How can we help you?</h3>
        <h1>Contact Us</h1>
        <p>We're here to help and answer any questions you might have.<br>
        We look forward to hearing from you!</p>
        <img src="../photos/contact-us_images/hallo.png" class="contact-img" alt="Contact Image">
        <br>
        <img src="../photos/contact-us_images/placeicon.jpg" class="icon" alt="Location Icon">
        <br><br>
        <a href="https://maps.app.goo.gl/yToY4GeEwLhoiwpPA"> Karmiel, OrtBraude</a>
        <br><br>
        <img src="../photos/contact-us_images/phoneicon.png" class="icon" alt="Phone Icon">
        <br><br>
        <a href="tel:04-20111000">04-20111000</a>
        <br><br>
        <img src="../photos/contact-us_images/mailicon.png" class="icon" alt="Email Icon">
        <br><br>
        <a href="mailto:name@name.com">name@name.com</a>
    </div>

    <!-- Display error messages or success message if form was submitted -->
    <?php if (isset($form_success)): ?>
        <p class="success-message"><?php echo htmlspecialchars($form_success); ?></p>
    <?php elseif (!empty($errors)): ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Contact Form Box -->
    <div class="contact-form">
        <h2>Send us a message</h2>
        <form action="" method="post" id="contactForm">
            <!-- Hidden fields to store user data (optional, for debugging) -->
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <!-- Only the message field is input by the user -->
            <textarea name="message" placeholder="Your Message" id="message" required></textarea>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <script src="../scripts/contact_us.js"></script>
</body>
</html>