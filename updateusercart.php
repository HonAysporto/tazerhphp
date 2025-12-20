<?php
require 'connect.php';


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'];
$quantity = $data['quantity'];

$sql = "UPDATE user_cart SET quantity = ? WHERE cart_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("ii", $quantity, $cart_id);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
}
