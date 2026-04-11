<?php

$host = getenv("DB_HOST");
$user = getenv("DB_USER");
$pass = getenv("DB_PASSWORD");
$db   = getenv("DB_NAME");
$port = getenv("DB_PORT");

$connection = new mysqli($host, $user, $pass, $db, $port);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>