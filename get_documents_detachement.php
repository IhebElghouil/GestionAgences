<?php
// get_documents_detachement.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح', 'documents' => []]);
    exit;
}

$detachement_id = isset($_GET['detachement_id']) ? intval($_GET['detachement_id']) : 0;

if ($detachement_id <= 0) {
    echo json_encode(['success' => false, 'documents' => []]);
    exit;
}

require_once('connection.php');

$sql = "SELECT id, document_name, document_type, file_path, file_size, uploaded_by, upload_date, description 
        FROM detachement_documents WHERE detachement_id = $detachement_id ORDER BY upload_date DESC";
$result = mysqli_query($connection, $sql);

$documents = [];
while ($row = mysqli_fetch_assoc($result)) {
    $documents[] = $row;
}

echo json_encode(['success' => true, 'documents' => $documents]);
mysqli_close($connection);
?>