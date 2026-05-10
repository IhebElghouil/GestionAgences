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

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
    
    // Initialize counters
    $totalEmployees = 0;
    $permanentEmployees = 0;
    $traineeEmployees = 0;
    $attachedEmployees = 0;

    if ($_SESSION['departement'] !== "admin") {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $types = str_repeat('i', count($departements));
        
        $query = "
            SELECT contrastage, COUNT(*) as count
            FROM stuf
            WHERE dep IN ($placeholders) AND contrastage IN (0,1,3)
            GROUP BY contrastage
        ";
        
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    } else {
        $query = "
            SELECT contrastage, COUNT(*) as count
            FROM stuf
            WHERE contrastage IN (0,1,3)
            GROUP BY contrastage
        ";
        $stmt = mysqli_prepare($connection, $query);
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $contrastage, $count);

    while (mysqli_stmt_fetch($stmt)) {
        $totalEmployees += $count;
        switch ($contrastage) {
            case 0: $permanentEmployees = $count; break;
            case 1: $traineeEmployees = $count; break;
            case 3: $attachedEmployees = $count; break;
        }
    }

    mysqli_stmt_close($stmt);

    $response['success'] = true;
    $response['stats'] = [
        'totalEmployees' => $totalEmployees,
        'permanentEmployees' => $permanentEmployees,
        'traineeEmployees' => $traineeEmployees,
        'attachedEmployees' => $attachedEmployees
    ];

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

echo json_encode($response);
?>