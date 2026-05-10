<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول']);
    exit;
}

if (!isset($_POST['document_id']) || empty($_POST['document_id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف المستند مطلوب']);
    exit;
}

$document_id = intval($_POST['document_id']);

// Démarrer une transaction
mysqli_begin_transaction($connection);

try {
    // Récupérer les infos du document
    $select_stmt = mysqli_prepare($connection, "SELECT file_path, document_name FROM detachement_documents WHERE id = ? FOR UPDATE");
    mysqli_stmt_bind_param($select_stmt, "i", $document_id);
    mysqli_stmt_execute($select_stmt);
    $result = mysqli_stmt_get_result($select_stmt);
    
    if (mysqli_num_rows($result) == 0) {
        throw new Exception('المستند غير موجود');
    }
    
    $document = mysqli_fetch_assoc($result);
    $file_path = $document['file_path'];
    mysqli_stmt_close($select_stmt);
    
    // Supprimer l'enregistrement
    $delete_stmt = mysqli_prepare($connection, "DELETE FROM detachement_documents WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, "i", $document_id);
    if (!mysqli_stmt_execute($delete_stmt)) {
        throw new Exception('خطأ في حذف المستند من قاعدة البيانات');
    }
    mysqli_stmt_close($delete_stmt);
    
    // Supprimer le fichier
    if (file_exists($file_path) && !unlink($file_path)) {
        // Log l'erreur mais ne pas annuler la transaction
        error_log("Warning: Could not delete file: " . $file_path);
    }
    
    // Valider la transaction
    mysqli_commit($connection);
    
    echo json_encode(['success' => true, 'message' => 'تم حذف المستند بنجاح']);
    
} catch (Exception $e) {
    mysqli_rollback($connection);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>