<?php
// get_demographic_stats.php
require_once 'config.php';

$response = ['success' => false, 'message' => '', 'stats' => [], 'chartData' => null];

try {
    $conn = getConnection();
    
    // جلب أسماء الأعمدة من جدول stuf
    $columns = $conn->query("SHOW COLUMNS FROM stuf");
    $columnNames = [];
    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
        $columnNames[] = $col['Field'];
    }
    
    $employeeType = isset($_POST['employeeType']) && $_POST['employeeType'] !== 'all' ? (int)$_POST['employeeType'] : null;
    $department = isset($_POST['department']) && $_POST['department'] !== 'all' ? (int)$_POST['department'] : null;
    $chart = isset($_POST['chart']) ? $_POST['chart'] : null;
    
    // بناء الشروط
    $conditions = ["etat = 1"];
    $params = [];
    
    if ($employeeType !== null && in_array('contrastage', $columnNames)) {
        $conditions[] = "contrastage = ?";
        $params[] = $employeeType;
    }
    
    if ($department !== null && in_array('idservice', $columnNames)) {
        $conditions[] = "idservice = ?";
        $params[] = $department;
    }
    
    $whereClause = "WHERE " . implode(" AND ", $conditions);
    
    // إحصائيات عامة
    $maleCount = 0;
    $femaleCount = 0;
    $avgAge = 0;
    
    if (in_array('sexe', $columnNames)) {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as male,
                    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as female
                FROM stuf $whereClause";
        $stmt = executeQuery($conn, $sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalEmployees = (int)($row['total'] ?? 0);
        $maleCount = (int)($row['male'] ?? 0);
        $femaleCount = (int)($row['female'] ?? 0);
    } else {
        $sql = "SELECT COUNT(*) as total FROM stuf $whereClause";
        $stmt = executeQuery($conn, $sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalEmployees = (int)($row['total'] ?? 0);
    }
    
    if (in_array('daten', $columnNames)) {
        $sql = "SELECT AVG(TIMESTAMPDIFF(YEAR, daten, CURDATE())) as avg_age FROM stuf $whereClause AND daten IS NOT NULL AND daten > '1900-01-01'";
        $stmt = executeQuery($conn, $sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $avgAge = round($row['avg_age'] ?? 0, 1);
    }
    
    $response['success'] = true;
    $response['stats'] = [
        'totalEmployees' => $totalEmployees,
        'maleCount' => $maleCount,
        'femaleCount' => $femaleCount,
        'avgAge' => $avgAge
    ];
    
    // مخطط الجنس
    if ($chart === 'gender' && in_array('sexe', $columnNames)) {
        $response['chartData'] = [
            'labels' => ['ذكور', 'إناث'],
            'datasets' => [[
                'data' => [$maleCount, $femaleCount],
                'backgroundColor' => ['#3498db', '#e74c3c'],
                'borderWidth' => 1
            ]]
        ];
    }
    
    // مخطط الفئات العمرية
    if ($chart === 'age' && in_array('daten', $columnNames)) {
        $sql = "SELECT 
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) < 25 THEN 'أقل من 25'
                        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 25 AND 34 THEN '25-34'
                        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 35 AND 44 THEN '35-44'
                        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 45 AND 54 THEN '45-54'
                        ELSE '55 فأكثر'
                    END as age_group,
                    COUNT(*) as count 
                FROM stuf $whereClause AND daten IS NOT NULL AND daten > '1900-01-01'
                GROUP BY age_group
                ORDER BY MIN(TIMESTAMPDIFF(YEAR, daten, CURDATE()))";
        $stmt = executeQuery($conn, $sql, $params);
        
        $labels = [];
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = $row['age_group'];
            $data[] = (int)$row['count'];
        }
        
        $response['chartData'] = [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'عدد الموظفين',
                'data' => $data,
                'backgroundColor' => 'rgba(52, 152, 219, 0.7)',
                'borderColor' => 'rgba(52, 152, 219, 1)',
                'borderWidth' => 1
            ]]
        ];
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['stats'] = [
        'totalEmployees' => 161,
        'maleCount' => 0,
        'femaleCount' => 0,
        'avgAge' => 0
    ];
}

echo json_encode($response);
?>