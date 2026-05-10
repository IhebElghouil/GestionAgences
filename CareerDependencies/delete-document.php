<?php
session_start();
require('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Récupérer le chemin du fichier avant de supprimer
    $query = "SELECT file_path FROM career_documents WHERE id = $id";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $file_path = $row['file_path'];
        
        // Supprimer de la base de données
        $delete_query = "DELETE FROM career_documents WHERE id = $id";
        if (mysqli_query($connection, $delete_query)) {
            // Supprimer le fichier physique
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo json_encode(['success' => true, 'message' => 'Document supprimé']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur de suppression en base']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Document non trouvé']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
}
?>