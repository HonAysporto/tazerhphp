<?php

require 'connect.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Content-Type');



$query = "SELECT * FROM user_cart WHERE user_id = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $userId);
$execute = $prepare->execute();

?>