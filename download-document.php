<?php
session_start();
require('connection.php');

// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    if ($id <= 0) {
        die("ID de document invalide");
    }
    
    $query = "SELECT * FROM career_documents WHERE id = $id";
    $result = mysqli_query($connection, $query);
    
    if (!$result) {
        die("Erreur SQL: " . mysqli_error($connection));
    }
    
    if (mysqli_num_rows($result) > 0) {
        $doc = mysqli_fetch_assoc($result);
        
        // Construire le chemin absolu du fichier
        $file_path = $doc['file_path'];
        
        // Vérifier si le fichier existe
        if (!file_exists($file_path)) {
            // Essayer de chercher dans le dossier uploads/career_documents/
            $alternative_path = 'uploads/career_documents/' . $doc['file_name'];
            if (file_exists($alternative_path)) {
                $file_path = $alternative_path;
            } else {
                die("Fichier non trouvé sur le serveur. Chemin: " . $file_path);
            }
        }
        
        $file_size = filesize($file_path);
        
        // Vider le buffer de sortie
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Forcer le téléchargement
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($doc['file_name']) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
        
        readfile($file_path);
        exit;
    } else {
        die("Document non trouvé dans la base de données");
    }
} else {
    die("ID de document manquant");
}
?>