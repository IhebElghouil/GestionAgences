<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

// دالة مبسطة ومضمونة لحساب الأيام المتبقية
function calculateRemainingDays($startDate, $endDate, $jrepos) {
    // التحقق من صحة التواريخ
    if (empty($startDate) || empty($endDate) || $startDate == '0000-00-00' || $endDate == '0000-00-00') {
        return null;
    }
    
    try {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        
        // إذا كان تاريخ البدء بعد تاريخ الانتهاء
        if ($start > $end) {
            return 0;
        }
        
        // حساب الفرق بالأيام
        $interval = $start->diff($end);
        $totalDays = $interval->days;
        
        // إذا كان الفرق صفر أو أقل
        if ($totalDays <= 0) {
            return 0;
        }
        
        // تحديد أيام الراحة
        $weekendDays = [];
        if ($jrepos == 10) {
            $weekendDays = [0, 6]; // السبت والأحد
        } elseif (is_numeric($jrepos) && $jrepos >= 0 && $jrepos <= 6) {
            $weekendDays = [(int)$jrepos];
        } else {
            $weekendDays = [0, 6]; // الافتراضي: السبت والأحد
        }
        
        // حساب أيام العمل
        $workDays = 0;
        $current = clone $start;
        
        for ($i = 0; $i <= $totalDays; $i++) {
            $dayOfWeek = (int)$current->format('w');
            if (!in_array($dayOfWeek, $weekendDays)) {
                $workDays++;
            }
            $current->modify('+1 day');
        }
        
        return $workDays;
        
    } catch (Exception $e) {
        error_log("Error in calculateRemainingDays: " . $e->getMessage());
        return null;
    }
}

$isAdmin = ($_SESSION['departement'] === "admin");
$departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];

// جلب السنة الحالية
$annee_result = mysqli_query($connection, "SELECT annee FROM annee");
if (!$annee_result) {
    echo json_encode(['success' => false, 'message' => 'خطأ في جلب السنة: ' . mysqli_error($connection)]);
    exit;
}
$annee_row = mysqli_fetch_row($annee_result);
$annee = (int)$annee_row[0];
$annee_min = $isAdmin ? $annee - 1 : $annee;
$annee_max = $annee + 1;
$annee_naissance = $annee_max - 60;

// تصحيح أسماء الأعمدة في query1
			   
$query1 = "SELECT 
                depart.mecano, 
                stuf.nom, 
                stuf.daten, 
                depart.datedepart, 
                depart.dateretraite, 
                depart.annee, 
                dep.depar, 
                depart.cministere,  
                COALESCE(nbconge.rest, 0) as rest, 
                depart.observation,
                COALESCE(stuf.jrepos, 10) as jrepos
           FROM depart 
           LEFT JOIN stuf ON stuf.mecano = depart.mecano 
           LEFT JOIN dep ON stuf.dep = dep.id 
           LEFT JOIN nbconge ON stuf.mecano = nbconge.mecano 
           WHERE depart.annee BETWEEN $annee_min AND $annee_max";

if (!$isAdmin) {
    $query1 .= " AND stuf.dep IN (" . implode(',', $departements) . ")";
} 
// تصحيح أسماء الأعمدة في query2
$query2 = "SELECT 
                stuf.mecano, 
                stuf.nom, 
                stuf.daten,
                DATE_ADD(
                    DATE_ADD(stuf.daten, INTERVAL 60 YEAR),
                    INTERVAL (DAY(LAST_DAY(DATE_ADD(stuf.daten, INTERVAL 60 YEAR))) - DAY(DATE_ADD(stuf.daten, INTERVAL 60 YEAR))) DAY
                ) AS datedepart,
                DATE_ADD(stuf.daten, INTERVAL 60 YEAR) AS dateretraite,
                $annee_max as annee, 
                dep.depar, 
                0 as cministere,
                COALESCE(nbconge.rest, 0) as rest, 
                'تقاعد متوقّع' as observation,
                COALESCE(stuf.jrepos, 10) as jrepos
           FROM stuf
           LEFT JOIN dep ON stuf.dep = dep.id 
           LEFT JOIN nbconge ON stuf.mecano = nbconge.mecano
           WHERE YEAR(stuf.daten) = $annee_naissance
           AND stuf.contrastage != 4
           AND stuf.mecano NOT IN (SELECT mecano FROM depart WHERE mecano IS NOT NULL)";
if (!$isAdmin) {
    $query2 .= " AND stuf.dep IN (" . implode(',', $departements) . ")";
} 
// تنفيذ الاستعلام
$query = "$query1 UNION $query2 ORDER BY datedepart DESC";
$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'خطأ في الاستعلام: ' . mysqli_error($connection)]);
    exit;
}

$data = [];
$today = date('Y-m-d');

while ($row = mysqli_fetch_assoc($result)) {
    $departDate = $row['datedepart'];
    $jrepos = $row['jrepos'];
    
    // حساب الأيام المتبقية
    $remainingDays = calculateRemainingDays($today, $departDate, $jrepos);
    
    // دمج عدد الأيام مع حقل observation
    $observation_original = $row['observation'];
    
    // إضافة عدد الأيام بجانب observation
    if ($remainingDays !== null && $remainingDays > 0) {
        $row['observation'] = $observation_original . ' ≈ ' . $remainingDays . ' يوم عمل متبقي ';
    } elseif ($remainingDays === 0) {
        $row['observation'] = $observation_original;
    } elseif ($remainingDays === null) {
        $row['observation'] = $observation_original . ' - تاريخ غير محدد';
    } else {
        $row['observation'] = $observation_original;
    }
    
    // الاحتفاظ بعدد الأيام في حقل منفصل إذا احتجت إليه لاحقاً
    $row['jours_restants'] = $remainingDays;
    $row['date_aujourdhui'] = $today;
    
    $data[] = $row;
}

// إرسال النتيجة
echo json_encode([
    'success' => true, 
    'data' => $data,
    'total_count' => count($data),
    'today' => $today
], JSON_UNESCAPED_UNICODE);
?>