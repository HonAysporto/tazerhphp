<?php
require 'connect.php';
require_once 'cors.php';

// Get seller ID from request (e.g., from Angular) or session
$data = json_decode(file_get_contents('php://input'));
$seller_id = $data->seller_id; 


if (!$seller_id) {
    echo json_encode([
        "status" => false,
        "message" => "Seller ID is required"
    ]);
    exit;
}

// Get all orders that include this seller's products (latest first)
$sql = "SELECT o.*
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products_table p ON oi.product_id = p.product_id
    WHERE p.sellers_id = ?
    GROUP BY o.order_id
    ORDER BY o.created_at DESC
";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($order = $result->fetch_assoc()) {
    $order_id = $order['order_id'];

    // 🔥 Get items in this order that belong to this seller
    $items_sql = "SELECT oi.*, p.product_name, p.product_image
        FROM order_items oi
        JOIN products_table p ON oi.product_id = p.product_id
        WHERE oi.order_id = ? AND p.sellers_id = ?
    ";

    $items_stmt = $connection->prepare($items_sql);
    $items_stmt->bind_param("ii", $order_id, $seller_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

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
?>