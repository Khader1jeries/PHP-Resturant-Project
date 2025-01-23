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
    <link rel="stylesheet" href="../css_files/sign_up.css" />
    <style>/* Global styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    padding: 20px;
}

/* Admin container */
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
}

p {
    text-align: center;
    font-size: 1rem;
    margin-bottom: 30px;
}

/* Table styles */
.messages-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.messages-table th, .messages-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.messages-table th {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
}

.messages-table td {
    background-color: #f9f9f9;
}

.messages-table tr:nth-child(even) td {
    background-color: #f1f1f1;
}

/* Form styles */
textarea {
    width: 100%;
    height: 100px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    margin-bottom: 10px;
}

select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    margin-right: 10px;
}

button {
    padding: 10px 20px;
    margin-left: 30px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

button:disabled {
    background-color: #bbb;
    cursor: not-allowed;
}

/* Styling for the Answer section */
.submit-answer-btn {
    background-color: #007BFF;
    margin-top: 10px;
}

.submit-answer-btn:hover {
    background-color: #0056b3;
}

.change-status-btn {
    background-color: #28a745;
    margin-top: 10px;
}

.change-status-btn:hover {
    background-color: #218838;
}

/* Centering forms and buttons */
form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

form input[type="hidden"] {
    display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-container {
        padding: 20px;
    }

    h1 {
        font-size: 1.5rem;
    }

    .messages-table th, .messages-table td {
        padding: 10px;
    }

    textarea {
        height: 80px;
    }

    select, button {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
<link rel="stylesheet" href="css_files/navbar.css">
</head>
<body>
    <?php require 'navbar.php'; ?>
    <div class="admin-container">
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
