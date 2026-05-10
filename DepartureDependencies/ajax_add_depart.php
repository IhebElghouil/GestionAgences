<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$mecano = mysqli_real_escape_string($connection, $_POST['mecano'] ?? '');
$dateNaissance = mysqli_real_escape_string($connection, $_POST['daterecrutement'] ?? '');
$dateRetraite = mysqli_real_escape_string($connection, $_POST['dateretraite'] ?? '');
$datedepart = mysqli_real_escape_string($connection, $_POST['datedepart'] ?? '');
$ministere = intval($_POST['ministere'] ?? 0);
$anneedepart = mysqli_real_escape_string($connection, $_POST['anneedepart'] ?? '');
$cause = mysqli_real_escape_string($connection, $_POST['cause'] ?? '');

if (empty($mecano) || empty($datedepart) || empty($anneedepart) || empty($cause)) {
    echo json_encode(['success' => false, 'message' => 'جميع الحقول مطلوبة']);
    exit;
}

// Vérification si le mecano avec datedepart existe déjà
$checkQuery = "SELECT id FROM depart WHERE mecano = '$mecano'";
$checkResult = mysqli_query($connection, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    echo json_encode(['success' => false, 'message' => 'هذا الموظف مسجل كمغادر مسبقاً']);
    exit;
}

// Démarrer la transaction
mysqli_begin_transaction($connection);

try {
    // Insertion dans depart
    $insertQuery = "INSERT INTO depart (mecano, datenaissance ,dateretraite, datedepart, cministere, annee, observation) 
                    VALUES ('$mecano','$dateNaissance', '$datedepart','$dateretraite', '$ministere', '$anneedepart', '$cause')";
    
    if (!mysqli_query($connection, $insertQuery)) {
        throw new Exception(mysqli_error($connection));
    }
    
    // Mise à jour de stuf (contrastage = 4)
    $updateQuery = "UPDATE stuf SET contrastage = 4 WHERE mecano = '$mecano'";
    
    if (!mysqli_query($connection, $updateQuery)) {
        throw new Exception(mysqli_error($connection));
    }
    
    mysqli_commit($connection);
    echo json_encode(['success' => true, 'message' => 'تمت الإضافة بنجاح']);
    
} catch (Exception $e) {
    mysqli_rollback($connection);
    echo json_encode(['success' => false, 'message' => 'خطأ: ' . $e->getMessage()]);
}

mysqli_close($connection);
?>