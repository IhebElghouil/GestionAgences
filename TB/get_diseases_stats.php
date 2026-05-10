<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'stats' => []];

try {
    if (!isset($_SESSION['congidGA'])) {
        $response['message'] = 'غير مصرح بالوصول';
        echo json_encode($response);
        exit;
    }

    $year = $_POST['year'] ?? date('Y');
    $department = $_POST['department'] ?? 'all';

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];

    // إحصائيات الأمراض (type=5 في autreconge)
    $diseasesByType = getDiseasesByType($connection, $departements, $year, $department);
    
    // إحصائيات الأمراض حسب الإدارة
    $diseasesByDepartment = getDiseasesByDepartment($connection, $departements, $year);
    
    // إحصائيات الأمراض حسب الرتبة
    $diseasesByGrade = getDiseasesByGrade($connection, $departements, $year, $department);

    // الإحصائيات الإجمالية
    $totalStats = getTotalDiseaseStats($connection, $departements, $year, $department);

    $response['success'] = true;
    $response['stats'] = [
        'byType' => $diseasesByType,
        'byDepartment' => $diseasesByDepartment,
        'byGrade' => $diseasesByGrade,
        'totalStats' => $totalStats,
        'monthlyData' => getMonthlyDiseaseData($connection, $departements, $year, $department)
    ];

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

// دالة للحصول على الإحصائيات الإجمالية للأمراض
function getTotalDiseaseStats($connection, $departements, $year, $department) {
    $query = "SELECT COUNT(*) as total_diseases,
                     COUNT(DISTINCT mecano) as total_employees,
                     SUM(DATEDIFF(datefin, datedebut) + 1) as total_days,
                     AVG(DATEDIFF(datefin, datedebut) + 1) as avg_days
              FROM autreconge 
              WHERE YEAR(datedebut) = ? AND type = 5";
    
    // إضافة فلاتر الصلاحيات والإدارة
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep IN ($placeholders))";
    }
    
    if ($department !== 'all') {
        $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep = ?)";
    }
    
    $stmt = mysqli_prepare($connection, $query);
    
    $params = [$year];
    $types = 'i';
    
    if ($_SESSION['departement'] !== "admin") {
        $types .= str_repeat('i', count($departements));
        $params = array_merge($params, $departements);
    }
    
    if ($department !== 'all') {
        $types .= 'i';
        $params[] = $department;
    }
    
    if ($_SESSION['departement'] !== "admin" || $department !== 'all') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $year);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_diseases, $total_employees, $total_days, $avg_days);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    return [
        'total_diseases' => $total_diseases ?? 0,
        'total_employees' => $total_employees ?? 0,
        'total_days' => $total_days ?? 0,
        'avg_days' => round($avg_days ?? 0, 1)
    ];
}

