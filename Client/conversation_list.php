<?php
// Include necessary configurations and database connections
include "../config/phpdb.php";

// Get email from URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Fetch messages for the specific email from the contact_us table
$query = "SELECT id, name, phone, email, message, status, submission_date FROM contact_us WHERE email = ? ORDER BY submission_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Function to convert status code to human-readable text
function getStatusText($statusCode) {
    switch ($statusCode) {
        case 0:
            return 'Pending';
        case 1:
            return 'In Progress';
        case 2:
            return 'Finished';
        default:
            return 'Unknown';
    }
}

$rows = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Messages</title>
    <link rel="stylesheet" href="../Admin/css_files/support.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="css_files/navbar.css"> <!-- Link to the navbar CSS file -->
</head>
<body>
    <!-- Include Navbar -->
    <?php require 'navbar.php'; ?>

    <div class="admin-container" style="margin-left: 600px;margin-top: 80px;">
        <h1>Support Messages</h1>
        <p>View your submitted inquiries below.</p>

        <table class="messages-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Conversation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo getStatusText($row['status']); ?></td>
                        <td>
                            <?php if ($row['status'] == 1): ?>
                                <button onclick="window.location.href='conversation.php?id=<?php echo $row['id']; ?>'" class="go-to-conversation-btn">
                                    Go to Conversation
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
