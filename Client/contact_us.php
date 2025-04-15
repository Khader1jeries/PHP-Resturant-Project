<?php

session_start(); // Start the session to access user data
include "../config/phpdb.php"; // Include the database connection file

// Check if the user is logged in (assuming you store the user's ID in the session)
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit the contact form.");
}

// Fetch the logged-in user's details from the clientusers table
$user_id = $_SESSION['user_id']; // Assuming the user's ID is stored in the session
$userQuery = "SELECT username, phone, email FROM clientusers WHERE id = '$user_id'";
$userResult = mysqli_query($conn, $userQuery);

if (mysqli_num_rows($userResult) === 0) {
    die("User not found in the database.");
}

$userData = mysqli_fetch_assoc($userResult);
$name = $userData['username'];
$phone = $userData['phone'];
$email = $userData['email'];

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
        $insertQuery = "INSERT INTO contact_us (name, phone, email, message) VALUES ('$name', '$phone', '$email', '$message')";
        if (mysqli_query($conn, $insertQuery)) {
            $form_success = "Thank you for contacting us! We will get back to you soon.";
        } else {
            $errors[] = "An error occurred while submitting the form. Please try again.";
        }
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
    <link rel="stylesheet" type="text/css" href="css_files/contact_us.css" />
    <title>Contact Us</title>
</head>
<body class="contact-page">
    <?php require 'navbar.php'; ?>
    <div style="text-align: center; margin-top: 6%; margin-bottom: 1%;">
    <form action="conversation_list.php" method="get">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <button type="submit" class="conversation-btn">
            Go to Conversations
        </button>
    </form>
</div>

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

    <?php if (isset($form_success)): ?>
        <p class="success-message"><?php echo htmlspecialchars($form_success); ?></p>
    <?php elseif (!empty($errors)): ?>
        <ul class="error-messages">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="contact-form">
        <h2>Send us a message</h2>
        <form action="" method="post" id="contactForm">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <textarea name="message" placeholder="Your Message" id="message" required></textarea>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
    <script src="../scripts/contact_us.js"></script>
</body>
</html>
