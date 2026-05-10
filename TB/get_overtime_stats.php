<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration de la base de données
$host = 'localhost:3307';
$dbname = 'pointage';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion: ' . $e->getMessage()]);
    exit;
}

// Récupération des données POST
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    $data = $_POST;
}

$action = isset($data['action']) ? $data['action'] : 'get_stats';
$year = isset($data['year']) ? intval($data['year']) : date('Y');
$month = isset($data['month']) && $data['month'] != 'all' ? intval($data['month']) : null;
$department = isset($data['department']) && $data['department'] != 'all' ? $data['department'] : null;

// Action pour obtenir les années disponibles
if ($action == 'get_years') {
    getAvailableYears($pdo);
    exit;
}

// Action pour obtenir les statistiques
getOvertimeStats($pdo, $year, $month, $department);

function getAvailableYears($pdo) {
    try {
        $sql = "SELECT DISTINCT Annee as year FROM journal WHERE Annee IS NOT NULL ORDER BY Annee DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $years = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo json_encode([
            'success' => true,
            'stats' => [
                'availableYears' => $years
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function getOvertimeStats($pdo, $year, $month, $department) {
    try {
        $stats = [];
        
        // Statistiques globales
        $stats['totalStats'] = getTotalStats($pdo, $year, $month, $department);
        
        // Statistiques par type d'heures supplémentaires
        $stats['byType'] = getStatsByType($pdo, $year, $month, $department);
        
        // Statistiques par département
        $stats['byDepartment'] = getStatsByDepartment($pdo, $year, $month, $department);
        
        // Statistiques par grade
        $stats['byGrade'] = getStatsByGrade($pdo, $year, $month, $department);
        
        // Statistiques par mois
        $stats['byMonth'] = getStatsByMonth($pdo, $year, $department);
        
        // Liste des employés avec heures supplémentaires (regroupés)
        $stats['employeesList'] = getEmployeesList($pdo, $year, $month, $department);
        
        echo json_encode([
            'success' => true,
            'stats' => $stats
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}

function getTotalStats($pdo, $year, $month, $department) {
    $sql = "SELECT 
                COUNT(DISTINCT MatriculeSalarie) as total_employees,
                SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) as total_hs125_hours,
                SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) as total_hs150_hours,
                SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) as total_hs175_hours,
                SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) as total_hferies_hours,
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) as total_hs125_amount,
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) as total_hs150_amount,
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) as total_hs175_amount,
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) as total_hferies_amount
            FROM journal 
            WHERE Annee = :year";
    
    $params = [':year' => $year];
    
    if ($month) {
        $sql .= " AND Mois = :month";
        $params[':month'] = $month;
    }
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $totalHours = $result['total_hs125_hours'] + $result['total_hs150_hours'] + $result['total_hs175_hours'] + $result['total_hferies_hours'];
    $totalAmount = $result['total_hs125_amount'] + $result['total_hs150_amount'] + $result['total_hs175_amount'] + $result['total_hferies_amount'];
    $avgHoursPerEmployee = $result['total_employees'] > 0 ? $totalHours / $result['total_employees'] : 0;
    
    return [
        'total_employees' => intval($result['total_employees']),
        'total_hours' => round($totalHours, 2),
        'total_amount' => round($totalAmount, 2),
        'avg_hours_per_employee' => round($avgHoursPerEmployee, 1),
        'hs125_hours' => round($result['total_hs125_hours'], 2),
        'hs150_hours' => round($result['total_hs150_hours'], 2),
        'hs175_hours' => round($result['total_hs175_hours'], 2),
        'hferies_hours' => round($result['total_hferies_hours'], 2),
        'hs125_amount' => round($result['total_hs125_amount'], 2),
        'hs150_amount' => round($result['total_hs150_amount'], 2),
        'hs175_amount' => round($result['total_hs175_amount'], 2),
        'hferies_amount' => round($result['total_hferies_amount'], 2)
    ];
}

function getStatsByType($pdo, $year, $month, $department) {
    $sql = "SELECT 
                SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) as hs125_hours,
                SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) as hs150_hours,
                SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) as hs175_hours,
                SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) as hferies_hours,
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) as hs125_amount,
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) as hs150_amount,
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) as hs175_amount,
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) as hferies_amount
            FROM journal 
            WHERE Annee = :year";
    
    $params = [':year' => $year];
    
    if ($month) {
        $sql .= " AND Mois = :month";
        $params[':month'] = $month;
    }
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'hs125_hours' => round($result['hs125_hours'], 2),
        'hs150_hours' => round($result['hs150_hours'], 2),
        'hs175_hours' => round($result['hs175_hours'], 2),
        'hferies_hours' => round($result['hferies_hours'], 2),
        'hs125_amount' => round($result['hs125_amount'], 2),
        'hs150_amount' => round($result['hs150_amount'], 2),
        'hs175_amount' => round($result['hs175_amount'], 2),
        'hferies_amount' => round($result['hferies_amount'], 2)
    ];
}

