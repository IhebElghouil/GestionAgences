<?php
// debug_delete.php
session_start();
require('connection.php');

// إظهار جميع الأخطاء
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>فحص نظام الحذف</h2>";

// فحص الجلسة
echo "<h3>1. فحص الجلسة</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID: " . ($_SESSION['congidGA'] ?? 'غير مسجل') . "<br>";
echo "Departement: " . ($_SESSION['departement'] ?? 'غير محدد') . "<br>";
echo "Username: " . ($_SESSION['username'] ?? 'غير معروف') . "<br>";

// فحص قاعدة البيانات
echo "<h3>2. فحص قاعدة البيانات</h3>";
try {
    $test_query = "SELECT COUNT(*) as count FROM sanctions";
    $result = mysqli_query($connection, $test_query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "عدد السجلات في sanctions: " . $row['count'] . "<br>";
    } else {
        echo "خطأ في الاتصال بقاعدة البيانات: " . mysqli_error($connection) . "<br>";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "<br>";
}

// فحص جدول سجل الحذف
echo "<h3>3. فحص جدول سجل الحذف</h3>";
$checkTable = "SHOW TABLES LIKE 'sanctions_deletion_log'";
$tableResult = mysqli_query($connection, $checkTable);
if (mysqli_num_rows($tableResult) > 0) {
    echo "✓ جدول sanctions_deletion_log موجود<br>";
    
    // عرض بعض السجلات
    $countQuery = "SELECT COUNT(*) as count FROM sanctions_deletion_log";
    $countResult = mysqli_query($connection, $countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    echo "عدد السجلات في سجل الحذف: " . $countRow['count'] . "<br>";
} else {
    echo "✗ جدول sanctions_deletion_log غير موجود<br>";
}

// فحص دالة JSON
echo "<h3>4. فحص دالة JSON</h3>";
$test_array = ['success' => true, 'message' => 'اختبار'];
$json_output = json_encode($test_array, JSON_UNESCAPED_UNICODE);
echo "JSON encode test: " . $json_output . "<br>";
echo "JSON last error: " . json_last_error_msg() . "<br>";

// فحص صلاحيات المجلد
echo "<h3>5. فحص صلاحيات المجلد</h3>";
$upload_dir = 'uploads/';
if (is_dir($upload_dir)) {
    echo "✓ مجلد uploads موجود<br>";
    echo "صلاحيات المجلد: " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "<br>";
} else {
    echo "✗ مجلد uploads غير موجود<br>";
}

// زر لاختبار الحذف
echo "<h3>6. اختبار الحذف</h3>";
echo '<form id="testDeleteForm" method="POST">';
echo '<input type="hidden" name="id" value="1">';
echo '<input type="hidden" name="reason" value="اختبار حذف">';
echo '<button type="button" onclick="testDelete()">اختبار الحذف</button>';
echo '</form>';

echo '<div id="testResult"></div>';

mysqli_close($connection);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testDelete() {
    $.ajax({
        url: 'delete_sanction.php',
        type: 'POST',
        data: {
            id: 1,
            reason: 'اختبار حذف'
        },
        dataType: 'json',
        success: function(response) {
            $('#testResult').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
        },
        error: function(xhr, status, error) {
            $('#testResult').html('<div style="color:red">خطأ: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
        }
    });
}
</script>