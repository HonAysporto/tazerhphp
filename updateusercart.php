<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'];
$quantity = $data['quantity'];

$sql = "UPDATE user_cart SET quantity = ? WHERE cart_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ii", $quantity, $cart_id);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
}
