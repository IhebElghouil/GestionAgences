<?php
// config.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 0); // إيقاف عرض الأخطاء في الإنتاج

// إعدادات قاعدة البيانات
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pointage');
define('DB_PORT', 3307);

// أسماء الجداول
define('TABLE_EMPLOYEES', 'stuf');
define('TABLE_LEAVES', 'conge');
define('TABLE_DEPARTURES', 'depart');
define('TABLE_SERVICES', 'service');

function getConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("set names utf8mb4");
        return $conn;
    } catch(PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

function executeQuery($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
?>