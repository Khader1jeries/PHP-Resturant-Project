<?php
session_start();
include "../config/phpdb.php";

// Get the current logged-in admin's username from the session
$currentAdminUsername = $_SESSION['username'] ?? null;

// Handle user deletion for clientusers
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_client_user'])) {
    $username = $_POST['username'] ?? null;

    if ($username) {
        // Prepare and execute the delete query for clientusers
        $stmt = $conn->prepare("DELETE FROM clientusers WHERE username = ?");
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Client user deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting client user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Invalid request.";
    }

    // Redirect to the same page to refresh the user list
    header("Location: userList.php");
    exit();
}

// Handle user deletion for adminusers
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_admin_user'])) {
    $username = $_POST['username'] ?? null;

    if ($username && $username !== $currentAdminUsername) { // Prevent self-deletion
        // Prepare and execute the delete query for adminusers
        $stmt = $conn->prepare("DELETE FROM adminusers WHERE username = ?");
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Admin user deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting admin user: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "You cannot delete yourself.";
    }

    // Redirect to the same page to refresh the user list
    header("Location: userList.php");
    exit();
}

// Handle form submission to add a new user
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_user'])) {
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
        $stmt = $conn->prepare("SELECT username FROM clientusers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Username already exists, display an error message
            echo "<p style='color: red; text-align: center;'>Error: The username is already taken. Please choose a different one.</p>";
        } else {
            // Insert the user into the database if username doesn't exist
            $stmt = $conn->prepare("INSERT INTO clientusers (username, firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)");
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

// Query the database for all client users automatically when the page loads
$clientUsers = [];
$result = $conn->query("SELECT username, firstname, lastname, email, phone, password, dob FROM clientusers ORDER BY firstname ASC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate age based on DOB
        if (!empty($row['dob'])) {
            $dob = new DateTime($row['dob']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
            $row['age'] = $age; // Add age to the user data
        } else {
            $row['age'] = 'N/A'; // Handle cases where DOB is not set
        }
        $clientUsers[] = $row;
    }
} else {
    echo "<p style='color: red; text-align: center;'>No client users found or error in query.</p>";
}

// Query the database for all admin users automatically when the page loads
$adminUsers = [];
$result = $conn->query("SELECT username, firstname, lastname, email, phone, password, dob FROM adminusers ORDER BY firstname ASC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate age based on DOB
        if (!empty($row['dob'])) {
            $dob = new DateTime($row['dob']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
            $row['age'] = $age; // Add age to the user data
        } else {
            $row['age'] = 'N/A'; // Handle cases where DOB is not set
        }
        $adminUsers[] = $row;
    }
} else {
    echo "<p style='color: red; text-align: center;'>No admin users found or error in query.</p>";
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
        <div style="text-align: center; margin: 20px 0;">
            <a href="createUser.php">
                <button style="padding: 10px 20px; font-size: 16px; cursor: pointer;">
                    Create New User
                </button>
            </a>
        </div>
        <!-- Client Users Table -->
        <h3>Client Users (Sorted by First Name)</h3>
        <?php if (!empty($clientUsers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Age</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['password']); ?></td>
                            <td><?php echo htmlspecialchars($user['age']); ?></td>
                            <td>
                                <!-- Delete button for client users -->
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    <button type="submit" name="delete_client_user" onclick="return confirm('Are you sure you want to delete this client user?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No client users to display.</p>
        <?php endif; ?>

        <!-- Admin Users Table -->
        <h3>Admin Users (Sorted by First Name)</h3>
        <?php if (!empty($adminUsers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Age</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adminUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td><?php echo htmlspecialchars($user['password']); ?></td>
                            <td><?php echo htmlspecialchars($user['age']); ?></td>
                            <td>
                                <?php if ($user['username'] === $currentAdminUsername): ?>
                                    <!-- Display "You" for the current admin -->
                                    <span>You</span>
                                <?php else: ?>
                                    <!-- Delete button for other admin users -->
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                        <button type="submit" name="delete_admin_user" onclick="return confirm('Are you sure you want to delete this admin user?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No admin users to display.</p>
        <?php endif; ?>
    </div>
</body>
</html>