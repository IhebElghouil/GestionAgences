<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

if (!isset($_GET['career_id']) || empty($_GET['career_id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف غير صحيح']);
    exit;
}

$career_id = intval($_GET['career_id']);

$sql = "SELECT * FROM career_documents WHERE career_id = ? ORDER BY uploaded_at DESC";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'i', $career_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$documents = [];
while ($row = mysqli_fetch_assoc($result)) {
    $documents[] = [
        'id' => $row['id'],
        'file_name' => $row['file_name'],
        'file_path' => $row['file_path'],
        'file_size' => $row['file_size'],
        'file_type' => $row['file_type'],
        'uploaded_at' => $row['uploaded_at']
    ];
}

echo json_encode([
    'success' => true,
    'documents' => $documents
]);

mysqli_stmt_close($stmt);
?>