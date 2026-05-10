<?php
header('Content-Type: application/json');
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/conges.functions.php';

$response = ['success' => false, 'message' => '', 'nbJours' => 0];

try {
    // 🔹 Récupération sécurisée des POST
    $mecano = $_POST['mecano'] ?? null;
    $dateDebut = $_POST['DateDebut'] ?? null;
    $dateFin   = $_POST['DateFin'] ?? null;
    $typeRepos  = $_POST['TypeRepos'] ?? null;
    $jourRepos  = $_POST['JourDeReposFixe'] ?? null;
    $solde      = $_POST['Solde'] ?? 0;

    // 🔹 Validation minimale
    if (!$mecano || !$dateDebut || !$dateFin || !$typeRepos) {
        throw new Exception("Toutes les informations sont requises.");
    }

    if ($dateDebut > $dateFin) {
        throw new Exception("La date de début doit être avant la date de fin.");
    }

    // 🔹 Calcul du nombre de jours (fonction séparée)
    $nbJours = calculateCongeDays($dateDebut, $dateFin, $jourRepos, $typeRepos);

    if ($nbJours > $solde) {
        throw new Exception("Le solde restant est insuffisant.");
    }

    // 🔹 Tout est OK
    $response['success'] = true;
    $response['nbJours'] = $nbJours;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
