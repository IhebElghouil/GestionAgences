<?php
// delete_document_detachment.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$document_id = isset($_POST['document_id']) ? intval($_POST['document_id']) : 0;

if ($document_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'معرف غير صحيح']);
    exit;
}

require_once('connection.php');

$sql = "SELECT file_path FROM detachement_documents WHERE id = $document_id";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$file_path = $row['file_path'];

$sql = "DELETE FROM detachement_documents WHERE id = $document_id";
if (mysqli_query($connection, $sql)) {
    if ($file_path && file_exists($file_path)) {
        unlink($file_path);
    }
    echo json_encode(['success' => true, 'message' => 'تم حذف المستند بنجاح']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في الحذف']);
}
mysqli_close($connection);
?>