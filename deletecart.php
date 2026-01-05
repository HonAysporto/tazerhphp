<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data['cart_id'];

$sql = "DELETE FROM user_cart WHERE cart_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {
  echo json_encode(["status" => "removed"]);
}


?>