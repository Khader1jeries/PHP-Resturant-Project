<?php
session_start();
include "config/userAuthConfig.php"; // Include the database connection

// Handle form submission to add a new user
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Safely retrieve form values using null coalescing operator
    $username = $_POST['username'] ?? null;
    $firstname = $_POST['firstname'] ?? null;
    $lastname = $_POST['lastname'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hash the password

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

// Handle show/hide users toggle
if (!isset($_SESSION['show_users'])) {
    $_SESSION['show_users'] = false;
}

if (isset($_POST['show_users'])) {
    $_SESSION['show_users'] = !$_SESSION['show_users'];  // Toggle visibility
}

$showUsers = $_SESSION['show_users'];

// Initialize the users array to avoid undefined variable warnings
$sortedUsers = [];

// Query the database for all users if $showUsers is true
if ($showUsers) {
    $result = $conn->query("SELECT username, firstname, lastname, email, phone FROM users ORDER BY firstname ASC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sortedUsers[] = $row;
        }
    }
}

// Close the connection after all operations
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/sign_in.css" />
    <title>Sign Up and View Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(-135deg, wheat, gray);
            height: 100%;
            margin: 0;
            overflow: scroll;
        }

        .container {
            width: 560px;
            margin: 0 auto;
            margin-top: 100px;
            margin-bottom: 18px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 15px 20px rgba(0, 0, 0, 0.1);
            padding-bottom: 30px;
        }

        h2 {
            font-size: 35px;
            font-weight: 600;
            text-align: center;
            color: black;
            user-select: none;
            border-radius: 15px 15px 0 0;
            background: linear-gradient(-135deg, wheat, gray);
            padding: 10px 0;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
        }

        input[type="submit"] {
            color: black;
            border: none;
            width: 80%;
            border-radius: 20px;
            padding: 15px;
            margin-top: 20px;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(-135deg, wheat, gray);
            transition: all 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input[type="submit"]:hover {
            box-shadow: rgba(49, 49, 49, 0.35) 0 -25px 18px -14px inset,
                rgba(49, 49, 49, 0.35) 0 1px 2px, rgba(49, 49, 49, 0.35) 0 2px 4px,
                rgba(49, 49, 49, 0.35) 0 4px 8px, rgba(81, 81, 81, 0.25) 0 8px 16px,
                rgba(97, 97, 97, 0.25) 0 16px 32px;
            transform: scale(0.95);
            transition: 0.8s ease;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: linear-gradient(-135deg, wheat, gray);
            color: white;
        }

        button {
            position: relative;
            left: 37%;
            background-color:rgb(52, 52, 52);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color:rgb(0, 0, 0);
        }

        /* Custom Scrollbar Styling */
        body::-webkit-scrollbar {
            width: 12px;  /* Scrollbar width */
        }

        body::-webkit-scrollbar-track {
            background: linear-gradient(-135deg, wheat, gray);  /* Scrollbar track background matching the page */
            border-radius: 10px;
        }

        body::-webkit-scrollbar-thumb {
            background: linear-gradient(-135deg, rgb(63, 63, 63), rgb(182, 165, 132));  /* Green thumb with a gradient effect */
            border-radius: 10px;
            border: 3px solid rgba(0, 0, 0, 0.2);  /* Slight border around the thumb */
        }

        body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(-135deg, rgb(0, 0, 0), rgb(182, 165, 132));  /* Darker green when hovered */
        }

        body::-webkit-scrollbar-corner {
            background: transparent; /* No corner background */
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" name="username" required placeholder="Username" />
            <input type="text" name="firstname" required placeholder="First Name" />
            <input type="text" name="lastname" required placeholder="Last Name" />
            <input type="email" name="email" required placeholder="Email" />
            <input type="text" name="phone" required placeholder="Phone Number" />
            <input type="password" name="password" required placeholder="Password" />
            <input type="submit" value="Sign Up" />
        </form>

        <!-- Button to toggle users list visibility -->
        <form method="post">
            <button type="submit" name="show_users">
                <?php echo $showUsers ? 'Hide All Users' : 'Show All Users'; ?>
            </button>
        </form>

        <!-- Display sorted users if the button is clicked -->
        <?php if ($showUsers): ?>
            <h3>Signed-Up Users (Sorted by First Name)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
