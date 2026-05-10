<?php
include('DbConnexion.php');
header('Content-Type: application/json');
$mec = $_POST['mecano'] ?? '';
if (!$mec) {
  echo json_encode([]);
  exit;
}
$stmt = $conn->prepare("SELECT nom FROM stuf WHERE mecano = ?");
$stmt->bind_param('s', $mec);
$stmt->execute();
$stmt->bind_result($np);
if ($stmt->fetch()) {
  echo json_encode(['nomprenom' => $np]);
} else {
  echo json_encode([]);
}
$stmt->close();
$conn->close();