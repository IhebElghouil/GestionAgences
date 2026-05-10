<?php
session_start();
require('connection.php');

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Vérifier si grade_id est présent
if(isset($_POST['grade_id']) && !empty($_POST['grade_id'])) {
    $grade_id = (int)$_POST['grade_id'];
    
    // Récupérer la classe depuis la table titres
    $query = "SELECT classe FROM titres WHERE id = $grade_id LIMIT 1";
    $result = mysqli_query($connection, $query);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'classe' => $row['classe']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'classe' => '',
            'message' => 'Aucune classe trouvée pour ce grade'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'classe' => '',
        'message' => 'Grade ID manquant'
    ]);
}

mysqli_close($connection);
?>