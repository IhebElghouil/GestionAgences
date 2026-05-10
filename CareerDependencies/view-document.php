<?php
session_start();
require_once('../connection.php');

// Activer l'affichage des erreurs pour le débogage (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authentication
if (!isset($_SESSION['congidGA'])) {
    die("Accès non autorisé");
}

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
        
        // Define the correct base path
        $base_upload_dir = dirname(__DIR__) . '/uploads/career_documents/';
        
        // Possible file paths to check
        $possible_paths = [
            $base_upload_dir . $doc['file_path'],  // file_path from DB
            $base_upload_dir . $doc['file_name'],  // original filename
            '../uploads/career_documents/' . $doc['file_path'],
            '../uploads/career_documents/' . $doc['file_name'],
            'uploads/career_documents/' . $doc['file_path'],
            'uploads/career_documents/' . $doc['file_name']
        ];
        
        $file_path = null;
        
        // Try each possible path
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $file_path = $path;
                break;
            }
        }
        
        // If still not found, try to find any file with matching ID pattern
        if (!$file_path) {
            // Search for files that might contain the ID in the name
            $search_pattern = $base_upload_dir . '*';
            $files = glob($search_pattern);
            foreach ($files as $file) {
                if (strpos($file, (string)$doc['id']) !== false) {
                    $file_path = $file;
                    break;
                }
            }
        }
        
        // If file still not found
        if (!$file_path || !file_exists($file_path)) {
            die("Fichier non trouvé sur le serveur.<br>
                 ID Document: " . $doc['id'] . "<br>
                 Nom fichier DB: " . htmlspecialchars($doc['file_name']) . "<br>
                 Chemin DB: " . htmlspecialchars($doc['file_path']) . "<br>
                 Chemins recherchés:<br>" . implode('<br>', $possible_paths));
        }
        
        $file_extension = strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION));
        $file_size = filesize($file_path);
        
        // Vider le buffer de sortie
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Pour les images
        if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
            $mime_types = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp'
            ];
            $content_type = $mime_types[$file_extension] ?? 'application/octet-stream';
            
            header('Content-Type: ' . $content_type);
            header('Content-Length: ' . $file_size);
            header('Content-Disposition: inline; filename="' . basename($doc['file_name']) . '"');
            header('Cache-Control: public, max-age=0');
            header('Pragma: public');
            
            readfile($file_path);
            exit;
        }
        // Pour les PDF
        elseif ($file_extension == 'pdf') {
            header('Content-Type: application/pdf');
            header('Content-Length: ' . $file_size);
            header('Content-Disposition: inline; filename="' . basename($doc['file_name']) . '"');
            header('Cache-Control: public, max-age=0');
            header('Pragma: public');
            header('Accept-Ranges: bytes');
            
            readfile($file_path);
            exit;
        }
        // Pour les fichiers texte
        elseif (in_array($file_extension, ['txt', 'csv', 'log'])) {
            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Length: ' . $file_size);
            header('Content-Disposition: inline; filename="' . basename($doc['file_name']) . '"');
            
            readfile($file_path);
            exit;
        }
        // Pour les documents Word/Excel - Force download for better compatibility
        elseif (in_array($file_extension, ['doc', 'docx', 'xls', 'xlsx'])) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($doc['file_name']) . '"');
            header('Content-Length: ' . $file_size);
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            
            readfile($file_path);
            exit;
        }
        // Pour les autres types, forcer le téléchargement
        else {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($doc['file_name']) . '"');
            header('Content-Length: ' . $file_size);
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            
            readfile($file_path);
            exit;
        }
    } else {
        die("Document non trouvé dans la base de données");
    }
} else {
    die("ID de document manquant");
}
?>