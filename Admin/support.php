<?php
// Include necessary configurations and database connections
include "../config/phpdb.php";

// Fetch all messages from the contact_us table
$query = "SELECT id, name, phone, email, message, status, submission_date FROM contact_us ORDER BY submission_date ASC";
$result = $conn->query($query);

// Function to send an email
function sendEmail($to, $subject, $message) {
    $headers = "From: no-reply@yourdomain.com\r\n";
    $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

// Function to check if email exists in the clientusers table
function emailExistsInClientUsers($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM clientusers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Handle the status change request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['changeStatus'])) {
        $messageId = $_POST['messageId'];
        $newStatus = $_POST['status'];

        // Fetch the current message details
        $stmt = $conn->prepare("SELECT email, status FROM contact_us WHERE id = ?");
        $stmt->bind_param("i", $messageId);
        $stmt->execute();
        $stmt->bind_result($email, $currentStatus);
        $stmt->fetch();
        $stmt->close();

        // Update status in the database
        $stmt = $conn->prepare("UPDATE contact_us SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $messageId);
        $stmt->execute();
        $stmt->close();

        // Send email if status is changed to "In Progress", email exists, and email is not in clientusers table
        if ($newStatus == 1 && $currentStatus != 1 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!emailExistsInClientUsers($conn, $email)) {
                $subject = "Your Support Request is In Progress";
                $message = "Hello,Your support request is now in progress. You can view the conversation and respond here:'http://localhost/php-resturant-project/guest/conversation.php?id=$messageId'>Conversation Page</a>.<br><br>Best regards,<br>Support Team";
                mail($email, $subject, $message, $headers);
            
            }
        }

        // Redirect to avoid form resubmission
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
    <style>
        .leftbar .btn {
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
        <p>Manage and respond to customer inquiries. You can change the status of each message below.</p>

        <table class="messages-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Converstion</th>
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
                                <!-- Form to change status -->
                                <form method="POST" action="support.php" style="display:inline;">
                                    <input type="hidden" name="messageId" value="<?php echo $row['id']; ?>" />
                                    <select name="status">
                                        <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>Open</option>
                                        <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="2" <?php echo $row['status'] == 2 ? 'selected' : ''; ?>>Closed</option>
                                    </select><br>
                                    <button type="submit" name="changeStatus" class="change-status-btn">Change Status</button>
                                </form>
                            </td>
                            <td>
                                <!-- Go to Conversation Button (Visible only when status is "In Progress") -->
                                <?php if ($row['status'] == 1): ?>
                                    <button onclick="window.location.href='conversation.php?id=<?php echo $row['id']; ?>'" class="go-to-conversation-btn">
                                        Go to Conversation
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No contact messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>