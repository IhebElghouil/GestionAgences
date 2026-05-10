<?php
session_start();
require_once __DIR__ . '/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'معرف غير صحيح']);
    exit;
}

$query = "UPDATE detachement SET statut=1 WHERE id='$id'";

if (mysqli_query($connection, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($connection)]);
}
?>