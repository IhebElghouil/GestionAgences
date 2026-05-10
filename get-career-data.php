<?php
session_start();
require('connection.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);
    
    $query = "SELECT c.*, 
                     t1.libellet as ancienrang_libellet,
                     t2.libellet as nouveaurang_libellet
              FROM carriere c
              LEFT JOIN titres t1 ON c.ancienrang_id = t1.id
              LEFT JOIN titres t2 ON c.nouveaurang_id = t2.id
              WHERE c.id = '$id'";
    $result = mysqli_query($connection, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        // Formatage des données pour le JavaScript
        $response = [
            'success' => true,
            'id' => $data['id'],
            'decision_number' => $data['decision_number'],
            'issue_date' => $data['issue_date'],
            'ancienrang' => $data['ancienrang_libellet'] ?? '',
            'ancienrang_id' => $data['ancienrang_id'] ?? '',
            'nouveaurang' => $data['nouveaurang_libellet'] ?? '',
            'nouveaurang_id' => $data['nouveaurang_id'] ?? '',
            'ancienneechelle' => $data['ancienneechelle'],
            'nouvelechelle' => $data['nouvelechelle'],
            'anciennegrade' => $data['anciennegrade'],
            'nouveaugrade' => $data['nouveaugrade'],
            'notes' => $data['notes'],
            'dateeffet' => $data['dateeffet'],
            'commission' => $data['commission']
        ];
        
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enregistrement non trouvé']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
}
?>