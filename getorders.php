<?php

header("Access-Control-Allow-Origin: https://tazerh-store.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// 🔥 Handle preflight request immediately
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'connect.php';

// Only try to read body if it exists
$data = json_decode(file_get_contents('php://input'), true);

// Prevent crash if no data
$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "status" => false,
        "message" => "user_id is required"
    ]);
    exit();
}

// 🔥 Get orders
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($order = $result->fetch_assoc()) {

    $order_id = $order['order_id'];

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
