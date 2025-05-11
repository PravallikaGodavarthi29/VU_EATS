<?php
include 'database.php'; // Ensure this file contains correct DB credentials

header("Content-Type: application/json"); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);

// Check if JSON data is received
if (!$data) {
    echo json_encode(["success" => false, "message" => "No JSON received", "rawData" => file_get_contents("php://input")]);
    exit;
}

// Sanitize and Validate Inputs
$orderId = htmlspecialchars(strip_tags($data['orderId'] ?? ''));
$name = htmlspecialchars(strip_tags($data['name'] ?? ''));
$phone = htmlspecialchars(strip_tags($data['phone'] ?? ''));
$orderType = htmlspecialchars(strip_tags($data['orderType'] ?? ''));
$totalAmount = isset($data['totalAmount']) ? floatval($data['totalAmount']) : 0;
$paymentMethod = htmlspecialchars(strip_tags($data['paymentMethod'] ?? ''));
$paymentId = htmlspecialchars(strip_tags($data['paymentId'] ?? ''));
$orderDate = date("Y-m-d H:i:s"); // Current timestamp

// Validate Required Fields
if (!$orderId || !$name || !$phone || !$orderType || $totalAmount <= 0 || !$paymentMethod || !$paymentId) {
    echo json_encode(["success" => false, "message" => "Missing or invalid fields", "receivedData" => $data]);
    exit;
}

// Prepare SQL Statement
$sql = "INSERT INTO orders (order_id, name, phone, order_type, total_amount, payment_method, payment_id, order_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssssssss", $orderId, $name, $phone, $orderType, $totalAmount, $paymentMethod, $paymentId, $orderDate);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "orderId" => $orderId]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "SQL prepare failed: " . $conn->error]);
}

$conn->close();
?>
