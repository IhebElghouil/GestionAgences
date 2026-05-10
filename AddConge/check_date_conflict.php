<?php
require_once(__DIR__ . "/DbConnexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_conflict'])) {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $exclude_id = intval($_POST['exclude_id'] ?? 0);
    $year = intval($_POST['year'] ?? date('Y'));
    
    if (empty($start_date) || empty($end_date)) {
        echo json_encode(['conflict' => false]);
        exit;
    }
    
    // Convert dates to database format
    $start_parts = explode('-', $start_date);
    $end_parts = explode('-', $end_date);
    
    $start_stored = $start_parts[1] . $start_parts[2];
    $end_stored = $end_parts[1] . $end_parts[2];
    
    // Check for overlapping dates
    $query = "SELECT id FROM congenational 
              WHERE annee = ? 
              AND ((deb BETWEEN ? AND ?) 
                OR (fin BETWEEN ? AND ?) 
                OR (? BETWEEN deb AND fin) 
                OR (? BETWEEN deb AND fin))
              AND id != ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssssi", 
        $year, $start_stored, $end_stored, 
        $start_stored, $end_stored, 
        $start_stored, $end_stored, 
        $exclude_id);
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    $conflict = mysqli_stmt_num_rows($stmt) > 0;
    
    mysqli_stmt_close($stmt);
    
    echo json_encode(['conflict' => $conflict]);
    exit;
}

echo json_encode(['conflict' => false]);
?>