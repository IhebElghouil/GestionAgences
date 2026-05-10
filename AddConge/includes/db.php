<?php
// db.php

$host = 'localhost:3307';
$dbname = 'pointage';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,         // vrai prepared statements
];

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=$charset",
        $user,
        $pass,
        $options
    );
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => "DB Connection failed: " . $e->getMessage()]));
}
