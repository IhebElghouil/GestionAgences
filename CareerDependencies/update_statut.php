<?php
// update_statut.php
require_once('../connection.php');

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Vérifier si c'est une requête AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Support GET et POST
$mecano = isset($_GET['mecano']) ? intval($_GET['mecano']) : 
          (isset($_POST['mecano']) ? intval($_POST['mecano']) : 0);

if($mecano > 0) {
    $sql = "UPDATE stuf SET contrastage = 0 WHERE mecano = ? AND contrastage IN (4,5,6)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $mecano);
    
    if($stmt->execute()) {
        $affected_rows = $stmt->affected_rows;
        
        if($affected_rows > 0) {
            // Succès - retourner JSON
            echo json_encode([
                'success' => true,
                'message' => '✅ تم تحديث الحالة بنجاح',
                'affected_rows' => $affected_rows,
                'mecano' => $mecano
            ]);
        } else {
            // Aucune ligne mise à jour (le contraste n'est pas 4,5,6)
            echo json_encode([
                'success' => false,
                'message' => '⚠️ العون مباشر',
                'mecano' => $mecano
            ]);
        }
    } else {
        // Erreur SQL
        echo json_encode([
            'success' => false,
            'message' => '❌ حدث خطأ أثناء التحديث: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
} else {
    // Paramètre manquant
    echo json_encode([
        'success' => false,
        'message' => '❌ الرقم الآلي غير صالح'
    ]);
}

$connection->close();
?>