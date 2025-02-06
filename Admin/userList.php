<?php
session_start();
include "../config/phpdb.php";

// Get the current logged-in admin's username from the session
$currentAdminUsername = $_SESSION['username'] ?? null;

// Handle user deletion for clientusers
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_client_user'])) {
    $username = $_POST['username'] ?? null;

    if ($username) {
        // Check if the user has open purchases
        $checkQuery = "SELECT COUNT(*) as open_purchases FROM purchases WHERE user_id = (SELECT id FROM clientusers WHERE username = '$username') AND done = 0";
        $checkResult = mysqli_query($conn, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);

        if ($row['open_purchases'] > 0) {
            $_SESSION['message'] = "Cannot delete user. They have open purchases.";
        } else {
           
            mysqli_begin_transaction($conn);

            try {
                // First, delete all purchases associated with the user
                $deletePurchasesQuery = "DELETE FROM purchases WHERE user_id = (SELECT id FROM clientusers WHERE username = '$username')";
                if (!mysqli_query($conn, $deletePurchasesQuery)) {
                    throw new Exception("Error deleting purchases: " . mysqli_error($conn));
                }

                // Second, delete all login history associated with the user's email
                $deleteLoginHistoryQuery = "DELETE FROM client_login_history WHERE email = (SELECT email FROM clientusers WHERE username = '$username')";
                if (!mysqli_query($conn, $deleteLoginHistoryQuery)) {
                    throw new Exception("Error deleting login history: " . mysqli_error($conn));
                }

                // Finally, delete the user
                $deleteUserQuery = "DELETE FROM clientusers WHERE username = '$username'";
                if (!mysqli_query($conn, $deleteUserQuery)) {
                    throw new Exception("Error deleting client user: " . mysqli_error($conn));
                }

                // Commit the transaction if all queries succeed
                mysqli_commit($conn);
                $_SESSION['message'] = "Client user, associated purchases, and login history deleted successfully.";
            } catch (Exception $e) {
                // Rollback the transaction in case of any error
                mysqli_rollback($conn);
                $_SESSION['message'] = $e->getMessage();
            }
        }
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
        $query = "DELETE FROM adminusers WHERE username = '$username'";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Admin user deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting admin user: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['message'] = "You cannot delete yourself.";
    }

    // Redirect to the same page to refresh the user list
    header("Location: userList.php");
    exit();
}

// Query the database for all client users automatically when the page loads
$clientUsers = [];
$result = mysqli_query($conn, "SELECT username, firstname, lastname, email, phone, password, dob FROM clientusers ORDER BY firstname ASC");

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
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
$result = mysqli_query($conn, "SELECT username, firstname, lastname, email, phone, password, dob FROM adminusers ORDER BY firstname ASC");

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
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


mysqli_close($conn);
?>
<?php
// Display session message if it exists
if (isset($_SESSION['message'])) {
    echo "<div style='text-align: center; color: red; margin: 20px 0;'>" . $_SESSION['message'] . "</div>";
    // Clear the message after displaying it
    unset($_SESSION['message']);
}
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
                        <th>Actions</th>
                        <th>History</th>
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
                                <!-- Delete button -->
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    <button type="submit" name="delete_client_user" onclick="return confirm('Are you sure you want to delete this client user?');">Delete</button>
                                </form>
                            </td>
                            <td>    
                                <!-- Login History Button -->
                                <a href="login_history.php?username=<?php echo urlencode($user['username']); ?>">
                                    <button>Login History</button>
                                </a>
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
                        <th>Actions</th>
                        <th>History</th>
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
                                    <span>You</span>
                                <?php else: ?>
                                    <!-- Delete button -->
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                        <button type="submit" name="delete_admin_user" onclick="return confirm('Are you sure you want to delete this admin user?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Login History Button -->
                                <a href="login_history.php?username=<?php echo urlencode($user['username']); ?>">
                                    <button>Login History</button>
                                </a>
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