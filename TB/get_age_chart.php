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
                WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 18 AND 25 THEN '18-25'
                WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 26 AND 35 THEN '26-35'
                WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 36 AND 45 THEN '36-45'
                WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 46 AND 55 THEN '46-55'
                ELSE '56+'
            END as age_group,
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
    
    $query .= " GROUP BY age_group ORDER BY age_group";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if ($_SESSION['departement'] !== "admin") {
        $types = str_repeat('i', count($departements));
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $ageGroup, $count);
    
    $labels = ['18-25 سنة', '26-35 سنة', '36-45 سنة', '46-55 سنة', '56+ سنة'];
    $data = [0, 0, 0, 0, 0];
    
    while (mysqli_stmt_fetch($stmt)) {
        switch ($ageGroup) {
            case '18-25': $data[0] = $count; break;
            case '26-35': $data[1] = $count; break;
            case '36-45': $data[2] = $count; break;
            case '46-55': $data[3] = $count; break;
            case '56+': $data[4] = $count; break;
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
                'rgba(231, 76, 60, 0.7)'
            ],
            'borderColor' => [
                'rgba(52, 152, 219, 1)',
                'rgba(39, 174, 96, 1)',
                'rgba(243, 156, 18, 1)',
                'rgba(155, 89, 182, 1)',
                'rgba(231, 76, 60, 1)'
            ],
            'borderWidth' => 1
        ]]
    ];

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

echo json_encode($response);
?>