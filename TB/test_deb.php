<?php
// test_db.php - اختبار الاتصال بقاعدة البيانات
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>اختبار الاتصال بقاعدة البيانات</h1>";
echo "<hr>";

// استخدام المنفذ الصحيح 3307
echo "<h3>محاولة الاتصال بقاعدة البيانات pointage:</h3>";
try {
    $conn = new PDO("mysql:host=127.0.0.1;port=3307;dbname=pointage;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✓ الاتصال بقاعدة البيانات pointage ناجح!</p>";
    
    // عرض جميع الجداول
    $stmt = $conn->query("SHOW TABLES");
    echo "<h3>الجداول الموجودة في قاعدة البيانات:</h3>";
    echo "<ul>";
    $tables = [];
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    
    // اختبار جدول stuf
    if (in_array('stuf', $tables)) {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM stuf WHERE etat = 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p style='color:green'>✓ عدد الموظفين النشطين في stuf: " . $row['count'] . "</p>";
    } else {
        echo "<p style='color:orange'>⚠ جدول stuf غير موجود</p>";
    }
    
    // اختبار جدول depart
    if (in_array('depart', $tables)) {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM depart");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p style='color:green'>✓ عدد المغادرين في depart: " . $row['count'] . "</p>";
    } else {
        echo "<p style='color:orange'>⚠ جدول depart غير موجود</p>";
    }
    
    // اختبار جدول conge
    if (in_array('conge', $tables)) {
        $stmt = $conn->query("SELECT COUNT(*) as count FROM conge");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p style='color:green'>✓ عدد الإجازات في conge: " . $row['count'] . "</p>";
    } else {
        echo "<p style='color:orange'>⚠ جدول conge غير موجود</p>";
    }
    
    $conn = null;
    
} catch(PDOException $e) {
    echo "<p style='color:red'>✗ خطأ: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>ملاحظة:</h3>";
echo "<p>MySQL يعمل على المنفذ <strong>3307</strong> وليس المنفذ الافتراضي 3306</p>";
?>