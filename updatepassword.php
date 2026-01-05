<?php

require_once 'cors.php';
require_once 'connect.php';

$data = json_decode(file_get_contents('php://input'));
// echo json_encode($data);

$oldpassword = $data->oldPassword;
$newpassword = $data->newPassword;
$sellerid = $data->sellerid;

$query = "SELECT * FROM sellers_table WHERE sellers_id = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('i', $sellerid);
$execute = $prepare->execute();

if ($execute) {
    $getnumrow= $prepare->get_result();
    if ($getnumrow -> num_rows > 0) {
        $seller = $getnumrow->fetch_assoc();
        $pass = $seller['password'];
        $passwordverify = password_verify($oldpassword, $pass);

        if ($passwordverify) {
            $hash = password_hash($newpassword, PASSWORD_DEFAULT);
            if ($hash) {
                $querychange = "UPDATE sellers_table SET `password`=? WHERE `sellers_id` = ?";
                $prep = $connection->prepare($querychange);
                $prep->bind_param('si', $hash, $sellerid);
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