<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'));

$buyer = $data->buyer_id;
// $buyer = 1;

$query = "SELECT 
    p.product_name,
    p.product_price,
    p.product_image,
    c.cart_id,
    c.quantity
FROM user_cart c
JOIN products_table p
    ON p.product_id = c.product_id
WHERE c.user_id = ?";

$prepare = $connection->prepare($query);
$prepare->bind_param('s', $buyer);
$execute = $prepare->execute();

if ($execute) {
    $result = $prepare->get_result();
    if ($result->num_rows > 0) {
        $cart = [];
        while ($row = $result->fetch_assoc()) {
            $cart[] = $row;
        }
         $response = [
                    'status' => true,
                    'cart' => $cart,
                
                ];
                echo json_encode($response);
    } else {
        echo json_encode(['message' => 'no cart']);
    }
} else {
    echo json_encode(['message' => 'query failed']);
}
?>