// دالة للحصول على الأمراض حسب النوع (type2 في autreconge)
function getDiseasesByType($connection, $departements, $year, $department) {
    $query = "SELECT type2, COUNT(*) as count,
                     COUNT(DISTINCT mecano) as employees,
                     SUM(DATEDIFF(datefin, datedebut) + 1) as total_days,
                     AVG(DATEDIFF(datefin, datedebut) + 1) as avg_days
              FROM autreconge 
              WHERE YEAR(datedebut) = ? AND type = 5
              GROUP BY type2";
    
    // إضافة فلاتر الصلاحيات والإدارة
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep IN ($placeholders))";
    }
    
    if ($department !== 'all') {
        $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep = ?)";
    }
    
    $stmt = mysqli_prepare($connection, $query);
    
    $params = [$year];
    $types = 'i';
    
    if ($_SESSION['departement'] !== "admin") {
        $types .= str_repeat('i', count($departements));
        $params = array_merge($params, $departements);
    }
    
    if ($department !== 'all') {
        $types .= 'i';
        $params[] = $department;
    }
    
    if ($_SESSION['departement'] !== "admin" || $department !== 'all') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $year);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $type2, $count, $employees, $total_days, $avg_days);
    
    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $typeName = getDiseaseTypeName($type2);
        $result[] = [
            'type_id' => $type2,
            'type_name' => $typeName,
            'count' => $count,
            'employees' => $employees,
            'total_days' => $total_days ?? 0,
            'avg_days' => round($avg_days ?? 0, 1)
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

// دالة للحصول على اسم نوع المرض
function getDiseaseTypeName($type2) {
    $types = [
        1 => 'أمراض الجهاز التنفسي',
        2 => 'أمراض الجلدية', 
        3 => 'أمراض العظام',
        4 => 'أمراض السمع',
        5 => 'أمراض بصرية',
        6 => 'أمراض نفسية'
        // يمكن إضافة المزيد من الأنواع حسب الحاجة
    ];
    
    return $types[$type2] ?? 'نوع غير معروف';
}

// دالة للحصول على الأمراض حسب الإدارة
function getDiseasesByDepartment($connection, $departements, $year) {
    $query = "SELECT d.depar, COUNT(a.id) as count,
                     COUNT(DISTINCT a.mecano) as employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN dep d ON s.dep = d.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 5";
    
    // إضافة فلاتر الصلاحيات
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    $query .= " GROUP BY d.depar ORDER BY count DESC LIMIT 10";
    
    $stmt = mysqli_prepare($connection, $query);
    
    $params = [$year];
    $types = 'i';
    
    if ($_SESSION['departement'] !== "admin") {
        $types .= str_repeat('i', count($departements));
        $params = array_merge($params, $departements);
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $depar, $count, $employees, $total_days);
    
    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $result[] = [
            'department' => $depar,
            'count' => $count,
            'employees' => $employees,
            'total_days' => $total_days ?? 0
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

// دالة للحصول على الأمراض حسب الرتبة
function getDiseasesByGrade($connection, $departements, $year, $department) {
    $query = "SELECT t.libellet, COUNT(a.id) as count,
                     COUNT(DISTINCT a.mecano) as employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN titres t ON s.titre = t.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 5";
    
    // إضافة فلاتر الصلاحيات والإدارة
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    if ($department !== 'all') {
        $query .= " AND s.dep = ?";
    }
    
    $query .= " GROUP BY t.libellet ORDER BY count DESC";
    
    $stmt = mysqli_prepare($connection, $query);
    
    $params = [$year];
    $types = 'i';
    
    if ($_SESSION['departement'] !== "admin") {
        $types .= str_repeat('i', count($departements));
        $params = array_merge($params, $departements);
    }
    
    if ($department !== 'all') {
        $types .= 'i';
        $params[] = $department;
    }
    
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $grade, $count, $employees, $total_days);
    
    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $result[] = [
            'grade' => $grade,
            'count' => $count,
            'employees' => $employees,
            'total_days' => $total_days ?? 0
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

// دالة للحصول على البيانات الشهرية للأمراض
function getMonthlyDiseaseData($connection, $departements, $year, $department) {
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $query = "SELECT COUNT(*) as count,
                         COUNT(DISTINCT mecano) as employees,
                         SUM(DATEDIFF(datefin, datedebut) + 1) as total_days
                  FROM autreconge 
                  WHERE YEAR(datedebut) = ? AND MONTH(datedebut) = ? AND type = 5";
        
        // إضافة فلاتر الصلاحيات والإدارة
        if ($_SESSION['departement'] !== "admin") {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep IN ($placeholders))";
        }
        
        if ($department !== 'all') {
            $query .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep = ?)";
        }
        
        $stmt = mysqli_prepare($connection, $query);
        
        $params = [$year, $i];
        $types = 'ii';
        
        if ($_SESSION['departement'] !== "admin") {
            $types .= str_repeat('i', count($departements));
            $params = array_merge($params, $departements);
        }
        
        if ($department !== 'all') {
            $types .= 'i';
            $params[] = $department;
        }
        
        if ($_SESSION['departement'] !== "admin" || $department !== 'all') {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        } else {
            mysqli_stmt_bind_param($stmt, 'ii', $year, $i);
        }
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count, $employees, $total_days);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        $months[$i] = [
            'month' => $i,
            'count' => $count ?? 0,
            'employees' => $employees ?? 0,
            'total_days' => $total_days ?? 0,
            'avg_days' => $count > 0 ? round($total_days / $count, 1) : 0
        ];
    }
    
    return $months;
}

echo json_encode($response);
?>