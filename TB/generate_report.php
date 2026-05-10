<?php
session_start();
require('connection.php');

header('Content-Type: text/html; charset=utf-8');

try {
    if (!isset($_SESSION['congidGA'])) {
        echo '<div class="alert alert-danger">غير مصرح بالوصول</div>';
        exit;
    }

    $reportType = $_POST['reportType'] ?? 'employees';
    $timePeriod = $_POST['timePeriod'] ?? 'month';
    $employeeType = $_POST['employeeType'] ?? 'all';
    $department = $_POST['department'] ?? 'all';

    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
    
    switch ($reportType) {
        case 'employees':
            generateEmployeesReport($connection, $departements, $employeeType, $department);
            break;
        case 'hiring':
            generateHiringReport($connection, $departements, $employeeType, $department, $timePeriod);
            break;
        case 'turnover':
            generateTurnoverReport($connection, $departements, $employeeType, $department, $timePeriod);
            break;
        case 'attendance':
            generateAttendanceReport($connection, $departements, $employeeType, $department);
            break;
        default:
            echo '<div class="alert alert-warning">نوع التقرير غير معروف</div>';
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger">خطأ في توليد التقرير: ' . $e->getMessage() . '</div>';
}

// دالة لتوليد تقرير الموظفين
function generateEmployeesReport($connection, $departements, $employeeType, $department) {
    $query = "
        SELECT s.mecano, s.nom, s.daten, s.daterec, s.cin, t.libellet, d.depar,
               TIMESTAMPDIFF(YEAR, s.daten, CURDATE()) as age,
               TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) as seniority,
               CASE s.contrastage 
                   WHEN 0 THEN 'دائم' 
                   WHEN 1 THEN 'متدرب' 
                   WHEN 3 THEN 'ملحق' 
                   ELSE 'غير محدد' 
               END as status
        FROM stuf s
        LEFT JOIN titres t ON s.titre = t.id
        LEFT JOIN dep d ON s.dep = d.id
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
    
    $query .= " ORDER BY s.nom";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if ($_SESSION['departement'] !== "admin") {
        $types = str_repeat('i', count($departements));
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $mecano, $nom, $daten, $daterec, $cin, $title, $depar, $age, $seniority, $status);
    
    echo '<h3>تقرير الموظفين</h3>';
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr>
            <th>الرقم الآلي</th>
            <th>الاسم</th>
            <th>الرتبة</th>
            <th>الإدارة</th>
            <th>العمر</th>
            <th>الأقدمية</th>
            <th>الوضعية</th>
        </tr></thead>';
    echo '<tbody>';
    
    while (mysqli_stmt_fetch($stmt)) {
        echo "<tr>
                <td>{$mecano}</td>
                <td>{$nom}</td>
                <td>{$title}</td>
                <td>{$depar}</td>
                <td>{$age} سنة</td>
                <td>{$seniority} سنة</td>
                <td>{$status}</td>
            </tr>";
    }
    
    echo '</tbody></table>';
    mysqli_stmt_close($stmt);
}

// دالة لتوليد تقرير التعيينات
function generateHiringReport($connection, $departements, $employeeType, $department, $timePeriod) {
    // تحديد نطاق التاريخ
    $dateRange = getDateRange($timePeriod);
    
    $query = "
        SELECT s.nom, s.daterec, t.libellet, d.depar,
               CASE s.contrastage 
                   WHEN 0 THEN 'دائم' 
                   WHEN 1 THEN 'متدرب' 
                   WHEN 3 THEN 'ملحق' 
                   ELSE 'غير محدد' 
               END as status
        FROM stuf s
        LEFT JOIN titres t ON s.titre = t.id
        LEFT JOIN dep d ON s.dep = d.id
        WHERE s.daterec BETWEEN ? AND ?
        AND s.contrastage IN (0,1,3)
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
    
    $query .= " ORDER BY s.daterec DESC";
    
    $stmt = mysqli_prepare($connection, $query);
    
    if ($_SESSION['departement'] !== "admin") {
        $types = 'ss' . str_repeat('i', count($departements));
        $params = array_merge([$dateRange['start'], $dateRange['end']], $departements);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, 'ss', $dateRange['start'], $dateRange['end']);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom, $daterec, $title, $depar, $status);
    
    echo '<h3>تقرير التعيينات - الفترة: ' . $dateRange['start'] . ' إلى ' . $dateRange['end'] . '</h3>';
    echo '<table class="table table-bordered table-striped">';
    echo '<thead><tr>
            <th>الاسم</th>
            <th>تاريخ التعيين</th>
            <th>الرتبة</th>
            <th>الإدارة</th>
            <th>الوضعية</th>
        </tr></thead>';
    echo '<tbody>';
    
    $count = 0;
    while (mysqli_stmt_fetch($stmt)) {
        $count++;
        echo "<tr>
                <td>{$nom}</td>
                <td>{$daterec}</td>
                <td>{$title}</td>
                <td>{$depar}</td>
                <td>{$status}</td>
            </tr>";
    }
    
    echo '</tbody></table>';
    echo '<div class="alert alert-info">إجمالي التعيينات: ' . $count . '</div>';
    mysqli_stmt_close($stmt);
}

// دالة مساعدة للحصول على نطاق التاريخ
function getDateRange($period) {
    $today = new DateTime();
    
    switch ($period) {
        case 'last_month':
            $start = $today->modify('first day of last month')->format('Y-m-d');
            $end = $today->modify('last day of last month')->format('Y-m-d');
            break;
        case 'quarter':
            $quarter = ceil($today->format('n') / 3);
            $start = $today->modify('first day of January')->add(new DateInterval('P'.(($quarter-1)*3).'M'))->format('Y-m-d');
            $end = $today->modify('last day of March')->add(new DateInterval('P'.(($quarter-1)*3).'M'))->format('Y-m-d');
            break;
        case 'year':
            $start = $today->modify('first day of January')->format('Y-m-d');
            $end = $today->modify('last day of December')->format('Y-m-d');
            break;
        case 'month':
        default:
            $start = $today->modify('first day of this month')->format('Y-m-d');
            $end = $today->modify('last day of this month')->format('Y-m-d');
            break;
    }
    
    return ['start' => $start, 'end' => $end];
}

// دوال أخرى لتقارير الدوران الوظيفي والحضور يمكن إضافتها لاحقًا
function generateTurnoverReport($connection, $departements, $employeeType, $department, $timePeriod) {
    echo '<h3>تقرير الدوران الوظيفي</h3>';
    echo '<div class="alert alert-info">هذا التقرير قيد التطوير وسيتم تفعيله قريباً</div>';
}

function generateAttendanceReport($connection, $departements, $employeeType, $department) {
    echo '<h3>تقرير الحضور والانصراف</h3>';
    echo '<div class="alert alert-info">هذا التقرير قيد التطوير وسيتم تفعيله قريباً</div>';
}
?>