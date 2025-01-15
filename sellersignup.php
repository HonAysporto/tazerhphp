<?php

    require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents('php://input'));

// echo json_encode($data);



$firstname = $data->firstname;
$lastname = $data->lastname;
$email = $data->email;
$phonenumber = $data->contact;
$password = $data->password;
$shopname = $data->shopname;

$query = "SELECT * FROM sellers_table Where email = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $email);
$execute = $prepare->execute();

if ($execute) {
    $getnumrow = $prepare->get_result();
    if ($getnumrow->num_rows > 0) {
        $response = [
            'status' => false,
            'msg' => 'The email as already been registerd'
        ];
        echo json_encode($response);
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $queryinsert = " INSERT INTO sellers_table (`firstname`, `lastname`, `email`, `phonenumber`, `password`, `shopname`) VALUES (?,?,?,?,?,?)";
        $prep = $connection->prepare($queryinsert);
        $prep->bind_param('ssssss', $firstname, $lastname, $email, $phonenumber, $hash, $shopname);
        $exe = $prep->execute();

        if ($exe) {
            $response = [
                'status' => true,
                'msg' => 'Sign up Successful'
            ];
            echo json_encode($response);
        } else {
            $response = [
                'status' => false,
                'msg' => 'Sign up not Successful'
            ];
            echo json_encode($response);
        }
    }

} else {
    echo 'not executed';
}
?>