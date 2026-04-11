<?php
require 'connect.php';
require 'sendMail.php';
require 'cors.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];

$token = bin2hex(random_bytes(16));
$expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

// 🔍 Check email
$sql = "SELECT * FROM customers_table WHERE email = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

  // Save token
  $update = "UPDATE customers_table 
             SET reset_token=?, token_expiry=? 
             WHERE email=?";
  $stmt2 = $connection->prepare($update);
  $stmt2->bind_param("sss", $token, $expiry, $email);
  $stmt2->execute();

  $link = "https://tazerh-store.vercel.app/customer-reset-password?token=$token";

  // 🔥 EMAIL TEMPLATE
  $body = "
    <h3>Password Reset</h3>
    <p>Click the link below to reset your password:</p>
    <a href='$link'>Reset Password</a>
    <p>This link expires in 1 hour</p>
  ";

  if (sendMail($email, "Reset Your Password", $body)) {
    echo json_encode(["status" => true]);
  } else {
    echo json_encode(["status" => false, "message" => "Email failed"]);
  }

} else {
  echo json_encode(["status" => false, "message" => "Email not found"]);
}