<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

$connection = new mysqli($host, $user, $pass, $db, $port);

if ($connection->connect_error) {
    error_log('DB connection failed: ' . $connection->connect_error);
    die('Database connection failed');
}


echo 'Connected successfully';
?>
