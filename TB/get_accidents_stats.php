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
    $type = $_POST['type'] ?? 'all';
    $department = $_POST['department'] ?? 'all';

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];

    // إحصائيات الحوادث حسب النوع (type=6 في autreconge)
    $accidentsByType = getAccidentsByType($connection, $departements, $year, $department);
    
    // إحصائيات الحوادث حسب الإدارة
    $accidentsByDepartment = getAccidentsByDepartment($connection, $departements, $year, $type);
    
    // إحصائيات الحوادث حسب الرتبة
    $accidentsByGrade = getAccidentsByGrade($connection, $departements, $year, $type, $department);

    // الإحصائيات الإجمالية
    $totalStats = getTotalAccidentStats($connection, $departements, $year, $department);

    // البيانات الشهرية مع تفصيل حسب النوع
    $monthlyData = getMonthlyAccidentData($connection, $departements, $year, $department);
    
    // الحوادث حسب الإدارة مع تفصيل حسب النوع
    $accidentsByDepartmentDetailed = getAccidentsByDepartmentDetailed($connection, $departements, $year, $department);
    
    // الحوادث حسب الرتبة مع تفصيل حسب النوع
    $accidentsByGradeDetailed = getAccidentsByGradeDetailed($connection, $departements, $year, $department);

    $response['success'] = true;
    $response['stats'] = [
        'byType' => $accidentsByType,
        'byDepartment' => $accidentsByDepartment,
        'byGrade' => $accidentsByGrade,
        'byDepartmentDetailed' => $accidentsByDepartmentDetailed,
        'byGradeDetailed' => $accidentsByGradeDetailed,
        'totalStats' => $totalStats,
        'monthlyData' => $monthlyData,
        'monthlyDetailed' => getMonthlyAccidentDataDetailed($connection, $departements, $year, $department)
    ];

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

// دالة للحصول على الإحصائيات الإجمالية للحوادث
function getTotalAccidentStats($connection, $departements, $year, $department) {
    $query = "SELECT COUNT(*) as total_accidents,
                     COUNT(DISTINCT mecano) as total_employees,
                     SUM(DATEDIFF(datefin, datedebut) + 1) as total_days,
                     AVG(DATEDIFF(datefin, datedebut) + 1) as avg_days,
                     SUM(CASE WHEN type2 = 11 THEN 1 ELSE 0 END) as initial_accidents,
                     SUM(CASE WHEN type2 = 9 THEN 1 ELSE 0 END) as prolongation_accidents,
                     SUM(CASE WHEN type2 = 10 THEN 1 ELSE 0 END) as relapse_accidents
              FROM autreconge 
              WHERE YEAR(datedebut) = ? AND type = 6";
    
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
    mysqli_stmt_bind_result($stmt, $total_accidents, $total_employees, $total_days, $avg_days, $initial_accidents, $prolongation_accidents, $relapse_accidents);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    return [
        'total_accidents' => $total_accidents ?? 0,
        'total_employees' => $total_employees ?? 0,
        'total_days' => $total_days ?? 0,
        'avg_days' => round($avg_days ?? 0, 1),
        'initial_accidents' => $initial_accidents ?? 0,
        'prolongation_accidents' => $prolongation_accidents ?? 0,
        'relapse_accidents' => $relapse_accidents ?? 0
    ];
}

