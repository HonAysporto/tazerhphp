<?php
require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'));

$productname = $data->productname ;

$query = "DELETE FROM `products_table` WHERE `product_name` = ?";
$stmt = $connection->prepare($query);

if ($stmt) {
    $stmt->bind_param('s', $productname);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = [
                'status' => true,
                'msg' => 'Product deleted successfully.'
            ];
        } else {
            $response = [
                'status' => false,
                'msg' => 'No product found with the given name.'
            ];
        }
    } else {
        $response = [
            'status' => false,
            'msg' => 'Error executing query: ' . $stmt->error
        ];
    }

    $stmt->close();
} else {
    $response = [
        'status' => false,
        'msg' => 'Error preparing query: ' . $connection->error
    ];
}

echo json_encode($response);

?>
