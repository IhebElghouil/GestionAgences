<?php
// conges.functions.php
require_once __DIR__ . '/db.php';

/**
 * Calculer le nombre de jours de congé entre deux dates
 * en excluant les jours de repos fixes et week-ends selon type
 */
function calculateCongeDays($startDate, $endDate, $joursReposFixes = [], $typeRepos = 'normal') {
    $start = new DateTime($startDate);
    $end   = new DateTime($endDate);
    $end->modify('+1 day'); // inclure le dernier jour

    $interval = new DateInterval('P1D');
    $period   = new DatePeriod($start, $interval, $end);

    $nbJours = 0;

    foreach ($period as $day) {
        $weekday = $day->format('N'); // 1=Mon ... 7=Sun

        // Exclure week-end ou jour de repos fixe
        if (in_array($weekday, $joursReposFixes)) {
            continue;
        }

        // si type de congé spécial, on peut ajouter conditions ici
        $nbJours++;
    }

    return $nbJours;
}

/**
 * Vérifie si un congé existe déjà pour un employé sur une période donnée
 */
function checkCongeConflict($mecano, $startDate, $endDate) {
    global $db;

    $stmt = $db->prepare("
        SELECT COUNT(*) as total
        FROM conges
        WHERE mecano = :mecano
        AND (
            (date_debut <= :endDate AND date_fin >= :startDate)
        )
    ");

    $stmt->execute([
        ':mecano' => $mecano,
        ':startDate' => $startDate,
        ':endDate'   => $endDate
    ]);

    $result = $stmt->fetch();

    return $result['total'] > 0;
}

/**
 * Retourne le solde restant de congés d'un employé
 */
function getSoldeConge($mecano) {
    global $db;

    // Exemple : solde annuel fixe 30 jours
    $soldeAnnuel = 30;

    $stmt = $db->prepare("
        SELECT SUM(nb_jours) as totalPris
        FROM conges
        WHERE mecano = :mecano
    ");
    $stmt->execute([':mecano' => $mecano]);
    $result = $stmt->fetch();

    $totalPris = $result['totalPris'] ?? 0;

    return $soldeAnnuel - $totalPris;
}
