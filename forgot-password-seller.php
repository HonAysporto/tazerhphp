<?php
require 'connect.php';
require 'sendMail.php';
require_once 'cors.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
// $email = "ayomideoluwafemi2019@gmail.com";

$token = bin2hex(random_bytes(16));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

// 🔍 Check email
$sql = "SELECT * FROM sellers_table WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

  // Save token
  $update = "UPDATE sellers_table 
             SET reset_token=?, token_expiry=? 
             WHERE email=?";
  $stmt2 = $connection->prepare($update);
  $stmt2->bind_param("sss", $token, $expiry, $email);
  $stmt2->execute();

  $link = "http://localhost:4200/seller-reset-password?token=$token";

  // 🔥 EMAIL TEMPLATE
  $body = "
    <h3>Password Reset</h3>
    <p>Click the link below to reset your password:</p>
    <a href='$link'>Reset Password</a>
    <p>This link expires in 1 hour</p>
  ";

$result = sendMail($email, "Reset Password", $body);

if ($result === true) {
  echo json_encode(["status" => true]);
} else {
  echo json_encode([
    "status" => false,
    "message" => $result // 🔥 SHOW REAL ERROR
  ]);
}

} else {
  echo json_encode(["status" => false, "message" => "Email not found"]);
}