<?php
require 'connect.php';
require_once 'cors.php';

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

// 🔥 Get orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($order = $result->fetch_assoc()) {

    $order_id = $order['order_id'];

    // 🔥 Get items for each order
    $items_sql = "
      SELECT oi.*, p.product_name, p.product_image
      FROM order_items oi
      JOIN products_table p ON oi.product_id = p.product_id
      WHERE oi.order_id = ?
    ";
    $stmt2 = $connection->prepare($items_sql);
    $stmt2->bind_param("i", $order_id);
    $stmt2->execute();
    $items_result = $stmt2->get_result();

    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }

    $order['items'] = $items;
    $orders[] = $order;
}

echo json_encode([
    "status" => true,
    "orders" => $orders
]);