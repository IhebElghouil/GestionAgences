<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$isAdmin = ($_SESSION['departement'] === "admin");
$departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
$current_year = date('Y');

// Total departs
$query_depart = $isAdmin ? 
    "SELECT COUNT(*) as total, SUM(CASE WHEN observation = 'تقاعد' THEN 1 ELSE 0 END) as retired, SUM(CASE WHEN annee = $current_year THEN 1 ELSE 0 END) as current_year FROM depart" :
    "SELECT COUNT(*) as total, SUM(CASE WHEN observation = 'تقاعد' THEN 1 ELSE 0 END) as retired, SUM(CASE WHEN annee = $current_year THEN 1 ELSE 0 END) as current_year FROM depart LEFT JOIN stuf ON stuf.mecano = depart.mecano WHERE stuf.dep IN (" . implode(',', $departements) . ")";

$result = mysqli_query($connection, $query_depart);
$row = mysqli_fetch_assoc($result);
$total_departs = $row['total'] ?? 0;
$retired = $row['retired'] ?? 0;
$current_year_departs = $row['current_year'] ?? 0;

// Detachement stats
$query_out = "SELECT COUNT(*) as count FROM detachement WHERE situation = 'ملحق خارج الشركة' AND statut = 0";
$result_out = mysqli_query($connection, $query_out);
$total_detachements_out = mysqli_fetch_assoc($result_out)['count'] ?? 0;

$query_in = "SELECT COUNT(*) as count FROM detachement WHERE situation = 'ملحق لدى الشركة' AND statut = 0";
$result_in = mysqli_query($connection, $query_in);
$total_detachements_in = mysqli_fetch_assoc($result_in)['count'] ?? 0;

$query_nonservice = "SELECT COUNT(*) as count FROM detachement WHERE situation = 'إحالة على عدم المباشرة' AND statut = 0";
$result_nonservice = mysqli_query($connection, $query_nonservice);
$total_nonservice = mysqli_fetch_assoc($result_nonservice)['count'] ?? 0;

echo json_encode([
    'success' => true,
    'total_departs' => $total_departs,
    'retired' => $retired,
    'current_year' => $current_year_departs,
    'total_detachements_out' => $total_detachements_out,
    'total_detachements_in' => $total_detachements_in,
    'total_nonservice' => $total_nonservice
]);
?>