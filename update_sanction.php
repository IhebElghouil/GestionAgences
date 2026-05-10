<?php
session_start();
require('connection.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['congidGA'])) {
    header("Location: login.php");
    exit();
}

// Vérifier si c'est une requête POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Récupérer et sécuriser les données
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    $mecano = mysqli_real_escape_string($connection, $_POST['mecano']);
    $nom = mysqli_real_escape_string($connection, $_POST['nom']);
    $grade = mysqli_real_escape_string($connection, $_POST['grade']);
    
    // Gérer les dates NULL
    $datefaute = !empty($_POST['datefaute']) ? "'" . mysqli_real_escape_string($connection, $_POST['datefaute']) . "'" : "NULL";
    $datequestionnaire = !empty($_POST['datequestionnaire']) ? "'" . mysqli_real_escape_string($connection, $_POST['datequestionnaire']) . "'" : "NULL";
    $datereception = !empty($_POST['datereception']) ? "'" . mysqli_real_escape_string($connection, $_POST['datereception']) . "'" : "NULL";
    $datesanction = !empty($_POST['datesanction']) ? "'" . mysqli_real_escape_string($connection, $_POST['datesanction']) . "'" : "NULL";
    $report_date = !empty($_POST['report_date']) ? "'" . mysqli_real_escape_string($connection, $_POST['report_date']) . "'" : "NULL";
    
    $faute = mysqli_real_escape_string($connection, $_POST['faute']);
    $sanction = mysqli_real_escape_string($connection, $_POST['sanction']);
    $dates_arret = mysqli_real_escape_string($connection, $_POST['dates_arret']);
    $report_number = mysqli_real_escape_string($connection, $_POST['report_number']);
    
    // Gérer le statut (fermé ou ouvert)
    $statut = isset($_POST['close_questionnaire']) ? 'closed' : 'open';
    
    // Gérer le conseil de discipline (checkbox)
    $conseil_discipline = isset($_POST['conseil_discipline']) ? 1 : 0;
    
    // Récupérer les fichiers existants
    $sql = "SELECT questionnaire_file, report_file, sanction_decision_file FROM sanctions WHERE idsanction = $id";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    
    $questionnaire_file = $row['questionnaire_file'];
    $report_file = $row['report_file'];
    $sanction_decision_file = $row['sanction_decision_file'];
    
    // Dossier de téléchargement
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Traitement du fichier questionnaire
    if (isset($_FILES['questionnaire_file']) && $_FILES['questionnaire_file']['error'] == 0) {
        $file = $_FILES['questionnaire_file'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'questionnaire_' . $id . '_' . time() . '.' . $extension;
        $destination = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Supprimer l'ancien fichier s'il existe
            if (!empty($questionnaire_file) && file_exists($upload_dir . $questionnaire_file)) {
                unlink($upload_dir . $questionnaire_file);
            }
            $questionnaire_file = $new_filename;
        }
    } else if (isset($_POST['delete_questionnaire_file']) && $_POST['delete_questionnaire_file'] == '1') {
        // Supprimer le fichier existant
        if (!empty($questionnaire_file) && file_exists($upload_dir . $questionnaire_file)) {
            unlink($upload_dir . $questionnaire_file);
        }
        $questionnaire_file = '';
    }
    
    // Traitement du fichier rapport
    if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
        $file = $_FILES['report_file'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'report_' . $id . '_' . time() . '.' . $extension;
        $destination = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Supprimer l'ancien fichier s'il existe
            if (!empty($report_file) && file_exists($upload_dir . $report_file)) {
                unlink($upload_dir . $report_file);
            }
            $report_file = $new_filename;
        }
    } else if (isset($_POST['delete_report_file']) && $_POST['delete_report_file'] == '1') {
        // Supprimer le fichier existant
        if (!empty($report_file) && file_exists($upload_dir . $report_file)) {
            unlink($upload_dir . $report_file);
        }
        $report_file = '';
    }
    
    // Traitement du fichier décision de sanction
    if (isset($_FILES['sanction_decision_file']) && $_FILES['sanction_decision_file']['error'] == 0) {
        $file = $_FILES['sanction_decision_file'];
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'sanction_' . $id . '_' . time() . '.' . $extension;
        $destination = $upload_dir . $new_filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Supprimer l'ancien fichier s'il existe
            if (!empty($sanction_decision_file) && file_exists($upload_dir . $sanction_decision_file)) {
                unlink($upload_dir . $sanction_decision_file);
            }
            $sanction_decision_file = $new_filename;
        }
    } else if (isset($_POST['delete_sanction_decision_file']) && $_POST['delete_sanction_decision_file'] == '1') {
        // Supprimer le fichier existant
        if (!empty($sanction_decision_file) && file_exists($upload_dir . $sanction_decision_file)) {
            unlink($upload_dir . $sanction_decision_file);
        }
        $sanction_decision_file = '';
    }
    
    // Construire la requête SQL avec tous les champs
    $sql = "UPDATE sanctions SET 
            mecano = '$mecano',
            nom = '$nom',
            grade = '$grade',
            datefaute = $datefaute,
            datequestionnaire = $datequestionnaire,
            datereception = $datereception,
            faute = '$faute',
            sanction = '$sanction',
            dates_arret = '$dates_arret',
            datesanction = $datesanction,
            report_number = '$report_number',
            report_date = $report_date,
            questionnaire_file = " . (!empty($questionnaire_file) ? "'$questionnaire_file'" : "NULL") . ",
            report_file = " . (!empty($report_file) ? "'$report_file'" : "NULL") . ",
            sanction_decision_file = " . (!empty($sanction_decision_file) ? "'$sanction_decision_file'" : "NULL") . ",
            statut = '$statut',
            conseil_discipline = $conseil_discipline
            WHERE idsanction = $id";
    
    // Exécuter la requête
    if (mysqli_query($connection, $sql)) {
        // Journaliser l'action dans les logs si nécessaire
        $user_id = $_SESSION['congidGA'];
        $action = "Mise à jour de la sanction ID: $id - Employé: $mecano - $nom";
        $log_sql = "INSERT INTO action_logs (user_id, action, timestamp) VALUES ('$user_id', '$action', NOW())";
        mysqli_query($connection, $log_sql);
        
        // Retourner une réponse JSON de succès
        echo json_encode([
            'success' => true, 
            'message' => 'تم تحديث البيانات بنجاح',
            'data' => [
                'id' => $id,
                'mecano' => $mecano,
                'nom' => $nom,
                'conseil_discipline' => $conseil_discipline
            ]
        ]);
    } else {
        // Retourner une réponse JSON d'erreur
        echo json_encode([
            'success' => false, 
            'message' => 'حدث خطأ أثناء تحديث البيانات: ' . mysqli_error($connection)
        ]);
    }
    
} else {
    // Si ce n'est pas une requête POST, rediriger
    header("Location: index.php");
    exit();
}

// Fermer la connexion
mysqli_close($connection);
?>