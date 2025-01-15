<?php

require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');


$query = 'SELECT `products_table`.`product_id`, `products_table`.`product_name`, `products_table`.`product_details`, `products_table`.`product_quantity`, `products_table`.`product_category`, `products_table`.`product_price`, `products_table`.`product_image`, `sellers_table`.`shopname` FROM `products_table` JOIN `sellers_table` ON `products_table`.`sellers_id` = `sellers_table`.`sellers_id`';

$connect = $connection->query($query);

if ($connect) {
    if ($connect->num_rows> 0) {
        while ($product = $connect->fetch_assoc()) {
            $productArray[] = [
                'productid' => (int)$product['product_id'],
                'productname' => $product['product_name'],
                'description' => $product['product_details'],
                'quantity' => (int)$product['product_quantity'],
                'price' => $product['product_price'],
                'image' => $product['product_image'],
                'category' => $product['product_category'], 
                'shopname' => $product['shopname']            
            ]; 
        }
        $response = [
            'status' => true,
            'msg' => $productArray
        ];
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'msg' => 'Error while adding product'
        ];
        echo json_encode($response);
    }
} else 
{
    echo 'Querry not ran';
}

?>