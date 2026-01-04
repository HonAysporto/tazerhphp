<?php
    require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Content-Type');

$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$firstname = $data->firstname;
$lastname = $data->lastname;
$phonenumber = $data->phonenumber;
$buyerid = $data->buyerid;

$query = 'UPDATE `customers_table` SET `firstname`= ?,`lastname`=?,`phonenumber`=? WHERE `customer_id` = ?';
$prepare = $connection->prepare($query);
$prepare->bind_param('sssi', $firstname, $lastname,   $phonenumber, $buyerid);
$execute = $prepare->execute();

if ($execute) {
    $response = [
        'status' => true,
        'msg' => 'Profile updated successfully'
    ];
    echo json_encode($response);
} else {
    $response = [
        'status' => false,
        'msg' => 'Error updating profile'
    ];
    echo json_encode($response);
}



?>