function getStatsByDepartment($pdo, $year, $month, $department) {
    $sql = "SELECT 
                Intitule_Service as department,
                COUNT(DISTINCT MatriculeSalarie) as employee_count,
                SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) as hs125_hours,
                SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) as hs150_hours,
                SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) as hs175_hours,
                SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) as hferies_hours,
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) as hs125_amount,
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) as hs150_amount,
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) as hs175_amount,
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) as hferies_amount
            FROM journal 
            WHERE Annee = :year";
    
    $params = [':year' => $year];
    
    if ($month) {
        $sql .= " AND Mois = :month";
        $params[':month'] = $month;
    }
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    $sql .= " GROUP BY Intitule_Service 
              ORDER BY (SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) + 
                        SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) + 
                        SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) +
                        SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2)))) DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $departments = [];
    foreach ($results as $row) {
        $totalHours = $row['hs125_hours'] + $row['hs150_hours'] + $row['hs175_hours'] + $row['hferies_hours'];
        $totalAmount = $row['hs125_amount'] + $row['hs150_amount'] + $row['hs175_amount'] + $row['hferies_amount'];
        
        $departments[] = [
            'department' => $row['department'] ?: 'Non spécifié',
            'employee_count' => intval($row['employee_count']),
            'total_hours' => round($totalHours, 2),
            'total_amount' => round($totalAmount, 2),
            'hs125_hours' => round($row['hs125_hours'], 2),
            'hs150_hours' => round($row['hs150_hours'], 2),
            'hs175_hours' => round($row['hs175_hours'], 2),
            'hferies_hours' => round($row['hferies_hours'], 2),
            'hs125_amount' => round($row['hs125_amount'], 2),
            'hs150_amount' => round($row['hs150_amount'], 2),
            'hs175_amount' => round($row['hs175_amount'], 2),
            'hferies_amount' => round($row['hferies_amount'], 2)
        ];
    }
    
    return $departments;
}

function getStatsByGrade($pdo, $year, $month, $department) {
    $sql = "SELECT 
                Grade as grade,
                COUNT(DISTINCT MatriculeSalarie) as employee_count,
                SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) as hs125_hours,
                SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) as hs150_hours,
                SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) as hs175_hours,
                SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) as hferies_hours,
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) as hs125_amount,
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) as hs150_amount,
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) as hs175_amount,
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) as hferies_amount
            FROM journal 
            WHERE Annee = :year";
    
    $params = [':year' => $year];
    
    if ($month) {
        $sql .= " AND Mois = :month";
        $params[':month'] = $month;
    }
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    $sql .= " GROUP BY Grade 
              ORDER BY (SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) + 
                        SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) + 
                        SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) +
                        SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2)))) DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $grades = [];
    foreach ($results as $row) {
        $totalHours = $row['hs125_hours'] + $row['hs150_hours'] + $row['hs175_hours'] + $row['hferies_hours'];
        $totalAmount = $row['hs125_amount'] + $row['hs150_amount'] + $row['hs175_amount'] + $row['hferies_amount'];
        
        $grades[] = [
            'grade' => $row['grade'] ?: 'Non spécifié',
            'employee_count' => intval($row['employee_count']),
            'total_hours' => round($totalHours, 2),
            'total_amount' => round($totalAmount, 2),
            'hs125_hours' => round($row['hs125_hours'], 2),
            'hs150_hours' => round($row['hs150_hours'], 2),
            'hs175_hours' => round($row['hs175_hours'], 2),
            'hferies_hours' => round($row['hferies_hours'], 2),
            'hs125_amount' => round($row['hs125_amount'], 2),
            'hs150_amount' => round($row['hs150_amount'], 2),
            'hs175_amount' => round($row['hs175_amount'], 2),
            'hferies_amount' => round($row['hferies_amount'], 2)
        ];
    }
    
    return $grades;
}

