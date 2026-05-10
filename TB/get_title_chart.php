<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'chartData' => []];

try {
    if (!isset($_SESSION['congidGA'])) {
        $response['message'] = 'غير مصرح بالوصول';
        echo json_encode($response);
        exit;
    }

    $timePeriod = $_POST['timePeriod'] ?? 'month';
    $employeeType = $_POST['employeeType'] ?? 'all';
    $department = $_POST['department'] ?? 'all';

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
    
    // بناء الاستعلام
    $query = "
        SELECT t.libellet, COUNT(*) as count
        FROM stuf s
        LEFT JOIN titres t ON s.titre = t.id
        WHERE s.contrastage IN (0,1,3)
    ";
    
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND s.dep IN ($placeholders)";
    }
    
    if ($employeeType !== 'all') {
        $query .= " AND s.contrastage = " . intval($employeeType);
    }
    
    if ($department !== 'all') {
        $query .= " AND s.dep = " . intval($department);
    }
    
    $query .= " GROUP BY t.libellet ORDER BY count DESC LIMIT 15";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if ($_SESSION['departement'] !== "admin") {
        $types = str_repeat('i', count($departements));
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $count);
    
    $labels = [];
    $data = [];
    
    while (mysqli_stmt_fetch($stmt)) {
        $labels[] = $title ?: 'غير محدد';
        $data[] = $count;
    }
    
    mysqli_stmt_close($stmt);
    
    // إذا لم توجد بيانات، نضيف بيانات افتراضية
    if (empty($labels)) {
        $labels = ['لا توجد بيانات'];
        $data = [0];
    }
    
    $response['success'] = true;
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

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

echo json_encode($response);
?>