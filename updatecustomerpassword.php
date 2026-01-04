<?php

require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers:Content-Type');


$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$oldpassword = $data->oldPassword;
$newpassword = $data->newPassword;
$buyerid = $data->buyerid;

$query = "SELECT * FROM customers_table WHERE customer_id = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('i', $buyerid);
$execute = $prepare->execute();

if ($execute) {
    $getnumrow= $prepare->get_result();
    if ($getnumrow -> num_rows > 0) {
        $buyer = $getnumrow->fetch_assoc();
        $pass = $buyer['password'];
        $passwordverify = password_verify($oldpassword, $pass);

        if ($passwordverify) {
            $hash = password_hash($newpassword, PASSWORD_DEFAULT);
            if ($hash) {
                $querychange = "UPDATE customers_table SET `password`=? WHERE `customer_id` = ?";
                $prep = $connection->prepare($querychange);
                $prep->bind_param('si', $hash, $buyerid);
                $exe = $prep->execute();
    
                if ($exe) {
                    $response = [
                        'status' => true,
                        'msg' => 'Password Change Successfuly'
                    ];
                    echo json_encode($response);
                } else {
                    echo 'query not ran';
                }
            } else {
                echo 'not hashed';
            }
    


        } else {
            $response = [
                'status' => false,
                'msg' => 'Wrong old password',
            ];
            echo json_encode($response);
        }
    }
} else {
    echo 'query didnt run';
}


?>