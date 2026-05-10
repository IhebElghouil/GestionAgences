<?php
// config/Database.php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $config = require_once __DIR__ . '/config.php';
        $this->connection = new mysqli(
            $config['db_host'],
            $config['db_user'],
            $config['db_password'],
            $config['db_name']
        );
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}