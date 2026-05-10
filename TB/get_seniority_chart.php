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
        SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 0 AND 2 THEN '0-2'
                WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 3 AND 5 THEN '3-5'
                WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 6 AND 10 THEN '6-10'
                WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 11 AND 15 THEN '11-15'
                WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 16 AND 20 THEN '16-20'
                ELSE '20+'
            END as seniority_group,
            COUNT(*) as count
        FROM stuf
        WHERE contrastage IN (0,1,3)
    ";
    
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $query .= " AND dep IN ($placeholders)";
    }
    
    if ($employeeType !== 'all') {
        $query .= " AND contrastage = " . intval($employeeType);
    }
    
    if ($department !== 'all') {
        $query .= " AND dep = " . intval($department);
    }
    
    $query .= " GROUP BY seniority_group ORDER BY seniority_group";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if ($_SESSION['departement'] !== "admin") {
        $types = str_repeat('i', count($departements));
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $seniorityGroup, $count);
    
    $labels = ['0-2 سنوات', '3-5 سنوات', '6-10 سنوات', '11-15 سنوات', '16-20 سنوات', '20+ سنوات'];
    $data = [0, 0, 0, 0, 0, 0];
    
    while (mysqli_stmt_fetch($stmt)) {
        switch ($seniorityGroup) {
            case '0-2': $data[0] = $count; break;
            case '3-5': $data[1] = $count; break;
            case '6-10': $data[2] = $count; break;
            case '11-15': $data[3] = $count; break;
            case '16-20': $data[4] = $count; break;
            case '20+': $data[5] = $count; break;
        }
    }
    
    mysqli_stmt_close($stmt);
    
    $response['success'] = true;
    $response['chartData'] = [
        'labels' => $labels,
        'datasets' => [[
            'label' => 'عدد الموظفين',
            'data' => $data,
            'backgroundColor' => [
                'rgba(52, 152, 219, 0.7)',
                'rgba(39, 174, 96, 0.7)',
                'rgba(243, 156, 18, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(231, 76, 60, 0.7)',
                'rgba(26, 188, 156, 0.7)'
            ],
            'borderColor' => [
                'rgba(52, 152, 219, 1)',
                'rgba(39, 174, 96, 1)',
                'rgba(243, 156, 18, 1)',
                'rgba(155, 89, 182, 1)',
                'rgba(231, 76, 60, 1)',
                'rgba(26, 188, 156, 1)'
            ],
            'borderWidth' => 1
        ]]
    ];

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

echo json_encode($response);
?>