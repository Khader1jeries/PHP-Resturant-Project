<?php

include "../config/phpdb.php";
include "Service/conversation_service.php";

// Get the message ID from the URL
$messageId = isset($_GET['id']) ? intval($_GET['id']) : 0;

//  the message details
$message = getMessageDetails($conn, $messageId);

// the conversation messages
$conversationMessages = getConversationMessages($conn, $messageId);

// Handle form submission for sending a response
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    saveResponse($conn, $messageId, $_POST['response']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation</title>
    <link rel="stylesheet" href="../Admin/css_files/support.css"> <!-- Link to the CSS file -->
    <style>
        .chat-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .chat-messages {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .message.admin {
            background-color: #007bff;
            color: #fff;
            margin-left: 20%;
        }
        .message.client {
            background-color: #28a745;
            color: #fff;
            margin-right: 20%;
        }
        .message .sender {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message .timestamp {
            font-size: 0.8em;
            color: #ddd;
        }
        .response-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
        }
        .response-form button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .response-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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
                            <div class="message <?php echo $msg['sender'] === 'admin' ? 'admin' : 'client'; ?>">
                                <div class="sender"> <?php echo ucfirst($msg['sender']); ?> </div>
                                <div class="text"> <?php echo htmlspecialchars($msg['message']); ?> </div>
                                <div class="timestamp"> <?php echo $msg['created_at']; ?> </div>
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
