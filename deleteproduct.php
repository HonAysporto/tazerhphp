<?php
require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');

// Decode the incoming JSON data
$data = json_decode(file_get_contents('php://input'));

// Extract the product name
$productname = $data->productname ;

// Check if product name is provided


// Prepare the DELETE query
$query = "DELETE FROM `products_table` WHERE `product_name` = ?";
$stmt = $connection->prepare($query);

if ($stmt) {
    // Bind the product name parameter
    $stmt->bind_param('s', $productname);

    // Execute the query
    if ($stmt->execute()) {
        // Check if any rows were affected
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

// Return the JSON response
echo json_encode($response);

?>
