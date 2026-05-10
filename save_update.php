<?php
session_start();
require('connection.php');

// Activation du rapport d'erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Définir l'en-tête JSON
header('Content-Type: application/json');

// Fonction de nettoyage des données
function clean_data($data) {
    return htmlspecialchars(trim($data));
}

try {
    // Récupération et nettoyage des données
    $Drecrutement = clean_data($_POST['Drecrutement'] ?? '');
    $Dnaissance = clean_data($_POST['Dnaissance'] ?? '');
    $cin = clean_data($_POST['cin'] ?? '');
    $mecano = clean_data($_POST['recipientname'] ?? '');
    $titre = clean_data($_POST['grade'] ?? '');
    $dep = clean_data($_POST['dep'] ?? '');
    $echelle = clean_data($_POST['echelle'] ?? '');
    $degree = clean_data($_POST['echelon'] ?? '');
    $idservice = clean_data($_POST['service'] ?? '');
    $contrastage = clean_data($_POST['etat'] ?? '');
    $anneeprec = clean_data($_POST['restconge'] ?? '');
    $anneeactu = clean_data($_POST['soldeconge'] ?? '');
    $fil = clean_data($_POST['fil'] ?? '');
    $jrepos = clean_data($_POST['Jourrepos'] ?? '');
    $pointagemachine = clean_data($_POST['pointage'] ?? '');
    $lait = clean_data($_POST['lait'] ?? '');
    $ncnss = clean_data($_POST['codesocial'] ?? '');
    $nassurance = clean_data($_POST['codeassurance'] ?? '');
	$numpers = clean_data($_POST['numpers'] ?? '');
    $statut = clean_data($_POST['statut'] ?? '');
    $sexe = clean_data($_POST['sexe'] ?? '');
    $Dpointage = isset($_POST['Dpointage']) ? clean_data($_POST['Dpointage']) : null;
    
    // Vérification des données essentielles
    if (empty($mecano)) {
        echo json_encode(['success' => false, 'message' => 'Erreur : Numéro d\'employé manquant.']);
        exit;
    }
    
    if (empty($Drecrutement) || empty($Dnaissance) || empty($cin)) {
        echo json_encode(['success' => false, 'message' => 'Erreur : Données manquantes (date recrutement, date naissance ou CIN).']);
        exit;
    }
    
    // Conversion des valeurs numériques
    $titre = !empty($titre) ? (int)$titre : 0;
    $dep = !empty($dep) ? (int)$dep : 0;
    $echelle = !empty($echelle) ? (float)$echelle : 0;
    $degree = !empty($degree) ? (int)$degree : 0;
    $idservice = !empty($idservice) ? (int)$idservice : 0;
    $contrastage = !empty($contrastage) ? (int)$contrastage : 0;
    $anneeprec = !empty($anneeprec) ? (float)$anneeprec : 0;
    $anneeactu = !empty($anneeactu) ? (float)$anneeactu : 0;
    $jrepos = !empty($jrepos) ? (int)$jrepos : 0;
    $pointagemachine = !empty($pointagemachine) ? (int)$pointagemachine : 0;
    $lait = !empty($lait) ? (int)$lait : 0;
    
    // Démarrage de la transaction
    $connection->begin_transaction();
    
    // 1. Mise à jour de la table 'stuf'
    $query = "UPDATE stuf SET 
                titre = ?,
                dep = ?,
                daten = ?,
                daterec = ?,
                cin = ?,
                statut = ?,
                echelle = ?,
                degree = ?,
                idservice = ?,
                contrastage = ?,
                anneeprec = ?,
                anneeactu = ?,
				fil= ?,
                numpers = ?,
                jrepos = ?,
                pointagemachine = ?,
                lait = ?,
                sexe = ?
              WHERE mecano = ?";
    
    $stmt = $connection->prepare($query);
    if (!$stmt) {
        throw new Exception("Erreur de préparation de la requête stuf: " . $connection->error);
    }
    
    $stmt->bind_param("iissssddiiddsssiiss", 
        $titre, $dep, $Dnaissance, $Drecrutement, $cin, $statut, 
        $echelle, $degree, $idservice, $contrastage, $anneeprec, 
        $anneeactu, $fil, $numpers, $jrepos, $pointagemachine, $lait, $sexe, $mecano
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Erreur d'exécution de la requête stuf: " . $stmt->error);
    }
    $stmt->close();
    
    // 2. Mise à jour de la table 'social'
    $queryUpdateSocial = "UPDATE social SET ncnss = ?, nassurance = ? WHERE mecano = ?";
    $stmtUpdateSocial = $connection->prepare($queryUpdateSocial);
    if (!$stmtUpdateSocial) {
        throw new Exception("Erreur de préparation de la requête social: " . $connection->error);
    }
    
    $stmtUpdateSocial->bind_param("ssi", $ncnss, $nassurance, $mecano);
    if (!$stmtUpdateSocial->execute()) {
        throw new Exception("Erreur d'exécution de la requête social: " . $stmtUpdateSocial->error);
    }
    $stmtUpdateSocial->close();
    
    // 3. Récupération du nombre de jours consommés
    $querySelectNbConge = "SELECT COALESCE((nbj1 + nbj2) - rest, 0) AS NBjourConsomme FROM nbconge WHERE mecano = ?";
    $stmtSelectNbConge = $connection->prepare($querySelectNbConge);
    if (!$stmtSelectNbConge) {
        throw new Exception("Erreur de préparation de la requête select conge: " . $connection->error);
    }
    
    $stmtSelectNbConge->bind_param("i", $mecano);
    if (!$stmtSelectNbConge->execute()) {
        throw new Exception("Erreur d'exécution de la requête select conge: " . $stmtSelectNbConge->error);
    }
    
    $result = $stmtSelectNbConge->get_result();
    $row = $result->fetch_assoc();
    $NBjourConsomme = $row ? (int)$row['NBjourConsomme'] : 0;
    $stmtSelectNbConge->close();
    
    // 4. Calcul du nouveau solde de congés
    $soldeTotal = (float)$anneeprec + (float)$anneeactu;
    $rest = $soldeTotal - $NBjourConsomme;
    
    // S'assurer que le solde n'est pas négatif
    if ($rest < 0) {
        $rest = 0;
    }
    
    // 5. Mise à jour de la table 'nbconge'
    $queryUpdateNbConge = "UPDATE nbconge SET nbj1 = ?, nbj2 = ?, rest = ? WHERE mecano = ?";
    $stmtUpdateNbConge = $connection->prepare($queryUpdateNbConge);
    if (!$stmtUpdateNbConge) {
        throw new Exception("Erreur de préparation de la requête nbconge: " . $connection->error);
    }
    
    $stmtUpdateNbConge->bind_param("dddi", $anneeprec, $anneeactu, $rest, $mecano);
    if (!$stmtUpdateNbConge->execute()) {
        throw new Exception("Erreur d'exécution de la requête nbconge: " . $stmtUpdateNbConge->error);
    }
    $stmtUpdateNbConge->close();
    
    // 6. Si Dpointage existe, mettre à jour
    if ($Dpointage !== null && !empty($Dpointage)) {
        $queryUpdatePointage = "UPDATE stuf SET date_debut_pointage = ? WHERE mecano = ?";
        $stmtUpdatePointage = $connection->prepare($queryUpdatePointage);
        if ($stmtUpdatePointage) {
            $stmtUpdatePointage->bind_param("si", $Dpointage, $mecano);
            $stmtUpdatePointage->execute();
            $stmtUpdatePointage->close();
        }
    }
    
    // Validation de la transaction
    $connection->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => '✅ تمّ تحيين المعطيات بنجاح.'
    ]);
    
} catch (Exception $e) {
    // Annulation de la transaction en cas d'erreur
    if ($connection->connect_errno === 0) {
        $connection->rollback();
    }
    
    echo json_encode([
        'success' => false, 
        'message' => '❌ خطأ أثناء التحيين: ' . $e->getMessage()
    ]);
} finally {
    // Fermeture de la connexion
    if (isset($connection) && !$connection->connect_errno) {
        $connection->close();
    }
}
exit;
?>