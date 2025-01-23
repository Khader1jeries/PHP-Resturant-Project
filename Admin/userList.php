<?php
session_start();
include "../config/userAuthConfig.php"; // Include the database connection

// Handle form submission to add a new user
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Safely retrieve form values using null coalescing operator
    $username = $_POST['username'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $password = $_POST['password'] ?? null; // No hashing for password here

    // Check for null values before proceeding
    if ($username && $firstname && $lastname && $email && $phone && $password) {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Username already exists, display an error message
            echo "<p style='color: red; text-align: center;'>Error: The username is already taken. Please choose a different one.</p>";
        } else {
            // Insert the user into the database if username doesn't exist
            $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $phone, $password);

            if ($stmt->execute()) {
                echo "<p style='color: green; text-align: center;'>Sign-up successful! You can now log in.</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
            }
        }

        $stmt->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Please fill out all required fields.</p>";
    }
}

// Query the database for all users automatically when the page loads
$sortedUsers = [];

// Query the database for all users
$result = $conn->query("SELECT username, firstname, lastname, email, phone, password FROM users ORDER BY firstname ASC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sortedUsers[] = $row;
    }
} else {
    echo "<p style='color: red; text-align: center;'>No users found or error in query.</p>";
}

// Close the connection after all operations
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/userList.css" />
    <title>View Users</title>
</head>
<body>
    <div class="container">
    <?php require 'navbar.php'; ?>
        <h3>Users (Sorted by First Name)</h3>
        <?php if (!empty($sortedUsers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th> <!-- Add this column for the password -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sortedUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['password']); ?></td> <!-- Display the password -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No users to display.</p>
        <?php endif; ?>
    </div>
</body>
</html>
