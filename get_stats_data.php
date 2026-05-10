<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

try {
    // التحقق من وجود الجلسة والمستخدم
    if (!isset($_SESSION['departement'])) {
        throw new Exception('لم يتم تعريف صلاحيات المستخدم');
    }

    // تحديد صلاحيات المستخدم
    $departements = [];
    if ($_SESSION['departement'] !== "admin") {
        $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
    }

    // جلب بيانات الأعوان
    if ($_SESSION['departement'] !== "admin" && !empty($departements)) {
        $placeholders = implode(',', array_fill(0, count($departements), '?'));
        $types = str_repeat('i', count($departements));
        
        $query = "
            SELECT DISTINCT stuf.mecano, stuf.nom, stuf.daten, stuf.daterec, 
                   titres.libellet as grade, dep.depar as department, 
                   stuf.contrastage
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            LEFT JOIN titres ON titres.id = stuf.titre
            WHERE stuf.dep IN ($placeholders) AND stuf.contrastage IN (0,1,3)
            ORDER BY stuf.mecano ASC
        ";
        
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            throw new Exception('خطأ في إعداد الاستعلام: ' . mysqli_error($connection));
        }
        
        mysqli_stmt_bind_param($stmt, $types, ...$departements);
    } else {
        $query = "
            SELECT DISTINCT stuf.mecano, stuf.nom, stuf.daten, stuf.daterec, 
                   titres.libellet as grade, dep.depar as department, 
                   stuf.contrastage
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            LEFT JOIN titres ON titres.id = stuf.titre
            WHERE stuf.contrastage IN (0,1,3)
            ORDER BY stuf.mecano ASC
        ";
        
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            throw new Exception('خطأ في إعداد الاستعلام: ' . mysqli_error($connection));
        }
    }

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('خطأ في تنفيذ الاستعلام: ' . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_bind_result($stmt, $mecano, $nom, $daten, $daterec, $grade, $department, $contrastage);

    $employees = [];
    $departments = [];
    $grades = [];

    while (mysqli_stmt_fetch($stmt)) {
        // حساب العمر
        $age = 0;
        if (!empty($daten) && $daten != '0000-00-00') {
            $dateNaissance = date_create($daten);
            $dateToday = date_create('today');
            if ($dateNaissance) {
                $ageInterval = $dateNaissance->diff($dateToday);
                $age = $ageInterval->y;
            }
        }

        // حساب الأقدمية
        $seniority = 0;
        if (!empty($daterec) && $daterec != '0000-00-00') {
            $dateEmbauche = date_create($daterec);
            $dateToday = date_create('today');
            if ($dateEmbauche) {
                $ancienneteInterval = $dateEmbauche->diff($dateToday);
                $seniority = $ancienneteInterval->y;
            }
        }

        // تحديد نوع العون
        switch ($contrastage) {
            case 0: $situation = 'مترسم'; break;
            case 1: $situation = 'متربص'; break;
            case 3: $situation = 'ملحق'; break;
            default: $situation = 'غير محدد';
        }

        // تنظيف البيانات
        $department = !empty($department) ? $department : 'غير محدد';
        $grade = !empty($grade) ? $grade : 'غير محدد';

        $employee = [
            'mecano' => $mecano,
            'name' => $nom,
            'age' => $age,
            'seniority' => $seniority,
            'grade' => $grade,
            'situation' => $situation,
            'department' => $department
        ];

        $employees[] = $employee;

        // تجميع الأقسام والرتب الفريدة
        if (!in_array($department, $departments) && !empty($department)) {
            $departments[] = $department;
        }
        if (!in_array($grade, $grades) && !empty($grade)) {
            $grades[] = $grade;
        }
    }

    mysqli_stmt_close($stmt);

    // جلب جميع الأقسام من قاعدة البيانات
    $deptQuery = "SELECT depar FROM dep WHERE depar IS NOT NULL AND depar != '' ORDER BY depar";
    $deptResult = mysqli_query($connection, $deptQuery);
    $allDepartments = [];
    if ($deptResult) {
        while ($row = mysqli_fetch_assoc($deptResult)) {
            $allDepartments[] = $row['depar'];
        }
    }

    // جلب جميع الرتب من قاعدة البيانات
    $gradeQuery = "SELECT libellet FROM titres WHERE libellet IS NOT NULL AND libellet != '' ORDER BY libellet";
    $gradeResult = mysqli_query($connection, $gradeQuery);
    $allGrades = [];
    if ($gradeResult) {
        while ($row = mysqli_fetch_assoc($gradeResult)) {
            $allGrades[] = $row['libellet'];
        }
    }

    mysqli_close($connection);

    // إذا لم تكن هناك بيانات، نعيد مصفوفات فارغة
    if (empty($employees)) {
        $employees = [];
    }
    if (empty($allDepartments)) {
        $allDepartments = ['غير محدد'];
    }
    if (empty($allGrades)) {
        $allGrades = ['غير محدد'];
    }

    echo json_encode([
        'success' => true,
        'employees' => $employees,
        'departments' => $allDepartments,
        'grades' => $allGrades
    ]);

} catch (Exception $e) {
    // إرجاع رسالة الخطأ بشكل منظم
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>