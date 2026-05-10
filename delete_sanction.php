<?php
// delete_sanction_simple.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require('connection.php');

// فحص بسيط
if (empty($_SESSION['congidGA']) || $_SESSION['departement'] != "admin") {
    die(json_encode(['success' => false, 'message' => 'صلاحيات غير كافية']));
}

$id = intval($_POST['id'] ?? 0);
$reason = trim($_POST['reason'] ?? '');

if ($id < 1 || empty($reason)) {
    die(json_encode(['success' => false, 'message' => 'بيانات غير صحيحة']));
}

// الحصول على بيانات العقوبة
$sql = "SELECT * FROM sanctions WHERE idsanction = $id";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) == 0) {
    die(json_encode(['success' => false, 'message' => 'لم يتم العثور على العقوبة']));
}

$data = mysqli_fetch_assoc($result);

// إنشاء جدول السجل إذا لم يكن موجوداً
mysqli_query($connection, "
    CREATE TABLE IF NOT EXISTS sanctions_deletion_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        deleted_sanction_id INT,
        deleted_mecano VARCHAR(50),
        deleted_nom VARCHAR(100),
        deleted_by_user VARCHAR(100),
        deletion_reason TEXT,
        deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// حفظ السجل
$user = $_SESSION['username'] ?? 'مستخدم';
$log_sql = "INSERT INTO sanctions_deletion_log 
            (deleted_sanction_id, deleted_mecano, deleted_nom, deleted_by_user, deletion_reason) 
            VALUES ('{$data['idsanction']}', '{$data['mecano']}', '{$data['nom']}', '$user', '$reason')";
mysqli_query($connection, $log_sql);

// حذف العقوبة
$delete_sql = "DELETE FROM sanctions WHERE idsanction = $id";
mysqli_query($connection, $delete_sql);

// حذف الملفات
$folder = 'uploads/';
if (is_dir($folder)) {
    $files = [$data['questionnaire_file'], $data['report_file'], $data['sanction_decision_file']];
    foreach ($files as $file) {
        if ($file && file_exists($folder . $file)) {
            unlink($folder . $file);
        }
    }
}

echo json_encode([
    'success' => true,
    'message' => 'تم الحذف بنجاح'
], JSON_UNESCAPED_UNICODE);

mysqli_close($connection);
?>