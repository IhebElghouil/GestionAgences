<?php
session_start();
require('connection.php');

$mecano = $_GET['recipientname'] ?? null;
$rib = $_GET['rib'] ?? null;
$statut = $_GET['statut'] ?? null;

if (!$mecano || !$rib || !$statut) {
    $_SESSION['error_message'] = "Les données sont incomplètes.";
    header("Location: rib.php");
    exit();
}

try {
    // Démarrage de la transaction
    $connection->begin_transaction();

    // Mise à jour de la table 'social'
    $queryUpdateSocial = "UPDATE social SET rib=?, observations=? WHERE mecano=?";
    $stmtUpdateSocial = $connection->prepare($queryUpdateSocial);

    // Vérifie si mecano est numérique ou pas
    if (is_numeric($mecano)) {
        $stmtUpdateSocial->bind_param("ssi", $rib, $statut, $mecano);
    } else {
        $stmtUpdateSocial->bind_param("sss", $rib, $statut, $mecano);
    }

    $stmtUpdateSocial->execute();

    // Validation de la transaction
    $connection->commit();

    $_SESSION['success_message'] = "تمّ تحيين المعطبات بنجاح";
    header("Location: rib.php");
    exit();

} catch (Exception $e) {
    // Annulation de la transaction en cas d'erreur
    $connection->rollback();
    $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $e->getMessage();
    header("Location: rib.php");
    exit();
}

// Fermeture de la connexion
$connection->close();
?>
