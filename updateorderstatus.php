<?php
require 'connect.php';
require_once 'cors.php';

$data = json_decode(file_get_contents('php://input'), true);

$order_id = $data['order_id'];
$status = $data['status'];

$sql = "UPDATE orders SET status = ? WHERE order_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("si", $status, $order_id);

echo json_encode(["status" => $stmt->execute()]);