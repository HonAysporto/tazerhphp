<?php
 require_once 'cors.php';
require_once 'connect.php';
$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$firstname = $data->firstname;
$lastname = $data->lastname;
$phonenumber = $data->phonenumber;
$shopname = $data->shopname;
$sellerid = $data->sellerid;

$query = 'UPDATE `sellers_table` SET `firstname`= ?,`lastname`=?,`shopname`=?,`phonenumber`=? WHERE `sellers_id` = ?';
$prepare = $connection->prepare($query);
$prepare->bind_param('ssssi', $firstname, $lastname,  $shopname, $phonenumber, $sellerid);
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