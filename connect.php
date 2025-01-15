<?php
$localhost = 'localhost';
$username = 'root';
$password = '';
$db = 'tazerhstore';

$connection = new mysqli($localhost, $username, $password, $db);

if ($connection->connect_error) {
    echo 'not connected'.$connection->connect_error;
} else {
    
}

?>