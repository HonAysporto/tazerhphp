<?php
require 'connect.php';
require_once 'cors.php';

$data = json_decode(file_get_contents("php://input"), true);

$token = $data['token'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$sql = "SELECT * FROM sellers_table 
        WHERE reset_token=? AND token_expiry > NOW()";

$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

  $update = "UPDATE sellers_table 
    SET password=?, reset_token=NULL, token_expiry=NULL
    WHERE reset_token=?";

  $stmt2 = $connection->prepare($update);
  $stmt2->bind_param("ss", $password, $token);
  $stmt2->execute();

  echo json_encode(["status" => true]);

} else {
  echo json_encode([
    "status" => false,
    "message" => "Invalid or expired token"
  ]);
}