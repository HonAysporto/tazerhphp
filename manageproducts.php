<?php

require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents('php://input'));

$sellerid = $data->sellers_id;

$query = 'SELECT `product_name`, `product_details`, `product_quantity`, `product_category`, `product_price`, `product_image` FROM `products_table` WHERE `sellers_id` = ?';
$stmt = $connection->prepare($query);

if ($stmt) {
    $stmt->bind_param('i', $sellerid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $productArray = [];
        while ($product = $result->fetch_assoc()) {
            $productArray[] = [
                'productname' => $product['product_name'],
                'description' => $product['product_details'],
                'quantity' => $product['product_quantity'],
                'price' => $product['product_price'],
                'image' => $product['product_image'],
                'category' => $product['product_category'],
            ];
        }
        $response = [
            'status' => true,
            'msg' => $productArray
        ];
    } else {
        $response = [
            'status' => false,
            'msg' => 'No products found for the given seller ID'
        ];
    }
} else {
    $response = [
        'status' => false,
        'msg' => 'Query preparation failed: ' . $connection->error
    ];
}

echo json_encode($response);

?>
