<?php

require_once 'cors.php';
require_once 'connect.php';

$query = "SELECT * FROM user_cart WHERE user_id = ?";
$prepare = $connection->prepare($query);
$prepare->bind_param('s', $userId);
$execute = $prepare->execute();

?>