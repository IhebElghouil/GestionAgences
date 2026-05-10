<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

// التحقق من صلاحية المستخدم
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول']);
    exit;
}

try {
    // جمع البيانات من النموذج
    $id = $_POST['id'] ?? null;
    $mecano = $_POST['mecano'] ?? '';
    $nomprenom = $_POST['nomprenom'] ?? '';
    $affectation = $_POST['affectation'] ?? '';
    $source = $_POST['source'] ?? '';
    $situation = $_POST['situation'] ?? '';
    $datedetachement = $_POST['datedetachement'] ?? '';
    $periode = $_POST['periode'] ?? 1;
    $renouvellemnt1 = $_POST['renouvellemnt1'] ?? 0;
    $renouvellemnt2 = $_POST['renouvellemnt2'] ?? 0;
    $renouvellemnt3 = $_POST['renouvellemnt3'] ?? 0;
    $dossier = $_POST['dossier'] ?? '';
    $observations = $_POST['observations'] ?? '';

    // التحقق من البيانات المطلوبة
    if (empty($mecano) || empty($nomprenom) || empty($affectation) || empty($source) || empty($situation) || empty($datedetachement)) {
        echo json_encode(['success' => false, 'message' => 'جميع الحقول المطلوبة يجب ملؤها']);
        exit;
    }

    if ($id) {
        // تحديث بيانات موجودة
        $stmt = mysqli_prepare($connection, "
            UPDATE detachement 
            SET mecano = ?, nomprenom = ?, affectation = ?, source = ?, situation = ?, 
                datedetachement = ?, periode = ?, renouvellemnt1 = ?, renouvellemnt2 = ?, 
                renouvellemnt3 = ?, dossier = ?, observations = ?
            WHERE id = ?
        ");
        
        mysqli_stmt_bind_param($stmt, 'ssssssiiisssi', 
            $mecano, $nomprenom, $affectation, $source, $situation,
            $datedetachement, $periode, $renouvellemnt1, $renouvellemnt2, $renouvellemnt3,
            $dossier, $observations, $id
        );
    } else {
        // إضافة بيانات جديدة
        $stmt = mysqli_prepare($connection, "
            INSERT INTO detachement 
            (mecano, nomprenom, affectation, source, situation, datedetachement, 
             periode, renouvellemnt1, renouvellemnt2, renouvellemnt3, dossier, observations) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        mysqli_stmt_bind_param($stmt, 'ssssssiiisss', 
            $mecano, $nomprenom, $affectation, $source, $situation,
            $datedetachement, $periode, $renouvellemnt1, $renouvellemnt2, $renouvellemnt3,
            $dossier, $observations
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'تم حفظ البيانات بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . mysqli_error($connection)]);
    }

    mysqli_stmt_close($stmt);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
}

mysqli_close($connection);
?>