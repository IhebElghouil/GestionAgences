<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح به']);
    exit;
}

// Include database connection
$connection = null;
$connection_file = '../connection.php';

if (file_exists($connection_file)) {
    require_once($connection_file);
    $connection = $GLOBALS['connection'] ?? null;
} else {
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

// Check if file was uploaded
if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
    $error_message = 'خطأ في رفع الملف';
    if (isset($_FILES['document']['error'])) {
        switch ($_FILES['document']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error_message = 'الملف كبير جداً';
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message = 'لم يتم اختيار ملف';
                break;
            default:
                $error_message = 'خطأ في رفع الملف: ' . $_FILES['document']['error'];
        }
    }
    echo json_encode(['success' => false, 'message' => $error_message]);
    exit;
}

$career_id = isset($_POST['career_id']) ? intval($_POST['career_id']) : 0;
if (!$career_id) {
    echo json_encode(['success' => false, 'message' => 'معرّف المسار المهني مطلوب']);
    exit;
}

// Check if career record exists
$check_query = "SELECT id FROM carriere WHERE id = ?";
$check_stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($check_stmt, "i", $career_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'سجل المسار المهني غير موجود']);
    mysqli_stmt_close($check_stmt);
    exit;
}
mysqli_stmt_close($check_stmt);

// Create upload directory if it doesn't exist
$upload_dir = dirname(__FILE__) . '/../uploads/career_documents/';
if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'خطأ في إنشاء مجلد الرفع']);
        exit;
    }
}

// Check if table exists, create if not
$table_check = mysqli_query($connection, "SHOW TABLES LIKE 'career_documents'");
if (mysqli_num_rows($table_check) == 0) {
    $create_table = "CREATE TABLE IF NOT EXISTS `career_documents` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `career_id` int(11) NOT NULL,
        `file_name` varchar(255) NOT NULL,
        `file_path` varchar(255) NOT NULL,
        `upload_date` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `career_id` (`career_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (!mysqli_query($connection, $create_table)) {
        echo json_encode(['success' => false, 'message' => 'خطأ في إنشاء جدول المستندات']);
        exit;
    }
}

// Process uploaded file
$file = $_FILES['document'];
$original_name = basename($file['name']);
$file_extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

// Allowed file types
$allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];
if (!in_array($file_extension, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'نوع الملف غير مسموح به. الأنواع المسموحة: ' . implode(', ', $allowed_types)]);
    exit;
}

// Max file size (10 MB)
if ($file['size'] > 10 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'حجم الملف يتجاوز 10 ميجابايت']);
    exit;
}

// Generate unique filename
$unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
$file_path = $upload_dir . $unique_filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $file_path)) {
    // Save to database
    $insert_query = "INSERT INTO career_documents (career_id, file_name, file_path, upload_date) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($connection, $insert_query);
    mysqli_stmt_bind_param($stmt, "iss", $career_id, $original_name, $unique_filename);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'تم رفع الملف بنجاح', 'document_id' => mysqli_insert_id($connection)]);
    } else {
        // Delete file if database insert fails
        unlink($file_path);
        echo json_encode(['success' => false, 'message' => 'خطأ في حفظ المعلومات في قاعدة البيانات: ' . mysqli_error($connection)]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['success' => false, 'message' => 'خطأ في نقل الملف إلى الخادم']);
}
?>