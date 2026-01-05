<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$userId = $data->userId;
$productId = $data->productId;
$quantity = $data->orderedQuantity;

// Check if the product already exists in the user's cart
$query = "SELECT * FROM user_cart WHERE user_id = ? AND product_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param('ii', $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity if product exists
    $queryUpdate = "UPDATE user_cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
    $stmtUpdate = $connection->prepare($queryUpdate);
    $stmtUpdate->bind_param('iii', $quantity, $userId, $productId);
    $stmtUpdate->execute();
} else {
    // Insert new product into the cart
    $queryInsert = "INSERT INTO user_cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmtInsert = $connection->prepare($queryInsert);
    $stmtInsert->bind_param('iii', $userId, $productId, $quantity);
    $stmtInsert->execute();
}

echo json_encode(['status' => true, 'message' => 'Product added to cart']);
?>


