<?php
require 'connect.php';

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['category'])) {
    echo json_encode([
        "status" => false,
        "msg" => "Category not provided"
    ]);
    exit;
}

$category = $data['category'];
$productId = $data['product_id'];

$query = "
  SELECT *
  FROM products_table
  WHERE product_category = ?
  AND product_id != ? ORDER BY RAND()
LIMIT 4
";

$prepare = $connection->prepare($query);
$prepare->bind_param("si", $category, $productId);
$prepare->execute();

$result = $prepare->get_result();

if ($result->num_rows > 0) {
    $productArray = [];

    while ($product = $result->fetch_assoc()) {
        $productArray[] = [
            'productid' => (int)$product['product_id'],
            'productname' => $product['product_name'],
            'description' => $product['product_details'],
            'quantity' => $product['product_quantity'],
            'price' => $product['product_price'],
            'image' => $product['product_image'],
            'category' => $product['product_category'],
        ];
    }

    echo json_encode([
        'status' => true,
        'data' => $productArray
    ]);
} else {
    echo json_encode([
        'status' => false,
        'msg' => 'No related Product'
    ]);
}
?>
