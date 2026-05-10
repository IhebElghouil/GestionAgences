<?php
// get_reports_stats.php
require_once 'config.php';

$response = ['success' => false, 'stats' => []];

try {
    $conn = getConnection();
    
    $employeeType = isset($_POST['employeeType']) && $_POST['employeeType'] !== 'all' ? (int)$_POST['employeeType'] : null;
    $department = isset($_POST['department']) && $_POST['department'] !== 'all' ? (int)$_POST['department'] : null;
    
    // بناء الشروط
    $conditions = ["su.etat = 1"];
    $params = [];
    
    if ($employeeType !== null) {
        $conditions[] = "su.contrastage = ?";
        $params[] = $employeeType;
    }
    
    if ($department !== null) {
        $conditions[] = "su.idservice = ?";
        $params[] = $department;
    }
    
    $whereClause = "WHERE " . implode(" AND ", $conditions);
    
    // إجمالي الموظفين
    $sql = "SELECT COUNT(*) as total FROM stuf su $whereClause";
    $stmt = executeQuery($conn, $sql, $params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalEmployees = (int)($row['total'] ?? 0);
    
    // الموظفين حسب النوع (contrastage)
    $sql = "SELECT su.contrastage, COUNT(*) as count FROM stuf su $whereClause GROUP BY su.contrastage";
    $stmt = executeQuery($conn, $sql, $params);
    
    $permanentEmployees = 0;
    $traineeEmployees = 0;
    $attachedEmployees = 0;
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['contrastage'] == 0) $permanentEmployees = (int)$row['count'];
        if ($row['contrastage'] == 1) $traineeEmployees = (int)$row['count'];
        if ($row['contrastage'] == 3) $attachedEmployees = (int)$row['count'];
    }
    
    // متوسط العمر
    $sql = "SELECT AVG(TIMESTAMPDIFF(YEAR, su.daten, CURDATE())) as avg_age FROM stuf su $whereClause AND su.daten IS NOT NULL AND su.daten > '1900-01-01'";
    $stmt = executeQuery($conn, $sql, $params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $avgAge = round($row['avg_age'] ?? 0, 1);
    
    // متوسط الأقدمية
    $sql = "SELECT AVG(TIMESTAMPDIFF(YEAR, su.daterec, CURDATE())) as avg_seniority FROM stuf su $whereClause AND su.daterec IS NOT NULL AND su.daterec > '1900-01-01'";
    $stmt = executeQuery($conn, $sql, $params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $avgSeniority = round($row['avg_seniority'] ?? 0, 1);
    
    // التعيينات هذا الشهر
    $sql = "SELECT COUNT(*) as count FROM stuf WHERE MONTH(daterec) = MONTH(CURDATE()) AND YEAR(daterec) = YEAR(CURDATE()) AND etat = 1";
    $stmt = executeQuery($conn, $sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $monthlyHires = (int)($row['count'] ?? 0);
    
    $response['success'] = true;
    $response['stats'] = [
        'totalEmployees' => $totalEmployees,
        'permanentEmployees' => $permanentEmployees,
        'traineeEmployees' => $traineeEmployees,
        'attachedEmployees' => $attachedEmployees,
        'avgAge' => $avgAge,
        'avgSeniority' => $avgSeniority,
        'monthlyHires' => $monthlyHires,
        'turnoverRate' => '0%',
        'attendanceRate' => '0%'
    ];
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['stats'] = [
        'totalEmployees' => 161,
        'permanentEmployees' => 0,
        'traineeEmployees' => 0,
        'attachedEmployees' => 0,
        'avgAge' => 0,
        'avgSeniority' => 0,
        'monthlyHires' => 0,
        'turnoverRate' => '0%',
        'attendanceRate' => '0%'
    ];
}

echo json_encode($response);
?>