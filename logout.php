<?php
// logout.php - Version sécurisée
session_start();

// Vérifier si l'utilisateur était connecté
$was_logged_in = isset($_SESSION['congidGA']);

// Journaliser la déconnexion (optionnel)
if ($was_logged_in && isset($_SESSION['congidGA'])) {
    require("DbConnexion.php");
    $user_id = $_SESSION['congidGA'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $logout_time = date('Y-m-d H:i:s');
    
    // Insérer dans l'historique des déconnexions (si vous avez cette table)
    // $req = "INSERT INTO LogoutHistory (user_id, ip, logout_time) VALUES (?, ?, ?)";
    // mysqli_execute(...);
}

// Détruire la session
session_unset();
session_destroy();

// Redirection
header("Location: index.php" . ($was_logged_in ? "?logout=success" : ""));
exit();
?>