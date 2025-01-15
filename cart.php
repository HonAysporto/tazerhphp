<?php
require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');


$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$userId = $data->userId;

$query = "SELECT * FROM user_cart WHERE user_id = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $userId);
$execute = $prepare->execute();

if ($execute) {
    $getnumrow = $prepare->get_result();
    if ($getnumrow -> num_rows > 0) {
        while ($cart = $getnumrow->fetch_assoc()) {
            $cartArray[] = [
                'productId' => (int)$cart['product_id'],
                'orderedQuantity' => (int)$cart['quantity']
            ];
        }
        $response = [
            'status' => true,
            'msg' => $cartArray
        ];
        echo json_encode($response);
    } else {
        $response = [
            'status' => false,
            'msg' => 'No cart yet'
        ];
        echo json_encode($response);
    }
} else {
 echo 'Query not ran';
}






?>
