<?php
// get_retirement_stats.php
require_once 'config.php';

$response = ['success' => false, 'message' => '', 'stats' => []];

try {
    $conn = getConnection();
    
    $data = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        if (!empty($input)) {
            $data = json_decode($input, true);
        }
        if (empty($data)) {
            $data = $_POST;
        }
    }
    
    $year = isset($data['year']) ? (int)$data['year'] : null;
    $compareYear = isset($data['compareYear']) && $data['compareYear'] !== '' ? (int)$data['compareYear'] : null;
    $department = isset($data['department']) && $data['department'] !== 'all' ? (int)$data['department'] : null;
    
    // جلب السنوات المتاحة
    if (isset($data['action']) && $data['action'] === 'get_years') {
        $sql = "SELECT DISTINCT YEAR(datedepart) as year FROM depart WHERE datedepart IS NOT NULL ORDER BY year DESC";
        $stmt = executeQuery($conn, $sql);
        
        $years = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['year']) $years[] = (int)$row['year'];
        }
        
        if (empty($years)) {
            $currentYear = date('Y');
            for ($i = 0; $i < 5; $i++) {
                $years[] = $currentYear - $i;
            }
        }
        
        $response['success'] = true;
        $response['stats']['availableYears'] = $years;
        echo json_encode($response);
        exit;
    }
    
    // بناء الشروط
    $conditions = [];
    $params = [];
    
    if ($year) {
        $conditions[] = "YEAR(d.datedepart) = ?";
        $params[] = $year;
    }
    
    if ($department) {
        $conditions[] = "s.idservice = ?";
        $params[] = $department;
    }
    
    $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
    
    // إجمالي المغادرين
    $sql = "SELECT COUNT(*) as total FROM depart d $whereClause";
    $stmt = executeQuery($conn, $sql, $params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRetirements = (int)($row['total'] ?? 0);
    
    // المغادرين حسب السنة
    $sql = "SELECT 
                YEAR(datedepart) as year,
                COUNT(*) as count,
                CASE 
                    WHEN datedepart < CURDATE() THEN 'past'
                    ELSE 'future'
                END as type
            FROM depart
            GROUP BY year, type
            ORDER BY year";
    $stmt = executeQuery($conn, $sql);
    
    $byYear = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $byYear[] = [
            'year' => (int)$row['year'],
            'count' => (int)$row['count'],
            'type' => $row['type']
        ];
    }
    
    // المغادرين حسب الإدارات (بالعربية)
    $sql = "SELECT 
                COALESCE(dep.depar, CONCAT('قسم ', s.idservice)) as department,
                COUNT(*) as count
            FROM depart d
            LEFT JOIN stuf s ON d.mecano = s.mecano
            LEFT JOIN dep ON s.idservice = dep.idservice
            $whereClause
            GROUP BY department
            ORDER BY count DESC";
    $stmt = executeQuery($conn, $sql, $params);
    
    $byDepartment = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $byDepartment[] = [
            'department' => $row['department'],
            'count' => (int)$row['count']
        ];
    }
    
    // قائمة الموظفين المغادرين (بدون عمود prenom لأنه غير موجود)
    $sql = "SELECT 
                d.mecano,
                s.nom,
                d.datedepart,
                d.observation,
                COALESCE(dep.depar, CONCAT('قسم ', s.idservice)) as departement,
                CASE 
                    WHEN d.datedepart < CURDATE() THEN 'past'
                    ELSE 'future'
                END as type,
                TIMESTAMPDIFF(YEAR, s.daten, d.datedepart) as age_at_departure
            FROM depart d
            LEFT JOIN stuf s ON d.mecano = s.mecano
            LEFT JOIN dep ON s.idservice = dep.idservice
            $whereClause
            ORDER BY d.datedepart DESC
            LIMIT 100";
    $stmt = executeQuery($conn, $sql, $params);
    
    $employeesList = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $employeesList[] = [
            'mecano' => $row['mecano'] ?? '',
            'nom' => $row['nom'] ?? '',
            'prenom' => '', // العمود غير موجود
            'daten' => '',
            'datedepart' => $row['datedepart'] ?? '',
            'observation' => $row['observation'] ?? '',
            'departement' => $row['departement'] ?? '',
            'grade' => '',
            'current_age' => $row['age_at_departure'] ?? '',
            'type' => $row['type'] ?? 'future'
        ];
    }
    
    $response['success'] = true;
    $response['stats'] = [
        'totalRetirements' => $totalRetirements,
        'byYear' => $byYear,
        'byDepartment' => $byDepartment,
        'byGrade' => [],
        'employeesList' => $employeesList
    ];
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['stats'] = [
        'totalRetirements' => 0,
        'byYear' => [],
        'byDepartment' => [],
        'byGrade' => [],
        'employeesList' => []
    ];
}

echo json_encode($response);
?>