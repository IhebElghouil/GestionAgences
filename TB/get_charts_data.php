<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'departmentData' => [], 'statusData' => []];

try {
    if (!isset($_SESSION['congidGA'])) {
        $response['message'] = 'غير مصرح بالوصول';
        echo json_encode($response);
        exit;
    }

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
    
    // بيانات توزيع الموظفين حسب الإدارات
    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $types = str_repeat('i', count($departements));
        
        $query = "
            SELECT dep.depar, COUNT(*) as count
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            WHERE stuf.dep IN ($placeholders) AND contrastage IN (0,1,3)
            GROUP BY dep.depar
            ORDER BY count DESC
            LIMIT 10
        ";
        
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    } else {
        $query = "
            SELECT dep.depar, COUNT(*) as count
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            WHERE contrastage IN (0,1,3)
            GROUP BY dep.depar
            ORDER BY count DESC
            LIMIT 10
        ";
        $stmt = mysqli_prepare($connection, $query);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $depar, $count);

    $departmentLabels = [];
    $departmentCounts = [];
    $departmentColors = [
        'rgba(52, 152, 219, 0.7)', 'rgba(39, 174, 96, 0.7)', 'rgba(243, 156, 18, 0.7)',
        'rgba(155, 89, 182, 0.7)', 'rgba(231, 76, 60, 0.7)', 'rgba(26, 188, 156, 0.7)',
        'rgba(241, 196, 15, 0.7)', 'rgba(230, 126, 34, 0.7)', 'rgba(149, 165, 166, 0.7)',
        'rgba(52, 73, 94, 0.7)'
    ];

    while (mysqli_stmt_fetch($stmt)) {
        $departmentLabels[] = $depar;
        $departmentCounts[] = $count;
    }

    mysqli_stmt_close($stmt);

    $response['departmentData'] = [
        'labels' => $departmentLabels,
        'datasets' => [{
            'label' => 'عدد الموظفين',
            'data' => $departmentCounts,
            'backgroundColor' => array_slice($departmentColors, 0, count($departmentLabels)),
            'borderColor' => array_slice([
                'rgba(52, 152, 219, 1)', 'rgba(39, 174, 96, 1)', 'rgba(243, 156, 18, 1)',
                'rgba(155, 89, 182, 1)', 'rgba(231, 76, 60, 1)', 'rgba(26, 188, 156, 1)',
                'rgba(241, 196, 15, 1)', 'rgba(230, 126, 34, 1)', 'rgba(149, 165, 166, 1)',
                'rgba(52, 73, 94, 1)'
            ], 0, count($departmentLabels)),
            'borderWidth' => 1
        }]
    ];

    // بيانات توزيع الموظفين حسب الوضعية
    if ($_SESSION['departement'] !== "admin") {
        $query = "
            SELECT 
                SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) as permanent,
                SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) as trainee,
                SUM(CASE WHEN contrastage = 3 THEN 1 ELSE 0 END) as attached
            FROM stuf
            WHERE dep IN ($placeholders) AND contrastage IN (0,1,3)
        ";
        
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    } else {
        $query = "
            SELECT 
                SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) as permanent,
                SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) as trainee,
                SUM(CASE WHEN contrastage = 3 THEN 1 ELSE 0 END) as attached
            FROM stuf
            WHERE contrastage IN (0,1,3)
        ";
        $stmt = mysqli_prepare($connection, $query);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $permanent, $trainee, $attached);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $response['statusData'] = [
        'labels' => ['دائم', 'متدرب', 'ملحق'],
        'datasets' => [{
            'data' => [$permanent, $trainee, $attached],
            'backgroundColor' => [
                'rgba(39, 174, 96, 0.7)',
                'rgba(243, 156, 18, 0.7)',
                'rgba(52, 152, 219, 0.7)'
            ],
            'borderColor' => [
                'rgba(39, 174, 96, 1)',
                'rgba(243, 156, 18, 1)',
                'rgba(52, 152, 219, 1)'
            ],
            'borderWidth' => 1
        }]
    ];

    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

echo json_encode($response);
?>