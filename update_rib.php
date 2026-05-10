<?php
session_start();
require('connection.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que l'utilisateur est admin
    if (!isset($_SESSION['departement']) || $_SESSION['departement'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول']);
        exit;
    }
    
    // Récupérer et valider les données
    $mecano = isset($_POST['mecano']) ? mysqli_real_escape_string($connection, $_POST['mecano']) : '';
    $rib = isset($_POST['rib']) ? mysqli_real_escape_string($connection, $_POST['rib']) : '';
    
    if (empty($mecano)) {
        echo json_encode(['success' => false, 'message' => 'الرقم الآلي مطلوب']);
        exit;
    }
    
    // Valider le format du RIB (20 chiffres)
    if (!empty($rib) && (!is_numeric($rib) || strlen($rib) !== 20)) {
        echo json_encode(['success' => false, 'message' => 'يجب أن يتكون RIB من 20 رقمًا']);
        exit;
    }
    
    try {
        // Mettre à jour le RIB dans la table social
        $query = "UPDATE social SET rib = ? WHERE mecano = ?";
        $stmt = mysqli_prepare($connection, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $rib, $mecano);
            $success = mysqli_stmt_execute($stmt);
            
            if ($success) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo json_encode(['success' => true, 'message' => 'تم تحديث RIB بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'لم يتم العثور على العون أو لم يتم تغيير أي بيانات']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . mysqli_error($connection)]);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo json_encode(['success' => false, 'message' => 'خطأ في إعداد الاستعلام']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'خطأ: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير صالحة']);
}

mysqli_close($connection);
?>