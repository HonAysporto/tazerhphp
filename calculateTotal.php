<?php
require_once 'cors.php';
require 'connect.php';
$data = json_decode(file_get_contents('php://input'));

$buyer_id = $data->buyer_id;


$query = "SELECT SUM(c.quantity * p.product_price) AS total
FROM user_cart c
JOIN products_table p ON c.product_id = p.product_id
WHERE c.user_id = ?";

$stmt = $connection->prepare($query);

if (!$stmt) {
    echo json_encode([
        'status' => false,
        'message' => 'Prepare failed: ' . $connection->error
    ]);
    exit;
}

$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'status' => true,
    'total' => $result['total'] ?? 0
]);