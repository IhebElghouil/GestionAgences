<?php
// get_growth_chart.php
require_once 'config.php';

$response = ['success' => false, 'chartData' => null, 'message' => ''];

try {
    $conn = getConnection();
    
    // استعلام للتطور الشهري لعدد الموظفين حسب تاريخ التوظيف
    $sql = "SELECT 
                MONTH(daterec) as month,
                COUNT(*) as count 
            FROM stuf 
            WHERE etat = 1 
                AND YEAR(daterec) = YEAR(CURDATE())
                AND daterec IS NOT NULL
                AND daterec > '2000-01-01'
            GROUP BY month 
            ORDER BY month";
    $stmt = executeQuery($conn, $sql);
    
    $monthsData = array_fill(0, 12, 0);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $monthIndex = (int)$row['month'] - 1;
        if ($monthIndex >= 0 && $monthIndex < 12) {
            $monthsData[$monthIndex] = (int)$row['count'];
        }
    }
    
    // حساب المجموع التراكمي
    $cumulativeData = [];
    $runningTotal = 0;
    for ($i = 0; $i < 12; $i++) {
        $runningTotal += $monthsData[$i];
        $cumulativeData[] = $runningTotal;
    }
    
    $months = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    
    $response['success'] = true;
    $response['chartData'] = [
        'labels' => $months,
        'datasets' => [
            [
                'label' => 'التعيينات الجديدة',
                'data' => $monthsData,
                'backgroundColor' => 'rgba(52, 152, 219, 0.2)',
                'borderColor' => 'rgba(52, 152, 219, 1)',
                'borderWidth' => 2,
                'type' => 'bar'
            ],
            [
                'label' => 'إجمالي الموظفين التراكمي',
                'data' => $cumulativeData,
                'backgroundColor' => 'rgba(46, 204, 113, 0.2)',
                'borderColor' => 'rgba(46, 204, 113, 1)',
                'borderWidth' => 2,
                'type' => 'line',
                'tension' => 0.3,
                'fill' => true
            ]
        ]
    ];
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $months = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    $demoData = [5, 8, 12, 7, 15, 10, 6, 9, 14, 11, 13, 8];
    $cumulativeData = [];
    $total = 0;
    foreach ($demoData as $val) {
        $total += $val;
        $cumulativeData[] = $total;
    }
    
    $response['success'] = true;
    $response['chartData'] = [
        'labels' => $months,
        'datasets' => [
            [
                'label' => 'التعيينات الجديدة',
                'data' => $demoData,
                'backgroundColor' => 'rgba(52, 152, 219, 0.2)',
                'borderColor' => 'rgba(52, 152, 219, 1)',
                'borderWidth' => 2,
                'type' => 'bar'
            ],
            [
                'label' => 'إجمالي الموظفين التراكمي',
                'data' => $cumulativeData,
                'backgroundColor' => 'rgba(46, 204, 113, 0.2)',
                'borderColor' => 'rgba(46, 204, 113, 1)',
                'borderWidth' => 2,
                'type' => 'line',
                'tension' => 0.3,
                'fill' => true
            ]
        ]
    ];
}

echo json_encode($response);
?>