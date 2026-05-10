<?php
session_start();
require('connection.php');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'معرف غير صالح']);
    exit;
}

$stmt = mysqli_prepare($connection, "DELETE FROM detachement WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات']);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>