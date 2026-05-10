<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors in output
ini_set('log_errors', 1);     // Log errors to server log

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح به']);
    exit;
}

// Include database connection - adjust path as needed
$connection = null;
$connection_file = '../connection.php';

if (file_exists($connection_file)) {
    require_once($connection_file);
    $connection = $GLOBALS['connection'] ?? null;
} else {
    // Try alternative path
    $connection_file = 'connection.php';
    if (file_exists($connection_file)) {
        require_once($connection_file);
        $connection = $GLOBALS['connection'] ?? null;
    }
}

if (!$connection) {
    echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
    exit;
}

// Get career_id parameter
$career_id = isset($_GET['career_id']) ? intval($_GET['career_id']) : 0;

if (!$career_id) {
    echo json_encode(['success' => false, 'message' => 'معرّف المسار المهني مطلوب']);
    exit;
}

// Check if table exists first
$table_check = mysqli_query($connection, "SHOW TABLES LIKE 'career_documents'");
if (mysqli_num_rows($table_check) == 0) {
    // Table doesn't exist, return empty documents
    echo json_encode(['success' => true, 'documents' => [], 'message' => 'لا توجد مستندات']);
    exit;
}

// Query documents
$query = "SELECT id, career_id, file_name, file_path, upload_date FROM career_documents WHERE career_id = ? ORDER BY upload_date DESC";
$stmt = mysqli_prepare($connection, $query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'خطأ في تحضير الاستعلام: ' . mysqli_error($connection)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $career_id);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => false, 'message' => 'خطأ في تنفيذ الاستعلام: ' . mysqli_stmt_error($stmt)]);
    mysqli_stmt_close($stmt);
    exit;
}

$result = mysqli_stmt_get_result($stmt);
$documents = [];

while ($row = mysqli_fetch_assoc($result)) {
    $documents[] = [
        'id' => $row['id'],
        'career_id' => $row['career_id'],
        'file_name' => $row['file_name'],
        'file_path' => $row['file_path'],
        'upload_date' => $row['upload_date']
    ];
}

mysqli_stmt_close($stmt);

echo json_encode(['success' => true, 'documents' => $documents]);
?>