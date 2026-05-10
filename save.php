<?php
session_start();
require('connection.php');

// Activation du rapport d'erreurs
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Fonction de nettoyage des données
function clean_data($data) {
    return htmlspecialchars(trim($data));
}

// Récupération et nettoyage des données
$Drecrutement = clean_data($_POST['Drecrutement'] ?? '');
$Dnaissance = clean_data($_POST['Dnaissance'] ?? '');
$cin = clean_data($_POST['cin'] ?? '');
$mecano = clean_data($_POST['mecano'] ?? '');
$nomprenom = clean_data($_POST['nomprenom'] ?? '');
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
$Dpointage = clean_data($_POST['Dpointage'] ?? '');
$lait = clean_data($_POST['lait'] ?? '');
$ncnss = clean_data($_POST['codesocial'] ?? '');
$nassurance = clean_data($_POST['codeassurance'] ?? '');
$statut = clean_data($_POST['statut'] ?? '');
$numpers = clean_data($_POST['numpers'] ?? '');
$sexe = clean_data($_POST['sexe'] ?? '');
$etat = 2; //نوع التوقيت اداري

// Condition : Si pointage n'est pas 1, on met Dpointage à NULL
if ($pointagemachine != '1') {
    $Dpointage = null;
}

// Vérification des données essentielles
if (empty($Drecrutement) || empty($Dnaissance) || empty($cin) || empty($mecano)) {
    $_SESSION['error_message'] = "Erreur : Données manquantes.";
    header("Location: c_agents.php");
    exit;
}

try {
    // Démarrer la transaction
    $connection->begin_transaction();

    // --- Fonction utilitaire ---
    function executeOrThrow($stmt, $context = "")
    {
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de $context : " . $stmt->error);
        }
    }

    // Vérifier si le mecano existe déjà
    $checkQuery = "SELECT COUNT(*) FROM stuf WHERE mecano = ? OR cin = ?";
    $checkStmt = $connection->prepare($checkQuery);
    $checkStmt->bind_param("ss", $mecano, $cin);
    executeOrThrow($checkStmt, "la vérification d'existence");
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        $connection->rollback();
        $_SESSION['error_message'] = "⚠️ خطأ: رقم الأجير ($mecano) موجود مسبقاً في قاعدة البيانات.";
        header("Location: c_agents.php");
        exit();
    }

    // --- Insertion dans 'stuf' avec Dpointage ---
    $query = "INSERT INTO stuf (
        mecano, nom, titre, dep, daten, daterec, cin, statut, numpers, echelle, degree, idservice,
        contrastage, anneeprec, anneeactu, etat, fil, jrepos, pointagemachine, lait, sexe, date_debut_pointage
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param(
        "ssssssssssssssssssssss",
        $mecano, $nomprenom, $titre, $dep, $Dnaissance, $Drecrutement, $cin, $statut,
        $fil, $echelle, $degree, $idservice, $contrastage, $anneeprec, $anneeactu,
        $etat, $numpers, $jrepos, $pointagemachine, $lait, $sexe, $Dpointage
    );
    executeOrThrow($stmt, "l'insertion dans stuf");

    // --- Insertion dans 'cartes' si nécessaire ---
    if (in_array($titre, [6, 7, 8])) {
        $queryInsertCard = "INSERT INTO cartes (mecano, type) VALUES (?, ?)";
        $stmtInsertCard = $connection->prepare($queryInsertCard);

        $type0 = 0;
        $stmtInsertCard->bind_param("si", $mecano, $type0);
        executeOrThrow($stmtInsertCard, "l'insertion carte type 0");

        $type1 = 1;
        $stmtInsertCard->bind_param("si", $mecano, $type1);
        executeOrThrow($stmtInsertCard, "l'insertion carte type 1");
    }

    // --- Insertion dans 'social' ---
    $queryInsertSocial = "INSERT INTO social (mecano, ncnss, nassurance) VALUES (?, ?, ?)";
    $stmtInsertSocial = $connection->prepare($queryInsertSocial);
    $stmtInsertSocial->bind_param("sii", $mecano, $ncnss, $nassurance);
    executeOrThrow($stmtInsertSocial, "l'insertion dans social");

    // --- Insertion dans 'nbconge' ---
    $TotalSolde = $anneeprec + $anneeactu;
    $queryInsertNbConge = "INSERT INTO nbconge VALUES (?, ?, ?, ?)";
    $stmtInsertNbConge = $connection->prepare($queryInsertNbConge);
    $stmtInsertNbConge->bind_param("issi", $mecano, $anneeprec, $anneeactu, $TotalSolde);
    executeOrThrow($stmtInsertNbConge, "l'insertion dans nbconge");

    // --- Tout s’est bien passé : valider la transaction ---
    $connection->commit();

    $message = "✅ تمّ إضافة المعطيات بنجاح.";
    $type = "success";

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    if ($connection->errno === 0) {
        $connection->rollback();
    }
    $message = "❌ خطأ أثناء الإضافة: " . $e->getMessage();
    $type = "error";
}

// Fermeture de la connexion
$connection->close();
?>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: '<?php echo $type; ?>',
        title: '<?php echo ($type === "success") ? "تم بنجاح" : "خطأ"; ?>',
        text: '<?php echo addslashes($message); ?>',
        confirmButtonText: 'موافق',
        confirmButtonColor: '<?php echo ($type === "success") ? "#28a745" : "#d33"; ?>',
    }).then((result) => {
        // Redirection après fermeture de l'alerte
        window.location.href = "c_agents.php";
    });
});
</script>