<?php
require_once 'cors.php';
require_once 'connect.php';

$data = json_decode(file_get_contents("php://input"));
$userId = $data->userId;
$cart = $data->cart;

// Validate input
if (!isset($userId) || !is_array($cart)) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Clear existing cart for the user
$sqlClearCart = "DELETE FROM user_cart WHERE user_id = ?";
$stmt = $connection->prepare($sqlClearCart);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// Insert merged cart items
$sqlInsertCart = "INSERT INTO user_cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
$stmt = $connection->prepare($sqlInsertCart);

foreach ($cart as $item) {
    $productId = $item->productId;
    $quantity = $item->orderedQuantity;

    if (isset($productId) && isset($quantity)) {
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        $stmt->execute();
    }
}

$stmt->close();
$connection->close();

// Return success response
echo json_encode(["status" => "success", "msg" => "Cart saved successfully"]);

?>