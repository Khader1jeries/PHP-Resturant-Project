<?php
// Include necessary configurations and database connections
include "../config/contactUsConfig.php";

// Fetch all messages from the contact_us table
$query = "SELECT id, name, phone, email, message, status, answer, submission_date FROM contact_us ORDER BY submission_date ASC";
$result = $conn->query($query);

// Handle the status change request and answer update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['changeStatus'])) {
        $messageId = $_POST['messageId'];
        $newStatus = $_POST['status'];

        // Update status in the database
        $stmt = $conn->prepare("UPDATE contact_us SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $messageId);
        $stmt->execute();
        $stmt->close();
    }

    // Handle answer submission
    if (isset($_POST['submitAnswer'])) {
        $messageId = $_POST['messageId'];
        $answer = htmlspecialchars(trim($_POST['answer']));

        // Update the answer in the database
        $stmt = $conn->prepare("UPDATE contact_us SET answer = ? WHERE id = ?");
        $stmt->bind_param("si", $answer, $messageId);
        $stmt->execute();
        $stmt->close();

        // Send the answer via email
        $email = $_POST['email'];
        $subject = "Response to your inquiry";
        $emailMessage = "
        Dear Customer,\n\n
        Thank you for your inquiry. Here is our response:\n
        $answer\n\n
        Best regards,\n
        Your Support Team
        ";
        $headers = "From: konanai0699@gmail.com\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Send the email
        mail($email, $subject, $emailMessage, $headers);

        // Refresh the page after sending the answer
        header("Location: support.php");
        exit();
    }
}

// Function to convert status code to human-readable text
function getStatusText($statusCode) {
    switch ($statusCode) {
        case 0:
            return 'Open';
        case 1:
            return 'In Progress';
        case 2:
            return 'Closed';
        default:
            return 'Unknown';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Support - Contact Messages</title>
    <link rel="stylesheet" href="css_files/support.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="css_files/navbar.css"> <!-- Link to the navbar CSS file -->
    <style>.leftbar .btn {
    position: relative;
    right: 30px;   
        
    }    
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php require 'navbar.php'; ?>

    <div class="admin-container" style="margin-left: 600px;margin-top: 80px;">
        <h1>Admin Support - Contact Messages</h1>
        <p>Manage and respond to customer inquiries. You can change the status and provide answers to each message below.</p>

        <table class="messages-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                            <td><?php echo getStatusText($row['status']); ?></td>
                            <td>
                                <form method="POST" action="support.php">
                                    <textarea name="answer" placeholder="Write your answer here" required><?php echo htmlspecialchars($row['answer']); ?></textarea>
                            </td>
                            <td>
                                <input type="hidden" name="messageId" value="<?php echo $row['id']; ?>" />
                                <input type="hidden" name="email" value="<?php echo $row['email']; ?>" />
                                <button type="submit" name="submitAnswer" class="submit-answer-btn">Submit Answer</button>
                                <br><br>
                                <form method="POST" action="support.php">
                                    <input type="hidden" name="messageId" value="<?php echo $row['id']; ?>" />
                                    <select name="status">
                                        <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>Open</option>
                                        <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="2" <?php echo $row['status'] == 2 ? 'selected' : ''; ?>>Closed</option>
                                    </select>
                                    <button type="submit" name="changeStatus" class="change-status-btn">Change Status</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No contact messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>