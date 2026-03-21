<?php
require 'connect.php';
require_once 'cors.php';

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


$sql = "INSERT INTO orders (user_id, total_amount, address, reference)
        VALUES (?, ?, ?, ?)";
$stmt2 = $connection->prepare($sql);
$stmt2->bind_param("idss", $buyer_id, $total, $address, $reference);
$stmt2->execute();

$order_id = $stmt2->insert_id;

// Insert order items
$sqlCart = " SELECT c.product_id, c.quantity, p.product_price
  FROM user_cart c
  JOIN products_table p ON c.product_id = p.product_id
  WHERE c.user_id = ?
";
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
$connection->query("DELETE FROM cart WHERE user_id = $buyer_id");

echo json_encode(["status" => true]);