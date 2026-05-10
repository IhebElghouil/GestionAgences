<?php
// get_departments.php
require_once 'config.php';

$response = ['success' => false, 'departments' => []];

try {
    $conn = getConnection();
    
    // محاولة استخدام جدول dep أولاً للحصول على الأسماء العربية
    $checkDep = $conn->query("SHOW TABLES LIKE 'dep'");
    if ($checkDep->rowCount() > 0) {
        // استخدام جدول dep للحصول على الأقسام بالعربية
        $sql = "SELECT idservice, depar as name, dep as name_fr 
                FROM dep 
                WHERE idservice > 0 
                GROUP BY idservice 
                ORDER BY depar";
        $stmt = executeQuery($conn, $sql);
        
        $departments = [['id' => 'all', 'name' => 'جميع الإدارات']];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $departments[] = [
                'id' => $row['idservice'], 
                'name' => $row['name']  // الاسم بالعربية من عمود depar
            ];
        }
    } else {
        // إذا لم يوجد جدول dep، استخدم جدول service
        $sql = "SELECT id_service as id, Intitule_Service as name FROM service ORDER BY name";
        $stmt = executeQuery($conn, $sql);
        
        $departments = [['id' => 'all', 'name' => 'جميع الإدارات']];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $departments[] = ['id' => $row['id'], 'name' => $row['name']];
        }
    }
    
    $response['success'] = true;
    $response['departments'] = $departments;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['departments'] = [['id' => 'all', 'name' => 'جميع الإدارات']];
}

echo json_encode($response);
?>