<?php
// conversation_service.php
session_start();
include "../config/phpdb.php";

function getMessageDetails($conn, $messageId) {
    $query = "SELECT id, name, email, message, status, submission_date FROM contact_us WHERE id = $messageId";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getConversationMessages($conn, $messageId) {
    $query = "SELECT sender, message, created_at FROM conversation_messages WHERE contact_id = $messageId ORDER BY created_at ASC";
    $result = mysqli_query($conn, $query);
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    return $messages;
}

function saveResponse($conn, $messageId, $response) {
    $escapedResponse = htmlspecialchars(trim($response));
    $query = "INSERT INTO conversation_messages (contact_id, sender, message) VALUES ($messageId, 'client', '$escapedResponse')";
    mysqli_query($conn, $query);
    header("Location: conversation.php?id=$messageId");
    exit();
}
?>
