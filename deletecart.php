<?php
require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');
$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'];

$sql = "DELETE FROM user_cart WHERE cart_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {
  echo json_encode(["status" => "removed"]);
}


?>