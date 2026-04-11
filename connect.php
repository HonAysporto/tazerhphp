<!-- <?php
$localhost = 'localhost';
$username = 'root';
$password = '';
$db = 'defaultdb';

$connection = new mysqli($localhost, $username, $password, $db);

if ($connection->connect_error) {
    echo 'not connected'.$connection->connect_error;
} else {
   
}

?> -->
<?php

$host = getenv("DB_HOST");
$db   = getenv("DB_NAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASSWORD");
$port = getenv("DB_PORT");

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_CA => "/app/ca.pem"
        ]
    );

} catch (PDOException $e) {
    die("DB Connection Failed ❌: " . $e->getMessage());
}