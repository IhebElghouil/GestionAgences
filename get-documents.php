<?php
session_start();
require('connection.php');

// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if (isset($_GET['career_id'])) {
    $career_id = intval($_GET['career_id']);
    
    if ($career_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID de carrière invalide']);
        exit;
    }
    
    // Vérifier d'abord si la table existe
    $checkTable = mysqli_query($connection, "SHOW TABLES LIKE 'career_documents'");
    if (mysqli_num_rows($checkTable) == 0) {
        echo json_encode(['success' => false, 'message' => 'La table career_documents n\'existe pas']);
        exit;
    }
    
    $query = "SELECT id, file_name, file_path, file_type, file_size, uploaded_at 
              FROM career_documents 
              WHERE career_id = $career_id 
              ORDER BY uploaded_at DESC";
    
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL: ' . mysqli_error($connection)]);
        exit;
    }
    
    $documents = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Ne garder que le nom du fichier pour l'affichage
        $display_name = $row['file_name'];
        // Si le nom contient un timestamp, on peut l'enlever pour l'affichage
        if (preg_match('/^\d+_[a-f0-9]+_(.+)$/', $row['file_name'], $matches)) {
            $display_name = $matches[1];
        }
        
        $documents[] = [
            'id' => $row['id'],
            'file_name' => $display_name,
            'original_name' => $row['file_name'],
            'file_path' => $row['file_path'],
            'file_type' => $row['file_type'],
            'file_size' => $row['file_size'],
            'uploaded_at' => date('Y-m-d H:i', strtotime($row['uploaded_at']))
        ];
    }
    
    echo json_encode(['success' => true, 'documents' => $documents]);
    
} else {
    echo json_encode(['success' => false, 'message' => 'ID de carrière manquant']);
}
?>