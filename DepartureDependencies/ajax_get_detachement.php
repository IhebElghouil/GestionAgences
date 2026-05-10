<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

// Get document counts
$doc_count_query = "SELECT detachement_id, COUNT(*) as doc_count FROM detachement_documents GROUP BY detachement_id";
$doc_count_result = mysqli_query($connection, $doc_count_query);
$doc_counts = [];
while ($dc = mysqli_fetch_assoc($doc_count_result)) {
    $doc_counts[$dc['detachement_id']] = $dc['doc_count'];
}

$query = "SELECT id, mecano, nomprenom, affectation, source, situation, datedetachement, periode, 
                 renouvellemnt1, renouvellemnt2, renouvellemnt3, dossier, observations, statut,
                 DATE_ADD(datedetachement, INTERVAL (COALESCE(NULLIF(TRIM(periode), ''), 0) + 
                 COALESCE(NULLIF(TRIM(renouvellemnt1), ''), 0) + 
                 COALESCE(NULLIF(TRIM(renouvellemnt2), ''), 0) + 
                 COALESCE(NULLIF(TRIM(renouvellemnt3), ''), 0)) YEAR) AS date_fin
          FROM detachement 
          ORDER BY CASE WHEN statut = 1 THEN 1 ELSE 0 END";

$result = mysqli_query($connection, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $formattedDate = !empty($row['datedetachement']) ? date('Y-m-d', strtotime($row['datedetachement'])) : '';
    $dateFinObj = new DateTime($row['date_fin']);
    $aujourdhui = new DateTime();
    $interval = $aujourdhui->diff($dateFinObj);
    $moisRestants = ($interval->y * 12) + $interval->m;
    
    if ($row['statut'] == 1) {
        $monthsClass = 'months-red';
        $monthsText = 'متابعة موقوفة';
        $monthsLeftCategory = 'stopped';
    } else {
        if ($dateFinObj < $aujourdhui) {
            $monthsClass = 'months-red';
            $monthsText = 'منتهي';
            $monthsLeftCategory = 'expired';
        } elseif ($moisRestants < 3) {
            $monthsClass = 'months-yellow';
            $monthsText = $moisRestants . ' شهر';
            $monthsLeftCategory = 'less3';
        } elseif ($moisRestants <= 6) {
            $monthsClass = 'months-yellow';
            $monthsText = $moisRestants . ' شهر';
            $monthsLeftCategory = '3to6';
        } else {
            $monthsClass = 'months-green';
            $monthsText = $moisRestants . ' شهر';
            $monthsLeftCategory = 'more6';
        }
    }
    
    $row['datedetachement'] = $formattedDate;
    $row['monthsClass'] = $monthsClass;
    $row['monthsText'] = $monthsText;
    $row['monthsLeftCategory'] = $monthsLeftCategory;
    $row['docCount'] = isset($doc_counts[$row['id']]) ? $doc_counts[$row['id']] : 0;
    
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);
?>