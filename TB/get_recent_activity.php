<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'activities' => []];

try {
    if (!isset($_SESSION['congidGA'])) {
        $response['message'] = 'غير مصرح بالوصول';
        echo json_encode($response);
        exit;
    }

    // في التطبيق الحقيقي، يمكنك إنشاء جدول للأنشطة في قاعدة البيانات
    // هنا نستخدم استعلامات للحصول على أحدث التغييرات
    
    $activities = [];

    // الحصول على آخر الموظفين المضافين
    $query = "
        SELECT nom, daterec 
        FROM stuf 
        WHERE contrastage IN (0,1,3) 
        ORDER BY daterec DESC 
        LIMIT 3
    ";
    
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $activities[] = [
            'icon' => 'user-plus',
            'title' => 'تم إضافة موظف جديد',
            'description' => 'تمت إضافة ' . $row['nom'],
            'time' => 'منذ ' . time_elapsed_string($row['daterec']),
            'type' => 'success'
        ];
    }

    // إضافة أنشطة افتراضية إذا لم تكن هناك بيانات كافية
    if (count($activities) < 5) {
        $defaultActivities = [
            [
                'icon' => 'file-export',
                'title' => 'تم تصدير تقرير الموظفين',
                'description' => 'تم تصدير تقرير شهري لجميع الموظفين',
                'time' => 'منذ يوم',
                'type' => 'primary'
            ],
            [
                'icon' => 'calendar-check',
                'title' => 'تمت الموافقة على إجازة',
                'description' => 'تمت الموافقة على إجازة أحد الموظفين',
                'time' => 'منذ 3 أيام',
                'type' => 'info'
            ]
        ];
        
        $activities = array_merge($activities, $defaultActivities);
    }

    $response['activities'] = array_slice($activities, 0, 5);
    $response['success'] = true;

} catch (Exception $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
}

// دالة مساعدة لعرض الوقت المنقضي
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'سنة',
        'm' => 'شهر',
        'w' => 'أسبوع',
        'd' => 'يوم',
        'h' => 'ساعة',
        'i' => 'دقيقة',
        's' => 'ثانية',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? 'منذ ' . implode(', ', $string) : 'الآن';
}

echo json_encode($response);
?>