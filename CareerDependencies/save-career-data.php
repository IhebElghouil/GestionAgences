<?php
session_start();
require('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mecano = mysqli_real_escape_string($connection, $_POST['mecano']);
    
    // Récupérer les IDs des rangs directement depuis le formulaire
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
    
    // Insérer avec les IDs (les champs ancienrang et nouveaurang sont toujours varchar(100) NOT NULL)
    // Donc on doit aussi insérer les libellés ou des valeurs par défaut
    $query = "INSERT INTO carriere (
        mecano, decision_number, issue_date, 
        ancienrang, ancienrang_id,
        nouveaurang, nouveaurang_id,
        ancienneechelle, nouvelechelle, 
        anciennegrade, nouveaugrade, 
        notes, dateeffet, commission
    ) VALUES (
        '$mecano', '$decision_number', '$issue_date',
        '', $old_rank_id,
        '', $new_rank_id,
        $old_scale, $new_scale,
        $old_grade, $new_grade,
        '$notes', '$effective_date', '$committee'
    )";
    
    if (mysqli_query($connection, $query)) {
        $career_id = mysqli_insert_id($connection);
        
        // Mettre à jour les libellés à partir des IDs
        if ($old_rank_id != 'NULL') {
            $updateOld = "UPDATE carriere c 
                         LEFT JOIN titres t ON c.ancienrang_id = t.id 
                         SET c.ancienrang = t.libellet 
                         WHERE c.id = $career_id";
            mysqli_query($connection, $updateOld);
        }
        
        if ($new_rank_id != 'NULL') {
            $updateNew = "UPDATE carriere c 
                         LEFT JOIN titres t ON c.nouveaurang_id = t.id 
                         SET c.nouveaurang = t.libellet 
                         WHERE c.id = $career_id";
            mysqli_query($connection, $updateNew);
        }
        
        // Gestion de l'upload de document
        if (isset($_FILES['career_document']) && $_FILES['career_document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/career_documents/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['career_document']['name']);
            $file_path = $upload_dir . $file_name;
            $file_type = $_FILES['career_document']['type'];
            $file_size = $_FILES['career_document']['size'];
            
            if (move_uploaded_file($_FILES['career_document']['tmp_name'], $file_path)) {
                $doc_query = "INSERT INTO career_documents (career_id, file_name, file_path, file_type, file_size) 
                             VALUES ('$career_id', '$file_name', '$file_path', '$file_type', '$file_size')";
                mysqli_query($connection, $doc_query);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Données enregistrées avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur SQL : ' . mysqli_error($connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
?>