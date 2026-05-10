<?php
session_start();
require('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $nom = mysqli_real_escape_string($connection, $_POST['nom']);
    $mecano = mysqli_real_escape_string($connection, $_POST['mecano']);
    $grade = mysqli_real_escape_string($connection, $_POST['grade']);
    $daterapport = mysqli_real_escape_string($connection, $_POST['daterapport']);
    $nrapport = mysqli_real_escape_string($connection, $_POST['nrapport']);
    $datereceptionrapport = mysqli_real_escape_string($connection, $_POST['datereceptionrapport']);
    $faute = mysqli_real_escape_string($connection, $_POST['faute']);
    $questionnaire = mysqli_real_escape_string($connection, $_POST['questionnaire']);
    $description = $_POST['description'];

    // Nettoyer et sécuriser le contenu HTML
    $description = cleanHTML($description);
    $description = mysqli_real_escape_string($connection, $description);
    
    // Check if required fields are not empty
    if (empty($nom) || empty($mecano) || empty($daterapport) || empty($nrapport) || empty($datereceptionrapport)) {
        die("<script>alert('جميع الحقول الإلزامية يجب ملؤها'); history.back();</script>");
    }

    try {
        $sql = "INSERT INTO sanctions (
            mecano, 
            nom, 
            grade, 
            report_date, 
            report_number, 
            datereception, 
            datefaute, 
            datequestionnaire, 
            faute,
            Datestamp
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($connection, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssss", 
                $mecano, $nom, $grade, $daterapport, $nrapport, 
                $datereceptionrapport, $faute, $questionnaire, $description
            );

            if (mysqli_stmt_execute($stmt)) {
                // Récupérer l'ID inséré
                $last_id = mysqli_insert_id($connection);
                
                // Rediriger vers la page d'impression
                echo "<script>
                    alert('تم تسجيل الإستجواب بنجاح');
                    window.open('print_questionnaire.php?id=" . $last_id . "', '_blank');
                    
                </script>";
            } else {
                throw new Exception("خطأ في تنفيذ الاستعلام: " . mysqli_error($connection));
            }
            
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception("خطأ في إعداد الاستعلام: " . mysqli_error($connection));
        }
    } catch (Exception $e) {
        echo "<script>
            alert('حدث خطأ: " . addslashes($e->getMessage()) . "');
            history.back();
        </script>";
    }

    mysqli_close($connection);
} else {
    header("Location: sanctions.php");
    exit();
}

// Fonction pour nettoyer le HTML
function cleanHTML($html) {
    // Autoriser seulement les balises sécurisées
    $allowed_tags = '<br><strong><b><em><i><u><p><div><span><ul><ol><li><blockquote>';
    $allowed_tags .= '<h1><h2><h3><h4><h5><h6>';
    
    // Supprimer les balises non autorisées
    $html = strip_tags($html, $allowed_tags);
    
    // Nettoyer les attributs dangereux
    $html = preg_replace('/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i', '<$1$2>', $html);
    
    return trim($html);
}
?>