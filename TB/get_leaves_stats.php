<?php
// get_leaves_stats.php
require_once 'config.php';

$response = ['success' => false, 'message' => '', 'stats' => [], 'chartData' => null];

try {
    $conn = getConnection();
    
    // التحقق من أسماء الأعمدة في جدول conge
    $columns = $conn->query("SHOW COLUMNS FROM conge");
    $columnNames = [];
    while ($col = $columns->fetch(PDO::FETCH_ASSOC)) {
        $columnNames[] = $col['Field'];
    }
    
    // تحديد أسماء الحقول الصحيحة
    $idEmployeField = in_array('id_stuf', $columnNames) ? 'id_stuf' : (in_array('id_employe', $columnNames) ? 'id_employe' : 'id');
    $dateDebutField = in_array('date_debut', $columnNames) ? 'date_debut' : (in_array('dated', $columnNames) ? 'dated' : 'date_debut');
    $dateFinField = in_array('date_fin', $columnNames) ? 'date_fin' : (in_array('datef', $columnNames) ? 'datef' : 'date_fin');
    
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
    $chart = isset($data['chart']) ? $data['chart'] : null;
    
    // جلب السنوات المتاحة
    if (isset($data['action']) && $data['action'] === 'get_years') {
        $sql = "SELECT DISTINCT YEAR($dateDebutField) as year FROM conge WHERE $dateDebutField IS NOT NULL ORDER BY year DESC";
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
    
    if ($year) {
        // بناء الشروط للإدارات
        $deptCondition = "";
        $params = [$year];
        
        if ($department) {
            $deptCondition = " AND s.idservice = ?";
            $params[] = $department;
        }
        
        // إحصائيات عامة
        $sql = "SELECT 
                    COUNT(*) as total_leaves,
                    COUNT(DISTINCT c.$idEmployeField) as total_employees,
                    SUM(DATEDIFF(c.$dateFinField, c.$dateDebutField) + 1) as total_days
                FROM conge c
                LEFT JOIN stuf s ON c.$idEmployeField = s.id
                WHERE YEAR(c.$dateDebutField) = ? $deptCondition";
        $stmt = executeQuery($conn, $sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $totalLeaves = (int)($row['total_leaves'] ?? 0);
        $totalEmployees = (int)($row['total_employees'] ?? 0);
        $totalDays = (int)($row['total_days'] ?? 0);
        
        $stats = [
            'totalStats' => [
                'total_leaves' => $totalLeaves,
                'total_employees' => $totalEmployees,
                'total_days' => $totalDays,
                'avg_days' => $totalLeaves > 0 ? round($totalDays / $totalLeaves, 1) : 0
            ]
        ];
        
        // مخطط حسب الأشهر
        if ($chart === 'by_month') {
            $sql = "SELECT 
                        MONTH(c.$dateDebutField) as month,
                        COUNT(*) as count
                    FROM conge c
                    LEFT JOIN stuf s ON c.$idEmployeField = s.id
                    WHERE YEAR(c.$dateDebutField) = ? $deptCondition
                    GROUP BY month
                    ORDER BY month";
            $stmt = executeQuery($conn, $sql, $params);
            
            $monthsData = array_fill(0, 12, 0);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $monthIndex = (int)$row['month'] - 1;
                if ($monthIndex >= 0 && $monthIndex < 12) {
                    $monthsData[$monthIndex] = (int)$row['count'];
                }
            }
            
            $months = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            
            $response['chartData'] = [
                'labels' => $months,
                'datasets' => [[
                    'label' => 'عدد الإجازات',
                    'data' => $monthsData,
                    'backgroundColor' => 'rgba(52, 152, 219, 0.7)',
                    'borderColor' => 'rgba(52, 152, 219, 1)',
                    'borderWidth' => 1
                ]]
            ];
        }
        
        // مخطط أيام الإجازة حسب الأشهر
        if ($chart === 'days_by_month') {
            $sql = "SELECT 
                        MONTH(c.$dateDebutField) as month,
                        SUM(DATEDIFF(c.$dateFinField, c.$dateDebutField) + 1) as total_days
                    FROM conge c
                    LEFT JOIN stuf s ON c.$idEmployeField = s.id
                    WHERE YEAR(c.$dateDebutField) = ? $deptCondition
                    GROUP BY month
                    ORDER BY month";
            $stmt = executeQuery($conn, $sql, $params);
            
            $monthsData = array_fill(0, 12, 0);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $monthIndex = (int)$row['month'] - 1;
                if ($monthIndex >= 0 && $monthIndex < 12) {
                    $monthsData[$monthIndex] = (int)$row['total_days'];
                }
            }
            
            $months = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
            
            $response['chartData'] = [
                'labels' => $months,
                'datasets' => [[
                    'label' => 'أيام الإجازة',
                    'data' => $monthsData,
                    'backgroundColor' => 'rgba(46, 204, 113, 0.7)',
                    'borderColor' => 'rgba(46, 204, 113, 1)',
                    'borderWidth' => 1,
                    'fill' => true,
                    'tension' => 0.3
                ]]
            ];
        }
        
        // مخطط حسب الإدارات (بالعربية)
        if ($chart === 'by_department') {
            $sql = "SELECT 
                        COALESCE(dep.depar, CONCAT('قسم ', s.idservice)) as department,
                        COUNT(*) as count
                    FROM conge c
                    LEFT JOIN stuf s ON c.$idEmployeField = s.id
                    LEFT JOIN dep ON s.idservice = dep.idservice
                    WHERE YEAR(c.$dateDebutField) = ? $deptCondition
                    GROUP BY department
                    ORDER BY count DESC";
            $stmt = executeQuery($conn, $sql, $params);
            
            $labels = [];
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels[] = $row['department'];
                $data[] = (int)$row['count'];
            }
            
            $colors = ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#1abc9c', '#9b59b6', '#e67e22', '#95a5a6'];
            
            $response['chartData'] = [
                'labels' => $labels,
                'datasets' => [[
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderWidth' => 1
                ]]
            ];
        }
        
        // مخطط أيام الإجازة حسب الإدارات
        if ($chart === 'days_by_department') {
            $sql = "SELECT 
                        COALESCE(dep.depar, CONCAT('قسم ', s.idservice)) as department,
                        SUM(DATEDIFF(c.$dateFinField, c.$dateDebutField) + 1) as total_days
                    FROM conge c
                    LEFT JOIN stuf s ON c.$idEmployeField = s.id
                    LEFT JOIN dep ON s.idservice = dep.idservice
                    WHERE YEAR(c.$dateDebutField) = ? $deptCondition
                    GROUP BY department
                    ORDER BY total_days DESC";
            $stmt = executeQuery($conn, $sql, $params);
            
            $labels = [];
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $labels[] = $row['department'];
                $data[] = (int)($row['total_days'] ?? 0);
            }
            
            $colors = ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#1abc9c', '#9b59b6', '#e67e22', '#95a5a6'];
            
            $response['chartData'] = [
                'labels' => $labels,
                'datasets' => [[
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderWidth' => 1
                ]]
            ];
        }
        
        // جدول الإجازات الشهري
        if (isset($data['action']) && $data['action'] === 'monthly_table') {
            $sql = "SELECT 
                        MONTH(c.$dateDebutField) as month,
                        COUNT(*) as count,
                        SUM(DATEDIFF(c.$dateFinField, c.$dateDebutField) + 1) as total_days,
                        AVG(DATEDIFF(c.$dateFinField, c.$dateDebutField) + 1) as avg_days
                    FROM conge c
                    LEFT JOIN stuf s ON c.$idEmployeField = s.id
                    WHERE YEAR(c.$dateDebutField) = ? $deptCondition
                    GROUP BY month
                    ORDER BY month";
            $stmt = executeQuery($conn, $sql, $params);
            
            $monthlyData = array_fill(0, 12, ['count' => 0, 'total_days' => 0, 'avg_days' => 0]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $monthIndex = (int)$row['month'] - 1;
                if ($monthIndex >= 0 && $monthIndex < 12) {
                    $monthlyData[$monthIndex] = [
                        'count' => (int)$row['count'],
                        'total_days' => (int)($row['total_days'] ?? 0),
                        'avg_days' => round($row['avg_days'] ?? 0, 1)
                    ];
                }
            }
            
            $response['success'] = true;
            $response['monthlyData'] = $monthlyData;
            echo json_encode($response);
            exit;
        }
        
        $response['success'] = true;
        $response['stats'] = $stats;
    } else {
        $response['success'] = true;
        $response['stats'] = ['totalStats' => ['total_leaves' => 0, 'total_employees' => 0, 'total_days' => 0, 'avg_days' => 0]];
    }
    
    // إذا كان هناك chartData، أضفه إلى الرد
    if (isset($response['chartData'])) {
        // تم already set
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['stats'] = ['totalStats' => ['total_leaves' => 0, 'total_employees' => 0, 'total_days' => 0, 'avg_days' => 0]];
}

echo json_encode($response);
?>