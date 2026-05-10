<?php
session_start();
require('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    
    // Récupérer les IDs des rangs
    $old_rank_id = !empty($_POST['old_rank']) ? intval($_POST['old_rank']) : 'NULL';
    $new_rank_id = !empty($_POST['new_rank']) ? intval($_POST['new_rank']) : 'NULL';
    
    $decision_number = mysqli_real_escape_string($connection, $_POST['decision_number']);
    $issue_date = mysqli_real_escape_string($connection, $_POST['issue_date']);
    $old_scale = intval($_POST['old_scale']);
    $new_scale = intval($_POST['new_scale']);
    $old_grade = intval($_POST['old_grade']);
    $new_grade = intval($_POST['new_grade']);
    $notes = mysqli_real_escape_string($connection, $_POST['notes']);
    $effective_date = mysqli_real_escape_string($connection, $_POST['effective_date']);
    $committee = mysqli_real_escape_string($connection, $_POST['committee']);
    
    // Mettre à jour avec les IDs
    $query = "UPDATE carriere SET 
              decision_number = '$decision_number',
              issue_date = '$issue_date',
              ancienrang_id = $old_rank_id,
              nouveaurang_id = $new_rank_id,
              ancienneechelle = $old_scale,
              nouvelechelle = $new_scale,
              anciennegrade = $old_grade,
              nouveaugrade = $new_grade,
              notes = '$notes',
              dateeffet = '$effective_date',
              commission = '$committee'
              WHERE id = '$id'";
    
    if (mysqli_query($connection, $query)) {
        // Mettre à jour les libellés à partir des IDs
        if ($old_rank_id != 'NULL') {
            $updateOld = "UPDATE carriere c 
                         LEFT JOIN titres t ON c.ancienrang_id = t.id 
                         SET c.ancienrang = t.libellet 
                         WHERE c.id = $id";
            mysqli_query($connection, $updateOld);
        } else {
            mysqli_query($connection, "UPDATE carriere SET ancienrang = '' WHERE id = $id");
        }
        
        if ($new_rank_id != 'NULL') {
            $updateNew = "UPDATE carriere c 
                         LEFT JOIN titres t ON c.nouveaurang_id = t.id 
                         SET c.nouveaurang = t.libellet 
                         WHERE c.id = $id";
            mysqli_query($connection, $updateNew);
        } else {
            mysqli_query($connection, "UPDATE carriere SET nouveaurang = '' WHERE id = $id");
        }
        
        echo json_encode(['success' => true, 'message' => 'Données mises à jour avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL : ' . mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>