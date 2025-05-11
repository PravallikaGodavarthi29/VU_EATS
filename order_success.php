<?php
include 'database.php';

// Get order ID from URL
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : '';

if (!$orderId) {
    echo "<h2>Invalid Order!</h2>";
    exit;
}

// Fetch order details
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Fetch order items
$sqlItems = "SELECT * FROM order_items WHERE order_id = ?";
$stmtItems = $conn->prepare($sqlItems);
$stmtItems->bind_param("s", $orderId);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
        .container { max-width: 600px; margin: auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #f4f4f4; }
        .back-btn { padding: 10px 20px; background: green; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Order Confirmed!</h1>
<div class="container">
    <h2>Order ID: <?php echo $order['order_id']; ?></h2>
    <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
    <p><strong>Phone:</strong> <?php echo $order['phone']; ?></p>
    <p><strong>Order Type:</strong> <?php echo $order['order_type']; ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo $order['total_amount']; ?></p>

    <h3>Ordered Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $resultItems->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $item['product']; ?></td>
                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="cafe.html" class="back-btn">Back to Menu</a>
</div>

</body>
</html>
