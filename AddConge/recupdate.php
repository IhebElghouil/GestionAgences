<?php
require_once(__DIR__ . "/DbConnexion.php");
$sqlConge = "SELECT DISTINCT deb, fin, annee FROM congenational";
$stmtConge = $conn->prepare($sqlConge);
$stmtConge->execute();
$resultConge = $stmtConge->get_result();

$allJoursFeries = array();

while ($r = $resultConge->fetch_assoc()) {
    $datedebut = str_pad($r['deb'], 4, '0', STR_PAD_LEFT);
    $datefin = str_pad($r['fin'], 4, '0', STR_PAD_LEFT);
    $annee = $r['annee'];

    $date1 = DateTime::createFromFormat('Ymd', $annee . $datedebut);
    $date2 = DateTime::createFromFormat('Ymd', $annee . $datefin);

    if (!$date1 || !$date2) {
        echo "Format de date invalide pour les dates: $datedebut ou $datefin de l'année $annee.";
        continue;
    }

    $joursFeries = array();
    if ($date1 < $date2) {
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($date1, $interval, $date2->modify('+1 day'));

        foreach ($period as $date) {
            $joursFeries[] = $date->format('Y-m-d');
        }
    } else {
        $joursFeries[] = $date1->format('Y-m-d');
    }

    $allJoursFeries = array_merge($allJoursFeries, $joursFeries);
}
header('Content-Type: application/json');
echo json_encode($allJoursFeries);


?>