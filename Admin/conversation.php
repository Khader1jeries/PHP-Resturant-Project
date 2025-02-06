<?php
// Include necessary configurations and database connections
include "../config/phpdb.php";

// Get the message ID from the URL
$messageId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the message details from the contact_us table
$query = "SELECT id, name, email, message, status, submission_date FROM contact_us WHERE id = '$messageId'";
$result = mysqli_query($conn, $query);
$message = mysqli_fetch_assoc($result);

// Fetch the conversation messages from the conversation_messages table
$query = "SELECT sender, message, created_at FROM conversation_messages WHERE contact_id = '$messageId' ORDER BY created_at ASC";
$conversationResult = mysqli_query($conn, $query);
$conversationMessages = [];
while ($row = mysqli_fetch_assoc($conversationResult)) {
    $conversationMessages[] = $row;
}

// Handle form submission for sending a response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = htmlspecialchars(trim($_POST['response']));
    $insertQuery = "INSERT INTO conversation_messages (contact_id, sender, message) VALUES ('$messageId', 'admin', '$response')";
    mysqli_query($conn, $insertQuery);
    
    // Redirect to avoid form resubmission
    header("Location: conversation.php?id=$messageId");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation</title>
    <link rel="stylesheet" href="css_files/conversation.css">
    <link rel="stylesheet" href="css_files/navbar.css">
</head>
<body>
<a href="support.php" class="back-button">Back to Support</a>
    <?php require 'navbar.php'; ?>
    <div class="admin-container">
        <h1>Conversation</h1>
        <p>Manage the conversation for this message.</p>

        <?php if ($message): ?>
            <div class="message-details">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($message['submission_date']); ?></p>
            </div>

            <div class="chat-container">
                <div class="chat-messages">
                    <?php if (empty($conversationMessages)): ?>
                        <p>No messages yet. Start the conversation!</p>
                    <?php else: ?>
                        <?php foreach ($conversationMessages as $msg): ?>
                            <div class="message <?php echo $msg['sender']; ?>">
                                <div class="sender"><?php echo ucfirst($msg['sender']); ?></div>
                                <div class="text"><?php echo htmlspecialchars($msg['message']); ?></div>
                                <div class="timestamp"><?php echo $msg['created_at']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form method="POST" action="conversation.php?id=<?php echo $messageId; ?>" class="response-form">
                    <textarea name="response" placeholder="Write your response here" required></textarea>
                    <button type="submit" class="submit-response-btn">Send Response</button>
                </form>
            </div>
        <?php else: ?>
            <p>Message not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>