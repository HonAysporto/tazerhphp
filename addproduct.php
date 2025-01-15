<?php
require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Content-Type');

$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);


$productname = $data->productName;
$description = $data->description;
$category = $data->category;
$price = $data->price;
$quantity = $data->quantity;
$imagepath = $data->imagePath;
$sellerid = $data->sellerid;


$query = 'SELECT * FROM products_table Where product_name = ?';
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $productname);
$execute = $prepare->execute();


if ($execute) {
    $getnumrow = $prepare->get_result();
    if ($getnumrow->num_rows > 0) {
        $response = [
            'status' => false,
            'msg' => 'This products exist already'
        ];
        echo json_encode($response);
    } else {
        $queryinsert = " INSERT INTO products_table (`product_name`, `product_details`, `product_price`, `product_quantity`, `product_category`, `product_image`, `sellers_id`) VALUES (?,?,?,?,?,?,?)";
        $prep = $connection->prepare($queryinsert);
        $prep->bind_param('ssdissi', $productname, $description, $price, $quantity, $category,  $imagepath, $sellerid);
        $exe = $prep->execute();

        if ($exe) {
            $response = [
                'status' => true,
                'msg' => ''.$productname.' added successful'
            ];
            echo json_encode($response);
        } else {
            $response = [
                'status' => false,
                'msg' => 'Error while adding product'
            ];
            echo json_encode($response);
        }
    }
}else {
    echo 'not executed';
}

?>