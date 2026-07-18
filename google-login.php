<?php
require_once 'cors.php';
require_once 'connect.php';

$data = json_decode(file_get_contents('php://input'));

$name = $data->name;
$email = $data->email;
$photo = $data->photo ?? '';

$nameParts = explode(" ", $name);
$fname = $nameParts[0];
$lname = $nameParts[1] ?? '';
$password = $fname;

$query = "SELECT * FROM customers_table WHERE email = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param("s", $email);
$prepare->execute();
$result = $prepare->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    $response = [
        'status' => true,
        'msg' => 'Login successful',
        'user' => $user,
        'userid' => $user['customer_id']
    ];
    echo json_encode($response);

} else {

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insertQuery = "INSERT INTO customers_table (`firstname`, `lastname`, `email`, `password`) VALUES (?, ?, ?, ?)";
    $insertPrepare = $connection->prepare($insertQuery);
    $insertPrepare->bind_param("ssss", $fname, $lname, $email, $hash);

    if ($insertPrepare->execute()) {

        $newUserId = $insertPrepare->insert_id;

        $getNewUser = $connection->prepare("SELECT * FROM customers_table WHERE customer_id = ?");
        $getNewUser->bind_param("i", $newUserId);
        $getNewUser->execute();
        $newUserResult = $getNewUser->get_result();
        $user = $newUserResult->fetch_assoc();

        $response = [
            'status' => true,
            'msg' => 'Account created & login successful',
            'user' => $user,
            'userid' => $newUserId
        ];
        echo json_encode($response);

    } else {
        $response = [
            'status' => false,
            'msg' => 'Error creating user'
        ];
        echo json_encode($response);
    }
}
?>