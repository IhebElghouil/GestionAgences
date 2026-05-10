<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Test début<br>";

$root_path = dirname(__DIR__);
echo "Root path: " . $root_path . "<br>";

$conn_file = $root_path . '/connection.php';
echo "Connection file: " . $conn_file . "<br>";

if (file_exists($conn_file)) {
    echo "connection.php existe<br>";
    require_once($conn_file);
    
    if (isset($connection)) {
        echo "Connection OK<br>";
    } else {
        echo "Connection variable not set<br>";
    }
} else {
    echo "connection.php n'existe PAS !<br>";
}
?>