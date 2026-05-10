<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/conges.functions.php';

$response = ['success' => false, 'message' => ''];

try {
    $mecano    = $_POST['mecano'] ?? null;
    $dateDebut = $_POST['DateDebut'] ?? null;
    $dateFin   = $_POST['DateFin'] ?? null;
    $typeRepos = $_POST['TypeRepos'] ?? null;
    $nbJours   = $_POST['NbJoursConge'] ?? 0;

    if (!$mecano || !$dateDebut || !$dateFin || !$typeRepos) {
        throw new Exception("Données manquantes pour enregistrer le congé.");
    }

    // 🔹 Vérification doublons / conflits
    if (checkCongeConflict($mecano, $dateDebut, $dateFin)) {
        throw new Exception("Conflit : ce congé chevauche un congé existant.");
    }

    // 🔹 Insertion SQL sécurisée
    $stmt = $db->prepare("
        INSERT INTO conges (mecano, date_debut, date_fin, type, nb_jours) 
        VALUES (:mecano, :debut, :fin, :type, :nbjours)
    ");

    $stmt->execute([
        ':mecano' => $mecano,
        ':debut'  => $dateDebut,
        ':fin'    => $dateFin,
        ':type'   => $typeRepos,
        ':nbjours'=> $nbJours
    ]);

    $response['success'] = true;
    $response['message'] = "Congé enregistré avec succès.";

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