function getStatsByMonth($pdo, $year, $department) {
    $sql = "SELECT 
                Mois as month,
                COUNT(DISTINCT MatriculeSalarie) as employee_count,
                SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) as hs125_hours,
                SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) as hs150_hours,
                SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) as hs175_hours,
                SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) as hferies_hours,
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) as hs125_amount,
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) as hs150_amount,
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) as hs175_amount,
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) as hferies_amount
            FROM journal 
            WHERE Annee = :year";
    
    $params = [':year' => $year];
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    $sql .= " GROUP BY Mois 
              ORDER BY Mois";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Créer un tableau pour tous les mois (1-12)
    $allMonths = [];
    for ($i = 1; $i <= 12; $i++) {
        $allMonths[$i] = [
            'month' => $i,
            'employee_count' => 0,
            'hs125_hours' => 0,
            'hs150_hours' => 0,
            'hs175_hours' => 0,
            'hferies_hours' => 0,
            'hs125_amount' => 0,
            'hs150_amount' => 0,
            'hs175_amount' => 0,
            'hferies_amount' => 0,
            'total_hours' => 0,
            'total_amount' => 0
        ];
    }
    
    // Remplir avec les données réelles
    foreach ($results as $row) {
        $month = intval($row['month']);
        $totalHours = $row['hs125_hours'] + $row['hs150_hours'] + $row['hs175_hours'] + $row['hferies_hours'];
        $totalAmount = $row['hs125_amount'] + $row['hs150_amount'] + $row['hs175_amount'] + $row['hferies_amount'];
        
        $allMonths[$month] = [
            'month' => $month,
            'employee_count' => intval($row['employee_count']),
            'hs125_hours' => round($row['hs125_hours'], 2),
            'hs150_hours' => round($row['hs150_hours'], 2),
            'hs175_hours' => round($row['hs175_hours'], 2),
            'hferies_hours' => round($row['hferies_hours'], 2),
            'hs125_amount' => round($row['hs125_amount'], 2),
            'hs150_amount' => round($row['hs150_amount'], 2),
            'hs175_amount' => round($row['hs175_amount'], 2),
            'hferies_amount' => round($row['hferies_amount'], 2),
            'total_hours' => round($totalHours, 2),
            'total_amount' => round($totalAmount, 2)
        ];
    }
    
    return array_values($allMonths);
}

function getEmployeesList($pdo, $year, $month, $department) {
    $sql = "SELECT 
            MatriculeSalarie,
            Nom,
            Prenom,
            Intitule_Service,
            Grade,
            SUM(CAST(REPLACE(HS125_Nombre, ',', '.') AS DECIMAL(10,2))) AS HS125_Nombre,
            SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) AS HS125,
            SUM(CAST(REPLACE(HS150_Nombre, ',', '.') AS DECIMAL(10,2))) AS HS150_Nombre,
            SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) AS HS150,
            SUM(CAST(REPLACE(HS175_Nombre, ',', '.') AS DECIMAL(10,2))) AS HS175_Nombre,
            SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) AS HS175,
            SUM(CAST(REPLACE(HFERIES_Nombre, ',', '.') AS DECIMAL(10,2))) AS HFERIES_Nombre,
            SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2))) AS HFERIES,

            (
                SUM(CAST(REPLACE(HS125, ',', '.') AS DECIMAL(10,2))) +
                SUM(CAST(REPLACE(HS150, ',', '.') AS DECIMAL(10,2))) +
                SUM(CAST(REPLACE(HS175, ',', '.') AS DECIMAL(10,2))) +
                SUM(CAST(REPLACE(HFERIES, ',', '.') AS DECIMAL(10,2)))
            ) AS Total_Montant

        FROM journal 
        WHERE Annee = :year";

    
    $params = [':year' => $year];
    
    if ($month) {
        $sql .= " AND Mois = :month";
        $params[':month'] = $month;
    }
    
    if ($department) {
        $sql .= " AND Intitule_Service = :department";
        $params[':department'] = $department;
    }
    
    // Ajouter une condition pour exclure les enregistrements sans heures supplémentaires
    $sql .= " AND (HS125_Nombre != '0' OR HS150_Nombre != '0' OR HS175_Nombre != '0' OR HFERIES_Nombre != '0')";
    
    // Regrouper par employé pour éviter les doublons
    $sql .= " GROUP BY MatriculeSalarie, Nom, Prenom, Intitule_Service, Grade";
    
    $sql .= " ORDER BY Total_Montant DESC";

    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $employees = [];
    foreach ($results as $row) {
        $totalHours = $row['HS125_Nombre'] + $row['HS150_Nombre'] + $row['HS175_Nombre'] + $row['HFERIES_Nombre'];
        $totalAmount = $row['HS125'] + $row['HS150'] + $row['HS175'] + $row['HFERIES'];
        
        $employees[] = [
            'MatriculeSalarie' => $row['MatriculeSalarie'],
            'Nom' => $row['Nom'],
            'Prenom' => $row['Prenom'],
            'Intitule_Service' => $row['Intitule_Service'],
            'Grade' => $row['Grade'],
            'HS125_Nombre' => round($row['HS125_Nombre'], 2),
            'HS125' => round($row['HS125'], 2),
            'HS150_Nombre' => round($row['HS150_Nombre'], 2),
            'HS150' => round($row['HS150'], 2),
            'HS175_Nombre' => round($row['HS175_Nombre'], 2),
            'HS175' => round($row['HS175'], 2),
            'HFERIES_Nombre' => round($row['HFERIES_Nombre'], 2),
            'HFERIES' => round($row['HFERIES'], 2),
            'Total_Heures' => round($totalHours, 2),
            'Total_Montant' => round($totalAmount, 2)
        ];
    }
    
    return $employees;
}
?>