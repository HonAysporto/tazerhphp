<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'));

$productname = $data->productname;
$newDetails = $data->details;

$query = "UPDATE `products_table` 
          SET `product_details` = ?, `product_quantity` = ?, `product_price` = ?
          WHERE `product_name` = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param(
    'sids',
    $newDetails->product_details,
    $newDetails->product_quantity,
    $newDetails->product_price,
    $productname
);

if ($stmt->execute()) {
    echo json_encode(['status' => true, 'msg' => 'Product updated successfully']);
} else {
    echo json_encode(['status' => false, 'msg' => 'Error updating product']);
}
?>
