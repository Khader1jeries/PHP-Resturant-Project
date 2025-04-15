<?php
require 'dompdf/dompdf/autoload.inc.php'; // Correct path to Dompdf autoload

use Dompdf\Dompdf;

// Create a new Dompdf instance
$dompdf = new Dompdf();

// Get order details from database
include_once "../config/phpdb.php";

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    die("Invalid Order ID.");
}

// Fetch order details
$order_query = "SELECT p.id, p.purchase_date, p.total_amount, c.firstname, c.lastname 
                FROM purchases p 
                JOIN clientusers c ON p.user_id = c.id
                WHERE p.id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($order_result);

// Fetch purchase details (products)
$details_query = "SELECT pr.name, pd.quantity, pd.price
                  FROM purchase_details pd
                  JOIN products pr ON pd.product_id = pr.id
                  WHERE pd.purchase_id = '$order_id'";
$details_result = mysqli_query($conn, $details_query);

mysqli_close($conn);

// Build the HTML content for PDF
$html = "<h1 style='text-align: center;'>Invoice #{$order['id']}</h1>
<p>Date: {$order['purchase_date']}</p>
<p>Customer: {$order['firstname']} {$order['lastname']}</p>
<table border='1' width='100%' cellspacing='0' cellpadding='5'>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>";

while ($detail = mysqli_fetch_assoc($details_result)) {
    $total = $detail['quantity'] * $detail['price'];
    $html .= "<tr>
                <td>{$detail['name']}</td>
                <td>{$detail['quantity']}</td>
                <td>$" . number_format($detail['price'], 2) . "</td>
                <td>$" . number_format($total, 2) . "</td>
              </tr>";
}

$html .= "</tbody></table>
<p style='text-align:right; font-size:18px;'><strong>Total Amount: $" . number_format($order['total_amount'], 2) . "</strong></p>";

// Load HTML into Dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF as a download
$dompdf->stream("Invoice_{$order['id']}.pdf", ["Attachment" => true]);
?>