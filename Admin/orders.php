<?php
session_start();

// Ensure the correct path to phpdb.php
include_once "../config/phpdb.php"; // Include your database configuration file
$printMode = isset($_GET['print']) && $_GET['print'] == 'true';
// Check if the database connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle search by month
$monthFilter = isset($_GET['month']) ? $_GET['month'] : '';
$whereClause = "";
if (!empty($monthFilter)) {
    $whereClause = "WHERE MONTH(p.purchase_date) = " . (int)$monthFilter;
}

$query = "SELECT p.id, p.purchase_date, p.total_amount, p.done, c.firstname, c.lastname 
          FROM purchases p 
          JOIN clientusers c ON p.user_id = c.id
          $whereClause
          ORDER BY p.purchase_date DESC";
$result = mysqli_query($conn, $query);

if ($result):
    // Output all orders
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)):
        $orders[] = $row;
    endwhile;
endif;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - All Orders</title>
    <link rel="stylesheet" href="css_files/orders.css">
    
    <script>
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.print();
            setTimeout(() => {
                window.location.href = 'orders.php'; // Redirect back after printing
            }, 1000);
        }
    };
</script>

</head>
<body>
    <div class="orders-container">
        <h1>All Orders</h1>
        <?php if (!$printMode): ?>
    <?php require 'navbar.php'; ?>
<?php endif; ?>

<?php if (!$printMode): ?>
    
        <!-- Search by Month -->
        <form method="GET" action="">
            <label for="month">Filter by Month (1-12):</label>
            <input type="number" id="month" name="month" min="1" max="12" value="<?php echo htmlspecialchars($monthFilter); ?>">
            <button type="submit">Search</button>
        </form>
        <?php endif; ?>
        <?php if (!$printMode): ?>
        
        <button onclick="window.location.href='orders.php?print=true'" class="cta-button">Print Orders</button>
        <?php endif; ?>
        <?php if (count($orders) > 0): ?>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <?php if (!$printMode): ?>
                        <th>Action</th>
                        <th>Status Button</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['firstname']) . ' ' . htmlspecialchars($order['lastname']); ?></td>
                            <td>
                                <?php echo $order['done'] ? 'Done' : 'Not Done'; ?>
                            </td>
                            <?php if (!$printMode): ?>
                            <td>
                                <a href="view_order.php?order_id=<?php echo htmlspecialchars($order['id']); ?>" class="cta-button">View Details</a>
                            </td>
                           
                             
                            <td>
                                <form action="update_order_status.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                    <input type="hidden" name="done" value="<?php echo $order['done'] ? 0 : 1; ?>">
                                    <button type="submit" class="cta-button">
                                        <?php echo $order['done'] ? 'Mark as Not Done' : 'Mark as Done'; ?>
                                    </button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders found. <a href="index.php">Go back to dashboard</a></p>
        <?php endif; ?>
        <?php if (!$printMode): ?>
        <a href="index.php" class="cta-button">Back to Dashboard</a>
        <?php endif; ?>
    </div>
</body>
</html>