// دالة للحصول على الحوادث حسب النوع (type2 في autreconge)
function getAccidentsByType($connection, $departements, $year, $department) {
    $query = "SELECT type2, COUNT(*) as count,
                     COUNT(DISTINCT mecano) as employees,
                     SUM(DATEDIFF(datefin, datedebut) + 1) as total_days,
                     AVG(DATEDIFF(datefin, datedebut) + 1) as avg_days
              FROM autreconge 
              WHERE YEAR(datedebut) = ? AND type = 6
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
        $typeName = getAccidentTypeName($type2);
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

// دالة للحصول على اسم نوع الحادث
function getAccidentTypeName($type2) {
    $types = [
        11 => 'حادث شغل أولي',
        9 => 'تمديد',
        10 => 'انتكاسة'
    ];
    
    return $types[$type2] ?? 'نوع غير معروف';
}

// دالة للحصول على الحوادث حسب الإدارة
function getAccidentsByDepartment($connection, $departements, $year, $type) {
    $query = "SELECT d.depar, COUNT(a.id) as count,
                     COUNT(DISTINCT a.mecano) as employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN dep d ON s.dep = d.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 6";
    
    // فلترة حسب نوع الحادث
    if ($type !== 'all') {
        $query .= " AND a.type2 = ?";
    }
    
    // إضافة فلاتر الصلاحيات
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    $query .= " GROUP BY d.depar ORDER BY count DESC LIMIT 10";
    
    $stmt = mysqli_prepare($connection, $query);
    
    $params = [$year];
    $types = 'i';
    
    if ($type !== 'all') {
        $types .= 'i';
        $params[] = $type;
    }
    
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

// دالة جديدة للحصول على الحوادث حسب الإدارة مع تفصيل حسب النوع
function getAccidentsByDepartmentDetailed($connection, $departements, $year, $department) {
    $query = "SELECT d.depar, 
                     COUNT(a.id) as total_count,
                     SUM(CASE WHEN a.type2 = 11 THEN 1 ELSE 0 END) as initial_count,
                     SUM(CASE WHEN a.type2 = 9 THEN 1 ELSE 0 END) as prolongation_count,
                     SUM(CASE WHEN a.type2 = 10 THEN 1 ELSE 0 END) as relapse_count,
                     COUNT(DISTINCT a.mecano) as total_employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN dep d ON s.dep = d.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 6";
    
    // إضافة فلاتر الصلاحيات
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    if ($department !== 'all') {
        $query .= " AND s.dep = ?";
    }
    
    $query .= " GROUP BY d.depar ORDER BY total_count DESC LIMIT 10";
    
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
    mysqli_stmt_bind_result($stmt, $depar, $total_count, $initial_count, $prolongation_count, $relapse_count, $total_employees, $total_days);
    
    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $result[] = [
            'department' => $depar,
            'total_count' => $total_count,
            'initial_count' => $initial_count ?? 0,
            'prolongation_count' => $prolongation_count ?? 0,
            'relapse_count' => $relapse_count ?? 0,
            'total_employees' => $total_employees,
            'total_days' => $total_days ?? 0
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

// دالة للحصول على الحوادث حسب الرتبة
function getAccidentsByGrade($connection, $departements, $year, $type, $department) {
    $query = "SELECT t.libellet, COUNT(a.id) as count,
                     COUNT(DISTINCT a.mecano) as employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN titres t ON s.titre = t.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 6";
    
    // فلترة حسب نوع الحادث
    if ($type !== 'all') {
        $query .= " AND a.type2 = ?";
    }
    
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
    
    if ($type !== 'all') {
        $types .= 'i';
        $params[] = $type;
    }
    
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

// دالة جديدة للحصول على الحوادث حسب الرتبة مع تفصيل حسب النوع
function getAccidentsByGradeDetailed($connection, $departements, $year, $department) {
    $query = "SELECT t.libellet, 
                     COUNT(a.id) as total_count,
                     SUM(CASE WHEN a.type2 = 11 THEN 1 ELSE 0 END) as initial_count,
                     SUM(CASE WHEN a.type2 = 9 THEN 1 ELSE 0 END) as prolongation_count,
                     SUM(CASE WHEN a.type2 = 10 THEN 1 ELSE 0 END) as relapse_count,
                     COUNT(DISTINCT a.mecano) as total_employees,
                     SUM(DATEDIFF(a.datefin, a.datedebut) + 1) as total_days
              FROM autreconge a
              JOIN stuf s ON a.mecano = s.mecano
              JOIN titres t ON s.titre = t.id
              WHERE YEAR(a.datedebut) = ? AND a.type = 6";
    
    // إضافة فلاتر الصلاحيات والإدارة
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    if ($department !== 'all') {
        $query .= " AND s.dep = ?";
    }
    
    $query .= " GROUP BY t.libellet ORDER BY total_count DESC";
    
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
    mysqli_stmt_bind_result($stmt, $grade, $total_count, $initial_count, $prolongation_count, $relapse_count, $total_employees, $total_days);
    
    $result = [];
    while (mysqli_stmt_fetch($stmt)) {
        $result[] = [
            'grade' => $grade,
            'total_count' => $total_count,
            'initial_count' => $initial_count ?? 0,
            'prolongation_count' => $prolongation_count ?? 0,
            'relapse_count' => $relapse_count ?? 0,
            'total_employees' => $total_employees,
            'total_days' => $total_days ?? 0
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    return $result;
}

// دالة للحصول على البيانات الشهرية للحوادث
function getMonthlyAccidentData($connection, $departements, $year, $department) {
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $query = "SELECT COUNT(*) as count,
                         COUNT(DISTINCT mecano) as employees,
                         SUM(DATEDIFF(datefin, datedebut) + 1) as total_days,
                         SUM(CASE WHEN type2 = 11 THEN 1 ELSE 0 END) as initial_count,
                         SUM(CASE WHEN type2 = 9 THEN 1 ELSE 0 END) as prolongation_count,
                         SUM(CASE WHEN type2 = 10 THEN 1 ELSE 0 END) as relapse_count
                  FROM autreconge 
                  WHERE YEAR(datedebut) = ? AND MONTH(datedebut) = ? AND type = 6";
        
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
        mysqli_stmt_bind_result($stmt, $count, $employees, $total_days, $initial_count, $prolongation_count, $relapse_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        $months[$i] = [
            'month' => $i,
            'count' => $count ?? 0,
            'employees' => $employees ?? 0,
            'total_days' => $total_days ?? 0,
            'initial_count' => $initial_count ?? 0,
            'prolongation_count' => $prolongation_count ?? 0,
            'relapse_count' => $relapse_count ?? 0,
            'avg_days' => $count > 0 ? round($total_days / $count, 1) : 0
        ];
    }
    
    return $months;
}

// دالة جديدة للحصول على البيانات الشهرية مفصلة حسب النوع (للمخططات)
function getMonthlyAccidentDataDetailed($connection, $departements, $year, $department) {
    $months = [];
    $monthNames = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل', 
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
    ];
    
    for ($i = 1; $i <= 12; $i++) {
        $query = "SELECT 
                         SUM(CASE WHEN type2 = 11 THEN 1 ELSE 0 END) as initial_count,
                         SUM(CASE WHEN type2 = 9 THEN 1 ELSE 0 END) as prolongation_count,
                         SUM(CASE WHEN type2 = 10 THEN 1 ELSE 0 END) as relapse_count,
                         COUNT(*) as total_count
                  FROM autreconge 
                  WHERE YEAR(datedebut) = ? AND MONTH(datedebut) = ? AND type = 6";
        
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
        mysqli_stmt_bind_result($stmt, $initial_count, $prolongation_count, $relapse_count, $total_count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        $months[] = [
            'month_number' => $i,
            'month_name' => $monthNames[$i],
            'initial_count' => $initial_count ?? 0,
            'prolongation_count' => $prolongation_count ?? 0,
            'relapse_count' => $relapse_count ?? 0,
            'total_count' => $total_count ?? 0
        ];
    }
    
    return $months;
}

echo json_encode($response);
?>