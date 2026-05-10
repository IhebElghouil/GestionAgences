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

// Compter les documents
$query = "SELECT COUNT(*) as count FROM detachement_documents WHERE detachement_id = $detachement_id";
$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . mysqli_error($connection)]);
    exit;
}

$row = mysqli_fetch_assoc($result);
$count = $row['count'] ?? 0;

echo json_encode([
    'success' => true,
    'count' => $count,
    'detachement_id' => $detachement_id
]);
?>