<?php
// models/UserModel.php
require_once __DIR__ . '/../config/Database.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getUserDepartments($userId) {
        $query = "SELECT dep FROM stuf WHERE mecano = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row['dep'];
        }
        
        return $departments;
    }
    
    public function isAdmin($userId) {
        $query = "SELECT role FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        return $user && $user['role'] === 'admin';
    }
    
    public function getUserInfo($userId) {
        $query = "SELECT mecano, nom, prenom, dep FROM stuf WHERE mecano = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}