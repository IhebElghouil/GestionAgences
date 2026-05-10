<?php
session_start();
require('connection.php');

// التحقق من الصلاحيات
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] != "admin") {
    header("HTTP/1.1 403 Forbidden");
    exit("ليس لديك صلاحية للقيام بهذه العملية");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "UPDATE detachement SET statut = 1 WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($connection);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "error: طلب غير صالح";
}
?>