<?php
session_start();
require('connection.php');

// التحقق من الصلاحيات
if (!isset($_SESSION['congidGA'])) {
    header("HTTP/1.1 403 Forbidden");
    exit("ليس لديك صلاحية للوصول إلى هذه البيانات");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mecano = $_POST['mecano'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $sanction = $_POST['sanction'] ?? '';
    $status = $_POST['status'] ?? '';
    
    $departement = $_SESSION['departement'];
    
    // بناء استعلام البحث
    $sql = "SELECT 
            idsanction, mecano, nom, grade, datefaute, datequestionnaire, 
            faute, sanction, datesanction, Datestamp, 
            DATEDIFF(datequestionnaire, CURRENT_DATE()) as datediff,
            report_number, report_date,
            questionnaire_file, report_file, sanction_decision_file, datereception
            FROM sanctions WHERE 1=1";
    
    $params = [];
    $types = "";
    
    if (!empty($mecano)) {
        $sql .= " AND mecano LIKE ?";
        $params[] = "%$mecano%";
        $types .= "s";
    }
    
    if (!empty($nom)) {
        $sql .= " AND nom LIKE ?";
        $params[] = "%$nom%";
        $types .= "s";
    }
    
    if (!empty($sanction)) {
        $sql .= " AND sanction LIKE ?";
        $params[] = "%$sanction%";
        $types .= "s";
    }
    
    if ($_SESSION['departement'] !== "admin") {
        $sql .= " AND mecano IN (SELECT mecano FROM stuf WHERE dep = ?)";
        $params[] = $departement;
        $types .= "i";
    }
    
    // تطبيق فلتر الحالة
    if (!empty($status)) {
        switch($status) {
            case 'expired':
                $sql .= " AND DATEDIFF(datequestionnaire, CURRENT_DATE()) < 0";
                break;
            case 'urgent':
                $sql .= " AND DATEDIFF(datequestionnaire, CURRENT_DATE()) BETWEEN 1 AND 10";
                break;
            case 'today':
                $sql .= " AND DATEDIFF(datequestionnaire, CURRENT_DATE()) = 0";
                break;
        }
    }
    
    $sql .= " ORDER BY mecano DESC";
    
    $stmt = mysqli_prepare($connection, $sql);
    
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, 
        $idsanction, $mecano, $nom, $grade, $datefaute, 
        $datequestionnaire, $faute, $sanction, $datesanction, 
        $Datestamp, $datediff, $report_number, $report_date,
        $questionnaire_file, $report_file, $sanction_decision_file, $datereception
    );
    
    while (mysqli_stmt_fetch($stmt)) {
        // Determine status class
        $statusClass = '';
        if ($datediff < 0) {
            $statusClass = 'status-expired';
        } else if ($datediff >= 1 && $datediff <= 10) {
            $statusClass = 'status-urgent';
        } else if ($datediff == 0) {
            $statusClass = 'status-today';
        }
        
        echo '<tr class="' . $statusClass . ' fade-in">';
        // ... نفس كود عرض الصفوف كما في الملف الرئيسي
        echo '</tr>';
    }
    
    mysqli_stmt_close($stmt);
}
?>