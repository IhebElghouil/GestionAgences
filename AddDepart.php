 <?php
session_start();
require('connection.php');
?>

<?php

$mecano = $_GET['mecano'];
$daterecrutement = $_GET['daterecrutement'];
$datedepart = $_GET['datedepart'];
$dateretraite = $_GET['dateretraite'];
$Cministere = $_GET['ministere'];
$annee = $_GET['anneedepart'];
$observation = $_GET['cause'];

// Déterminer la valeur de contrastage en fonction de la cause
$contrastage_value = 4; // Valeur par défaut

if ($_GET['cause'] == "إلحاق") {
    $contrastage_value = 5;
} elseif ($_GET['cause'] == "إحالة على عدم المباشرة") {
    $contrastage_value = 6;
}

// Vérifier si le mécanicien existe déjà dans la table depart
$verif_query = mysqli_query($connection, "SELECT mecano FROM depart WHERE mecano = '".$mecano."'");

if (mysqli_num_rows($verif_query) > 0) {
    // Le mécanicien existe déjà
    echo "<script>
        alert('هذا العون موجود مسبقاً في جدول المغادرين');
        window.location.href = 'http://192.168.1.20:8081/GestionAgences/c_retraite.php';
    </script>";
} else {
    // Le mécanicien n'existe pas, procéder à l'insertion
    $reqinsert = mysqli_query($connection, "INSERT INTO depart VALUES('','".$mecano."','".$daterecrutement."','".$datedepart."','".$dateretraite."','".$Cministere."','".$annee."','".$observation."')");
    
    // Mise à jour de stuf avec la valeur appropriée
    $reqUpdate = mysqli_query($connection, "UPDATE stuf SET contrastage = ".$contrastage_value." WHERE mecano = '".$mecano."'");
    
    if ($reqinsert && $reqUpdate) {
        echo "<script>
            alert('تمّت الإضافة بنجاح');
            window.location.href = 'http://192.168.1.20:8081/GestionAgences/c_retraite.php';
        </script>";
    } else {
        echo "MySQL error ".mysqli_errno($connection).": ".mysqli_error($connection)."<br>";
    }
}
?>