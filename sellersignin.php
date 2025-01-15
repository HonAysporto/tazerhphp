<?php
require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Content-Type');

$data = json_decode(file_get_contents('php://input'));

$email = $data->email;
$password = $data->password;

$query = "SELECT * FROM sellers_table WHERE email = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $email);
$execute = $prepare->execute();

if ($execute) {
    $getnumrow = $prepare->get_result();
    if ($getnumrow -> num_rows > 0) {
        $seller = $getnumrow->fetch_assoc();
        $sellerId = $seller['sellers_id'];
        $pass = $seller['password'];
        $passwordverify = password_verify($password, $pass);

        if ($passwordverify) {
            $response = [
                'status' => true,
                'msg' => 'Signin Successful',
                'seller' => $seller,
                'sellerid' =>$sellerId
            ];
            echo json_encode($response);
        } else {
            $response = [
                'status' => false,
                'msg' => 'Wrong password',
            ];
            echo json_encode($response);
        }
    } else {
        $response = [
            'status' => false,
            'msg' => 'User not found'
        ];
        echo json_encode($response);
    }
} else {
    echo 'not executed';
}

?>