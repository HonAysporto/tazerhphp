<?php
require 'connect.php';
require_once 'cors.php';
require 'sendmail.php';

$data = json_decode(file_get_contents('php://input'), true);

$buyer_id = $data['buyer_id'];
$address = $data['address'];
$reference = $data['reference'];

// 🔥 Calculate total again (secure)
$sqlTotal = "SELECT SUM(c.quantity * p.product_price) AS total
FROM user_cart c
JOIN products_table p ON c.product_id = p.product_id
WHERE c.user_id = ?";

$stmt = $connection->prepare($sqlTotal);
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$total = $result['total'] ?? 0;

// Insert order
$sql = "INSERT INTO orders (user_id, total_amount, address, reference)
        VALUES (?, ?, ?, ?)";
$stmt2 = $connection->prepare($sql);
$stmt2->bind_param("idss", $buyer_id, $total, $address, $reference);
$stmt2->execute();
$order_id = $stmt2->insert_id;

// Insert order items
$sqlCart = "SELECT c.product_id, c.quantity, p.product_price
FROM user_cart c
JOIN products_table p ON c.product_id = p.product_id
WHERE c.user_id = ?";
$stmt3 = $connection->prepare($sqlCart);
$stmt3->bind_param("i", $buyer_id);
$stmt3->execute();
$resultCart = $stmt3->get_result();

while ($row = $resultCart->fetch_assoc()) {
    $stmt4 = $connection->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    $stmt4->bind_param("iiid", $order_id, $row['product_id'], $row['quantity'], $row['product_price']);
    $stmt4->execute();
}

// 🔥 Clear cart
$connection->query("DELETE FROM user_cart c WHERE user_id = $buyer_id");

// Fetch buyer email
$stmtUser = $connection->prepare("SELECT email, firstname FROM customers_table WHERE customer_id = ?");
$stmtUser->bind_param("i", $buyer_id);
$stmtUser->execute();
$userData = $stmtUser->get_result()->fetch_assoc();

$buyerEmail = $userData['email'];
$buyerName = $userData['firstname'];

// Prepare email content
$subject = "Order Confirmation - Reference: $reference";
$body = "
Hi $buyerName,<br><br>
Thank you for your order! Here are the details:<br>
Order ID: $order_id<br>
Total Amount: $$total<br>
Shipping Address: $address<br><br>
Your goods are being delivered.<br><br>
Thank you for shopping with us!
";

// Send email
$emailResult = sendMail($buyerEmail, $subject, $body);

// ✅ Respond once to frontend
if ($emailResult === true) {
    echo json_encode(["status" => true, "order_id" => $order_id]);
} else {
    echo json_encode(["status" => true, "order_id" => $order_id, "email_error" => $emailResult]);
}