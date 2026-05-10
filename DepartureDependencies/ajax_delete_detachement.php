<?php
session_start();
include('Cnx_Include.php');

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

$query = "DELETE FROM detachement WHERE id='$id'";

if (mysqli_query($connection, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($connection)]);
}
?>