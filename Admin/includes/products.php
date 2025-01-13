<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #1a1a1a; /* Dark background */
            color: white; /* Light text color */
        }
        .button-container {
            text-align: center;
            background: rgba(0, 0, 0, 0.8); /* Semi-transparent black background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4); /* Shadow for depth */
        }
        .button-container button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color:rgb(255, 170, 0); /* Blue button color */
            color: white;
            transition: background-color 0.3s ease;
        }
        .button-container button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <div class="button-container">
        <button onclick="window.location.href='createProduct.php'">Create Product</button>
        <button onclick="window.location.href='editProduct.php'">Edit Product</button>
    </div>
</body>
</html>