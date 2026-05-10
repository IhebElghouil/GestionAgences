<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح']);
    exit;
}

$id = $_POST['id'] ?? '';
$mecano = $_POST['mecano'] ?? '';
$nomprenom = $_POST['nomprenom'] ?? '';
$affectation = $_POST['affectation'] ?? '';
$source = $_POST['source'] ?? '';
$situation = $_POST['situation'] ?? '';
$datedetachement = $_POST['datedetachement'] ?? '';
$periode = $_POST['periode'] ?? '';
$renouvellemnt1 = $_POST['renouvellemnt1'] ?? 0;
$renouvellemnt2 = $_POST['renouvellemnt2'] ?? 0;
$renouvellemnt3 = $_POST['renouvellemnt3'] ?? 0;
$dossier = $_POST['dossier'] ?? '';
$observations = $_POST['observations'] ?? '';

if (empty($id)) {
    // Insert
    $query = "INSERT INTO detachement (mecano, nomprenom, affectation, source, situation, datedetachement, periode, renouvellemnt1, renouvellemnt2, renouvellemnt3, dossier, observations, statut) 
              VALUES ('$mecano', '$nomprenom', '$affectation', '$source', '$situation', '$datedetachement', '$periode', '$renouvellemnt1', '$renouvellemnt2', '$renouvellemnt3', '$dossier', '$observations', 0)";
} else {
    // Update
    $query = "UPDATE detachement SET 
              mecano='$mecano', nomprenom='$nomprenom', affectation='$affectation', source='$source', 
              situation='$situation', datedetachement='$datedetachement', periode='$periode', 
              renouvellemnt1='$renouvellemnt1', renouvellemnt2='$renouvellemnt2', renouvellemnt3='$renouvellemnt3', 
              dossier='$dossier', observations='$observations' 
              WHERE id='$id'";
}

if (mysqli_query($connection, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => mysqli_error($connection)]);
}
?>