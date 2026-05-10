<?php
// upload_document_detachement.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// بدء الجلسة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// تعيين رأس JSON
header('Content-Type: application/json');

// دالة لإرجاع الاستجابة
function sendResponse($success, $message, $data = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// التحقق من الصلاحيات
if (!isset($_SESSION['congidGA'])) {
    sendResponse(false, 'الرجاء تسجيل الدخول أولاً');
}

if ($_SESSION['departement'] !== "admin") {
    sendResponse(false, 'غير مصرح لك برفع الملفات');
}

// التحقق من طريقة الطلب
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'طريقة طلب غير صحيحة');
}

// التحقق من معرف الملحق
$detachement_id = isset($_POST['detachement_id']) ? intval($_POST['detachement_id']) : 0;
if ($detachement_id <= 0) {
    sendResponse(false, 'معرف الملحق غير صحيح');
}

// الاتصال بقاعدة البيانات
require_once('connection.php');

if (!isset($connection) || !$connection) {
    sendResponse(false, 'خطأ في الاتصال بقاعدة البيانات');
}

// التحقق من وجود الملحق
$check_sql = "SELECT id, nomprenom FROM detachement WHERE id = ?";
$check_stmt = mysqli_prepare($connection, $check_sql);
if (!$check_stmt) {
    sendResponse(false, 'خطأ في التحقق من الملحق: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($check_stmt, "i", $detachement_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) == 0) {
    mysqli_stmt_close($check_stmt);
    sendResponse(false, 'الملحق غير موجود في قاعدة البيانات. المعرف: ' . $detachement_id);
}
$detachement_data = mysqli_fetch_assoc($check_result);
mysqli_stmt_close($check_stmt);

// التحقق من وجود الملف
if (!isset($_FILES['document'])) {
    sendResponse(false, 'لم يتم اختيار ملف');
}

if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
    $error_messages = [
        UPLOAD_ERR_INI_SIZE => 'حجم الملف كبير جداً (الحد الأقصى ' . ini_get('upload_max_filesize') . ')',
        UPLOAD_ERR_FORM_SIZE => 'حجم الملف كبير جداً',
        UPLOAD_ERR_PARTIAL => 'تم رفع الملف جزئياً',
        UPLOAD_ERR_NO_FILE => 'لم يتم اختيار ملف',
        UPLOAD_ERR_NO_TMP_DIR => 'المجلد المؤقت غير موجود',
        UPLOAD_ERR_CANT_WRITE => 'فشل كتابة الملف على القرص',
        UPLOAD_ERR_EXTENSION => 'تم منع رفع الملف بواسطة امتداد PHP'
    ];
    $error_code = $_FILES['document']['error'];
    $error_msg = isset($error_messages[$error_code]) ? $error_messages[$error_code] : 'خطأ غير معروف';
    sendResponse(false, $error_msg);
}

$file = $_FILES['document'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];

// التحقق من امتداد الملف
$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
$allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];

if (!in_array($file_ext, $allowed_extensions)) {
    sendResponse(false, 'نوع الملف غير مسموح. الأنواع المسموحة: ' . implode(', ', $allowed_extensions));
}

// التحقق من حجم الملف (10MB)
if ($file_size > 10 * 1024 * 1024) {
    sendResponse(false, 'حجم الملف يتجاوز 10 ميجابايت');
}

// إنشاء مجلد الرفع - استخدام مسار مطلق
$upload_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'detachement_docs' . DIRECTORY_SEPARATOR;

if (!file_exists($upload_dir)) {
    if (!mkdir($upload_dir, 0777, true)) {
        sendResponse(false, 'لا يمكن إنشاء مجلد الرفع: ' . $upload_dir);
    }
}

// التحقق من صلاحيات الكتابة
if (!is_writable($upload_dir)) {
    sendResponse(false, 'مجلد الرفع غير قابل للكتابة. الرجاء تغيير الصلاحيات إلى 777');
}

// إنشاء اسم فريد للملف
$new_file_name = 'detachement_' . $detachement_id . '_' . time() . '_' . uniqid() . '.' . $file_ext;
$file_path = $upload_dir . $new_file_name;

// نقل الملف
if (!move_uploaded_file($file_tmp, $file_path)) {
    sendResponse(false, 'فشل نقل الملف إلى المجلد النهائي');
}

// المسار النسبي للويب
$web_path = 'uploads/detachement_docs/' . $new_file_name;
$description = isset($_POST['description']) ? mysqli_real_escape_string($connection, $_POST['description']) : '';
$uploaded_by = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['congidGA'];

// إدراج في قاعدة البيانات
$query = "INSERT INTO detachement_documents (detachement_id, document_name, document_type, file_path, file_size, uploaded_by, description) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($connection, $query);
if (!$stmt) {
    unlink($file_path);
    sendResponse(false, 'خطأ في تحضير الاستعلام: ' . mysqli_error($connection));
}

mysqli_stmt_bind_param($stmt, "isssiss", $detachement_id, $file_name, $file_ext, $web_path, $file_size, $uploaded_by, $description);

if (mysqli_stmt_execute($stmt)) {
    sendResponse(true, 'تم رفع الملف بنجاح للملحق: ' . $detachement_data['nomprenom'], [
        'id' => mysqli_insert_id($connection),
        'file_name' => $file_name,
        'file_path' => $web_path
    ]);
} else {
    unlink($file_path);
    sendResponse(false, 'خطأ في حفظ الملف في قاعدة البيانات: ' . mysqli_error($connection));
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>