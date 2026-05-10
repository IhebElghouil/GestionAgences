<?php
/*$conn = mysqli_connect("127.0.0.1","root","","pointage");

$DateDebut=$_GET['DateDebut'];
$DateFin=$_GET['DateFin'];
$mecano=$_GET['mecano'];

$ress1=mysql_query("SELECT * FROM conge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'");
$ress2=mysql_query("SELECT * FROM autreconge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'");

$sqlConge = "SELECT * FROM conge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'";
$resultConge = $conn->query($sqlConge);

$sqlAutreConge = "SELECT * FROM autreconge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'";
$resultAutreConge = $conn->query($sqlAutreConge);

if (($resultConge->num_rows > 0) || ($resultAutreConge->num_rows > 0)) {

echo "0";

}
else
{
	echo "1";
}
*/











// Connexion à la base de données avec gestion des erreurs
require('DbConnexion.php');

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupérer les paramètres d'entrée
$DateDebut = isset($_GET['DateDebut']) ? $_GET['DateDebut'] : null;
$DateFin = isset($_GET['DateFin']) ? $_GET['DateFin'] : null;
$mecano = isset($_GET['mecano']) ? $_GET['mecano'] : null;

if (!$DateDebut || !$DateFin || !$mecano) {
    echo "Paramètres manquants.";
    exit;
}

// Validation des dates
if (!DateTime::createFromFormat('Y-m-d', $DateDebut) || !DateTime::createFromFormat('Y-m-d', $DateFin)) {
    echo "Format de date invalide.";
    exit;
}

// Convertir les dates en objets DateTime
$dateDebut = new DateTime($DateDebut);
$dateFin = new DateTime($DateFin);

// Requêtes préparées pour éviter les injections SQL
$sqlConge = "SELECT * FROM conge WHERE datedebut < ? AND datefin > ? AND mecano = ?";
//$sqlAutreConge = "SELECT * FROM autreconge WHERE datedebut < ? AND datefin > ? AND mecano = ?";

$sqlConge = "SELECT * FROM conge WHERE mecano = ? AND (
            (datedebut BETWEEN ? AND ?) OR
            (datefin BETWEEN ? AND ?) OR
            (? BETWEEN datedebut AND datefin)
        )";
		
$sqlAutreConge = "SELECT * FROM autreconge WHERE mecano = ? AND (
            (datedebut BETWEEN ? AND ?) OR
            (datefin BETWEEN ? AND ?) OR
            (? BETWEEN datedebut AND datefin)
        )";


// Préparer et lier les paramètres pour la première requête
$stmt1 = $conn->prepare($sqlConge);
$stmt1->bind_param("ssssss", $mecano, $DateDebut, $DateFin, $DateDebut, $DateFin, $DateDebut);

// Exécuter la première requête
$stmt1->execute();
$resultConge = $stmt1->get_result();

// Préparer et lier les paramètres pour la deuxième requête
$stmt2 = $conn->prepare($sqlAutreConge);
$stmt2->bind_param("ssssss", $mecano, $DateDebut, $DateFin, $DateDebut, $DateFin, $DateDebut);

// Exécuter la deuxième requête
$stmt2->execute();
$resultAutreConge = $stmt2->get_result();

// Vérifier si des résultats sont retournés
if ($resultConge->num_rows > 0 || $resultAutreConge->num_rows > 0) {
    echo "0"; // Période de congé déjà enregistrée
} else {
    echo "1"; // Pas de chevauchement, période valide
}

// Fermer la connexion
$stmt1->close();
$stmt2->close();
$conn->close();




?>
