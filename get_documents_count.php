<?php
// get_documents_count.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$detachement_id = isset($_GET['detachement_id']) ? intval($_GET['detachement_id']) : 0;

if ($detachement_id <= 0) {
    echo json_encode(['success' => false, 'count' => 0]);
    exit;
}

require_once('connection.php');

$sql = "SELECT COUNT(*) as count FROM detachement_documents WHERE detachement_id = $detachement_id";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode(['success' => true, 'count' => $row['count']]);
mysqli_close($connection);
?>