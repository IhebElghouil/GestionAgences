<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول']);
    exit;
}

// Vérifier si l'ID du détachement est fourni
if (!isset($_GET['detachement_id']) || empty($_GET['detachement_id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف الملحق مطلوب']);
    exit;
}

$detachement_id = intval($_GET['detachement_id']);

// Récupérer les documents du détachement
$query = "SELECT id, detachement_id, document_name, file_path, file_size, document_type, description, uploaded_by, upload_date 
          FROM detachement_documents 
          WHERE detachement_id = $detachement_id 
          ORDER BY upload_date DESC";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . mysqli_error($connection)]);
    exit;
}

$documents = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Formater la taille du fichier
    $file_size = $row['file_size'];
    $formatted_size = '';
    
    if ($file_size < 1024) {
        $formatted_size = $file_size . ' B';
    } elseif ($file_size < 1048576) {
        $formatted_size = round($file_size / 1024, 2) . ' KB';
    } else {
        $formatted_size = round($file_size / 1048576, 2) . ' MB';
    }
    
    // Formater la date
    $upload_date = date('Y-m-d H:i', strtotime($row['upload_date']));
    
    $documents[] = [
        'id' => $row['id'],
        'detachement_id' => $row['detachement_id'],
        'document_name' => $row['document_name'],
        'file_path' => $row['file_path'],
        'file_size' => $row['file_size'],
        'formatted_size' => $formatted_size,
        'document_type' => $row['document_type'],
        'description' => $row['description'],
        'uploaded_by' => $row['uploaded_by'],
        'upload_date' => $upload_date
    ];
}

echo json_encode([
    'success' => true,
    'documents' => $documents,
    'count' => count($documents)
]);
?>