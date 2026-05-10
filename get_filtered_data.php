<?php
require 'Dbconnexion.php';

$regions = isset($_POST['regions']) ? $_POST['regions'] : [];
$services = isset($_POST['services']) ? $_POST['services'] : [];
$years = isset($_POST['years']) ? $_POST['years'] : [];
$codepers = isset($_POST['codepers']) ? $_POST['codepers'] : [];

// Build WHERE clause
$where = [];
$params = [];
$types = '';

if (!empty($regions)) {
    $placeholders = implode(',', array_fill(0, count($regions), '?'));
    $where[] = "s.dep IN ($placeholders)";
    $params = array_merge($params, $regions);
    $types .= str_repeat('i', count($regions));
}

if (!empty($services)) {
    $placeholders = implode(',', array_fill(0, count($services), '?'));
    $where[] = "s.idservice IN ($placeholders)";
    $params = array_merge($params, $services);
    $types .= str_repeat('i', count($services));
}

if (!empty($years)) {
    $placeholders = implode(',', array_fill(0, count($years), '?'));
    $where[] = "YEAR(s.daterec) IN ($placeholders)";
    $params = array_merge($params, $years);
    $types .= str_repeat('i', count($years));
}

if (!empty($codepers)) {
    $placeholders = implode(',', array_fill(0, count($codepers), '?'));
    $where[] = "s.codepers IN ($placeholders)";
    $params = array_merge($params, $codepers);
    $types .= str_repeat('s', count($codepers));
}

$where_clause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// KPI
$sql_kpi = "SELECT COUNT(*) AS total, SUM(CASE WHEN etat = 1 THEN 1 ELSE 0 END) AS actif, SUM(CASE WHEN etat = 0 THEN 1 ELSE 0 END) AS inactif FROM stuf s $where_clause";
$stmt = $conn->prepare($sql_kpi);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$kpi = $stmt->get_result()->fetch_assoc();

// Regions
$sql_region = "SELECT d.depar AS region, COUNT(s.mecano) AS total FROM stuf s LEFT JOIN dep d ON s.dep = d.id $where_clause GROUP BY d.depar ORDER BY total DESC";
$stmt = $conn->prepare($sql_region);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$regions_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Recruitment evolution
$sql_recruitment = "SELECT YEAR(daterec) as annee, COUNT(*) as total FROM stuf s $where_clause AND daterec IS NOT NULL GROUP BY YEAR(daterec) ORDER BY annee";
$stmt = $conn->prepare($sql_recruitment);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$recruitment_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Age pyramid
$sql_age = "SELECT CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 20 AND 29 THEN '20-29 ans' WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 30 AND 39 THEN '30-39 ans' WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 40 AND 49 THEN '40-49 ans' WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 50 AND 59 THEN '50-59 ans' ELSE '60+ ans' END as tranche_age, SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes, SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes, COUNT(*) as total FROM stuf s $where_clause AND daten IS NOT NULL GROUP BY tranche_age ORDER BY FIELD(tranche_age, '20-29 ans', '30-39 ans', '40-49 ans', '50-59 ans', '60+ ans')";
$stmt = $conn->prepare($sql_age);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$age_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Combined
$sql_combined = "SELECT d.depar AS region, SUM(CASE WHEN s.etat = 1 THEN 1 ELSE 0 END) AS actif, SUM(CASE WHEN s.etat = 0 THEN 1 ELSE 0 END) AS inactif, COUNT(s.mecano) AS total FROM stuf s LEFT JOIN dep d ON s.dep = d.id $where_clause GROUP BY d.depar ORDER BY d.depar";
$stmt = $conn->prepare($sql_combined);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$combined_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Départs combinés
$sql_departs = "
SELECT 
    annee,
    SUM(passes) as passes,
    SUM(futurs) as futurs
FROM (
    SELECT 
        YEAR(dateretraite) as annee,
        COUNT(*) as passes,
        0 as futurs
    FROM depart
    WHERE dateretraite IS NOT NULL
    GROUP BY YEAR(dateretraite)
    
    UNION ALL
    
    SELECT 
        YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) as annee,
        0 as passes,
        COUNT(*) as futurs
    FROM stuf s
    $where_clause
        AND daten IS NOT NULL 
        AND etat = 1
        AND YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) >= YEAR(CURDATE()) - 1
    GROUP BY YEAR(DATE_ADD(daten, INTERVAL 60 YEAR))
) combined
GROUP BY annee
ORDER BY annee
";
$stmt = $conn->prepare($sql_departs);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$departs_data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Prévisions 5 ans
$previsions = 0;
foreach($departs_data as $row) {
    if($row['annee'] <= date('Y') + 5 && $row['annee'] >= date('Y')) {
        $previsions += $row['futurs'];
    }
}

echo json_encode([
    'kpi' => $kpi,
    'regions' => $regions_data,
    'recruitment' => $recruitment_data,
    'agePyramid' => $age_data,
    'combined' => $combined_data,
    'departsCombined' => $departs_data,
    'previsions' => $previsions
]);
?>