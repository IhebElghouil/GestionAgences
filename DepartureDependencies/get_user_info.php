<?php
// تعطيل عرض الأخطاء في الإخراج
error_reporting(E_ALL);
ini_set('display_errors', 0);

// تأكيد أن الرد سيكون JSON فقط
header('Content-Type: application/json');

require_once __DIR__ . '/../connection.php';

// التحقق من الاتصال بقاعدة البيانات
if (!isset($connection) || !$connection) {
    echo json_encode([
        'success' => false,
        'error' => 'فشل الاتصال بقاعدة البيانات'
    ]);
    exit;
}

// التحقق من وجود معامل mecano
if (!isset($_GET['mecano']) || empty($_GET['mecano'])) {
    echo json_encode([
        'success' => false,
        'error' => 'الرقم الآلي مطلوب'
    ]);
    exit;
}

$mecano = $_GET['mecano'];

// تصحيح الاستعلام - إزالة الفاصلة الزائدة وإضافة الأسماء المستعارة
$query = "SELECT 
            stuf.nom, 
            stuf.daten, 
            dep.depar, 
            nbconge.rest,
            DATE_ADD(stuf.daten, INTERVAL 60 YEAR) as dateretraite
          FROM stuf
          LEFT JOIN dep ON stuf.dep = dep.id
          LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano
          WHERE stuf.contrastage IN (0,1,3) AND stuf.mecano = ?";

$stmt = mysqli_prepare($connection, $query);

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'error' => 'خطأ في تحضير الاستعلام: ' . mysqli_error($connection)
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $mecano);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nom, $daterecrutement, $departement, $restconge, $dateretraite);

if (mysqli_stmt_fetch($stmt)) {
    // تنظيف القيم
    $nom = $nom ?? '';
    $daterecrutement = $daterecrutement ?? '';
    $departement = $departement ?? '';
    $restconge = $restconge ?? '0';
    $dateretraite = $dateretraite ?? '';
    
    // تنسيق تاريخ التقاعد إذا كان موجوداً
    if (!empty($dateretraite) && $dateretraite != '0000-00-00') {
        $dateretraite = date('Y-m-d', strtotime($dateretraite));
    } else {
        $dateretraite = '';
    }
    
    // تنسيق تاريخ التوظيف
    if (!empty($daterecrutement) && $daterecrutement != '0000-00-00') {
        $daterecrutement = date('Y-m-d', strtotime($daterecrutement));
    } else {
        $daterecrutement = '';
    }
    
    echo json_encode([
        'success' => true,
        'nom' => $nom,
        'daterecrutement' => $daterecrutement,
        'departement' => $departement,
        'restconge' => $restconge,
        'dateretraite' => $dateretraite
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'الرقم الآلي ' . $mecano . ' غير موجود',
        'nom' => '',
        'daterecrutement' => '',
        'departement' => '',
        'restconge' => '',
        'dateretraite' => ''
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>