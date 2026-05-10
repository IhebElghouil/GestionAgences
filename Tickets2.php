<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>متابعة الحضور</title>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
    #loading_indicator {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;
        border: 10px solid grey;
        border-radius: 50%;
        border-top: 10px solid blue;
        width: 100px;
        height: 100px;
        animation: spinIndicator 1s linear infinite;
    }
    @keyframes spinIndicator {
        100% {
            transform: rotate(360deg);
        }
    }
    
    /* Style pour le tableau récapitulatif */
    .recap-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 12px;
    }
    .recap-table th, .recap-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }
    .recap-table th {
        background-color: #333;
        color: #FFF;
        font-weight: bold;
    }
    .recap-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .recap-table tr:hover {
        background-color: #e6e6e6;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .header h2 {
        margin: 0;
        padding: 10px;
        background-color: #333;
        color: #FFF;
    }
    .base-mois {
        background-color: #dff0d8;
        font-weight: bold;
    }
    .summary-row {
        background-color: #333 !important;
        color: #FFF;
        font-weight: bold;
    }
    .filter-container {
        margin: 15px 0;
        padding: 10px;
        background-color: #f5f5f5;
        border-radius: 5px;
    }
    .filter-group {
        margin-right: 20px;
        display: inline-block;
    }
    label {
        font-weight: bold;
        margin-right: 5px;
    }
    select, input {
        padding: 5px;
        border-radius: 3px;
        border: 1px solid #ddd;
    }
    button {
        padding: 5px 15px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    button:hover {
        background-color: #45a049;
    }
    @media screen and (max-width: 768px) {
        .filter-group {
            display: block;
            margin-bottom: 10px;
        }
        .recap-table {
            font-size: 10px;
        }
        .recap-table th, .recap-table td {
            padding: 4px;
        }
    }
</style>
<style>
        /* Centrage du toast en haut */
        .toast-container-top-center {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1060;
        }
    </style>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body onload="showToast()">
<div class="toast-container-top-center">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                ✅ يرجى رفع الحضور قبل كل شيء حتى تكون المعطيات صحيحة !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    </div>
</div>

<!-- Bootstrap JS + Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script pour afficher le toast -->
<script>
    function showToast() {
        const toastEl = document.getElementById('liveToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
</script>

<div id="loading_indicator"></div>
<div class="header">
    <h2><?php echo htmlspecialchars($_GET['annee'] ?? '').'/'.htmlspecialchars($_GET['mois'] ?? ''); ?>متابعة جدول حضور شهر</h2>
</div>

<div class="filter-container">
    <form method="get" action="">
        <div class="filter-group">
            <label for="region">Région:</label>
            <select name="region" id="region">
                <option value="gabes" <?php echo (($_GET['region'] ?? '') == 'gabes' ? 'selected' : ''); ?>>Gabès</option>
                <option value="kebili" <?php echo (($_GET['region'] ?? '') == 'kebili' ? 'selected' : ''); ?>>Kebili</option>
                <option value="parcgabes" <?php echo (($_GET['region'] ?? '') == 'parcgabes' ? 'selected' : ''); ?>>Parc Gabès</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="service">Service:</label>
            <select name="service" id="service">
                <option value="--------">--------</option>
                <?php
                $db_host = 'localhost:3307';
                $db_user = "root";
                $db_pass = "";
                $db_name = "pointage";
                $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
                
                $services = mysqli_query($conn, "SELECT id, libellet FROM service ORDER BY libellet");
                while($service = mysqli_fetch_assoc($services)) {
                    $selected = (($_GET['service'] ?? '') == $service['id'] ? 'selected' : '');
                    echo "<option value='{$service['id']}' $selected>{$service['libellet']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="mois">Mois:</label>
            <select name="mois" id="mois">
                <?php
				if (isset($_GET['mois']))
					{
$currentMonth =$_GET['mois'];
}else
	{
$currentMonth = date('n');
}
    
				
                for($i = 1; $i <= 12; $i++) {
                    $selected = (($currentMonth ?? '') == $i ? 'selected' : '');
                    echo "<option value='$i' $selected>".$i."</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="annee">Année:</label>
            <select name="annee" id="annee">
                <?php
				if (isset($_GET['annee']))
					{
$currentYear =$_GET['annee'];
}else
	{
$currentYear = date('Y');
}
                
                for($i = $currentYear - 5; $i <= $currentYear + 10; $i++) {
                    $selected = (($currentYear ?? '') == $i ? 'selected' : '');
                    echo "<option value='$i' $selected>$i</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="date_debut">Date Début:</label>
            <input type="date" name="date_debut" id="date_debut" value="<?php echo htmlspecialchars($_GET['date_debut'] ?? ''); ?>">
        </div>
        
        <div class="filter-group">
            <label for="date_fin">Date Fin:</label>
            <input type="date" name="date_fin" id="date_fin" value="<?php echo htmlspecialchars($_GET['date_fin'] ?? ''); ?>">
        </div>
        
        <button type="submit">Filtrer</button>
        <button type="button" id="resetFilters">Réinitialiser</button>
    </form>
</div>

<?php
// Initialize summary totals
$total_base = 0;
$total_absence = 0;
$total_conge = 0;
$total_at = 0;
$total_maladie = 0;
$total_sanction = 0;
$total_ferie = 0;
$total_repos = 0;
$total_autre=0;
$total_sanssolde=0;
$employee_count = 0;
$i=0;

if(isset($_GET['annee']) && isset($_GET['mois'])) {
    // Récupérer l'année en cours
    $reqgetannee = mysqli_query($conn, "SELECT * FROM annee");
    if($rgetannee = mysqli_fetch_row($reqgetannee)){
        $getannee = $rgetannee[0];
    }

    // Détermination de la période
    $d1 = $_GET['annee']."-".$_GET['mois']."-01"; // Premier jour du mois
    $d2 = date("Y-m-t", strtotime($d1)); // Dernier jour du mois

    // Surcharge par les dates personnalisées si fournies
    if(isset($_GET['date_debut']) && !empty($_GET['date_debut'])) {
        $d1 = $_GET['date_debut'];
    }
    if(isset($_GET['date_fin']) && !empty($_GET['date_fin'])) {
        $d2 = $_GET['date_fin'];
    }
    $nbj = date("t", strtotime($d1));

    // Requête pour récupérer les employés selon la région ou le service
    if (($_GET['service'] ?? '') == "--------"){
        $region = $_GET['region'] ?? '';
        
        if ($region == 'gabes' && $_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'gabes' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'kebili' && $_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice IN (15,16) AND SV.id IN (15,16) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'kebili' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice IN (15,16) AND SV.id IN (15,16) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'parcgabes' && $_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice IN (14,4,45,12) AND SV.id IN (14,4,45,12) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'parcgabes' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice IN (14,4,45,12) AND SV.id IN (14,4,45,12) ORDER BY S.idservice, S.dep, S.mecano");
        }
    }
    else {
        $serv = $_GET['service'] ?? '';
        if($_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice='".$serv."' ORDER BY S.idservice, S.dep, S.mecano");
        }
        else {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1 AND S.idservice='".$serv."' ORDER BY S.idservice, S.dep, S.mecano");
        }
    }

    // Afficher le tableau récapitulatif
    echo '<table id="recapTable" class="recap-table display nowrap" style="width:100%">';
    echo '<thead><tr>
            <th>N°</th>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Statut</th>
            <th>Base du mois</th>
            <th>∑ Absences</th>
            <th>∑ C.A</th>
            <th>∑ AT</th>
            <th>∑ Maladie</th>
            <th>∑ Susp</th>
			<th>∑ Autres</th>
			<th>∑ S.Solde</th>
            <th>J.Fériés Trav</th>
            <th>J.Repos Trav</th>
			<th>H.Nuit</th>
			<th>H.S 25%</th>
			<th>H.S 50%</th>
			<th>H.S 100%</th>
			<th>Panier</th>
          </tr></thead><tbody>';

    while($r1 = mysqli_fetch_row($req1)) {
        $nbabsence = 0;
        $nbconge = 0;
        $nbautrecongeAT = 0;
        $nbautrecongeMaladie = 0;
        $nbautrecongeSanction = 0;
        $nbFerieTravailles = 0;
        $nbReposTravailles = 0;
        
        // [DAY OF WEEK TRANSLATION CODE REMAINS THE SAME]
$jrepos = "";
        $jreposTraduit = "";
        if ($r1[5] == 0) {
            $jrepos = "Sunday";
            $jreposTraduit = "الأحد";
        }
        elseif ($r1[5] == 1) {
            $jrepos = "Monday";
            $jreposTraduit = "الإثنين";
        }
        elseif ($r1[5] == 2) {
            $jrepos = "Tuesday";
            $jreposTraduit = "الثلاثاء";
        }
        elseif ($r1[5] == 3) {
            $jrepos = "Wednesday";
            $jreposTraduit = "الأربعاء";
        }
        elseif ($r1[5] == 4) {
            $jrepos = "Thursday";
            $jreposTraduit = "الخميس";
        }
        elseif ($r1[5] == 5) {
            $jrepos = "Friday";
            $jreposTraduit = "الجمعة";
        }
        elseif ($r1[5] == 6) {
            $jrepos = "Saturday";
            $jreposTraduit = "السبت";
        }
        elseif ($r1[5] == 10) {
            $jrepos = "SatSun";
            $jreposTraduit = "السبت و الأحد";
        }
        
        // Calculate days in date range
        $start = new DateTime($d1);
        $end = new DateTime($d2);
        $end = $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        
        // D'abord compter tous les congés pour la période
     $reqTousConges = mysqli_query($conn, "
    SELECT 'annuel' AS type, DATEDIFF(LEAST(datefin, '".$d2."'), GREATEST(datedebut, '".$d1."')) + 1 AS jours
    FROM conge 
    WHERE mecano='".$r1[0]."' AND datedebut <= '".$d2."' AND datefin >= '".$d1."'
    UNION ALL
    SELECT 
    CASE 
        WHEN type = 5 THEN 'maladie'
        WHEN type = 6 THEN 'at'
        WHEN type = 10 THEN 'sanction'
        WHEN type = 9 THEN 'sanssolde'
        WHEN type IN (1, 2, 3, 4, 7, 8, 11, 12, 13, 14) THEN 'autre'
    END AS type_label,
    DATEDIFF(LEAST(datefin, '$d2'), GREATEST(datedebut, '$d1')) + 1 AS jours
FROM autreconge
WHERE mecano = '$r1[0]'
  AND datedebut <= '$d2'
  AND datefin >= '$d1'
  AND type IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14)
");

$conges = ['annuel' => 0, 'maladie' => 0, 'at' => 0, 'sanction' => 0, 'autre'=>0, 'sansolde'=>0];
while($row = mysqli_fetch_assoc($reqTousConges)) {
    $conges[$row['type']] += $row['jours'];
}

$nbconge = $conges['annuel'];
$nbautrecongeMaladie = $conges['maladie'];
$nbautrecongeAT = $conges['at'];
$nbautrecongeSanction = $conges['sanction'];
$nbautrecongeautre = $conges['autre'];
$nbautrecongeSanssolde = $conges['sansolde'];

    // Compter les congés annuels
    $reqCongeAnnuel = mysqli_query($conn, "SELECT SUM(
    DATEDIFF(
        LEAST(datefin, '".$d2."'), 
        GREATEST(datedebut, '".$d1."')
    )
) AS total 
FROM conge 
WHERE mecano='".$r1[0]."' 
AND datedebut <= '".$d2."' 
AND datefin >= '".$d1."'");

if(!$reqCongeAnnuel) {
    die("Erreur SQL: " . mysqli_error($conn));
}

$congeAnnuel = mysqli_fetch_assoc($reqCongeAnnuel);
$nbconge = $congeAnnuel['total'] ?? 0;
   
        
        // Maintenant parcourir chaque jour pour les autres calculs
        foreach ($dateRange as $date) {
            $d = $date->format('Y-m-d');
            $concat = $date->format('md');
           
            // Vérifier si c'est un jour de repos pour cet employé
            $isRepos = false;
            $dayOfWeek = $date->format('l');
            if($dayOfWeek == $jrepos) {
                $isRepos = true;
            }
            elseif($jrepos == "SatSun" && ($dayOfWeek == "Saturday" || $dayOfWeek == "Sunday")) {
                $isRepos = true;
            }
            
            // Vérifier si l'employé a travaillé ce jour
            if ($_GET['annee'] > "2023") {
                $reqPointage = mysqli_query($conn, "SELECT * FROM pointageall WHERE mecano='".$r1[0]."' AND date='".$d."'");
            } else {
                $reqPointage = mysqli_query($conn, "SELECT * FROM pointageallhistory WHERE mecano='".$r1[0]."' AND date='".$d."'");
            }
            
            $aTravaille = mysqli_num_rows($reqPointage) > 0;
            
            // Vérifier si c'est un jour férié
            $reqFerie = mysqli_query($conn, "SELECT * FROM congenational WHERE 
                ((deb <= '".$concat."' AND fin >= '".$concat."' AND annee='".$getannee."' AND type=2) OR 
                (deb <= '".$concat."' AND fin >= '".$concat."' AND type=1))");
            $isFerie = mysqli_num_rows($reqFerie) > 0;
            
           // Compter les jours fériés/repos travaillés
        if($isFerie && $aTravaille) $nbFerieTravailles++;
        if($isRepos && $aTravaille) $nbReposTravailles++;
            
            if(!$aTravaille && !$isRepos && !$isFerie) {
            // Vérifier que ce jour n'est pas dans un congé
            $estEnConge = false;
            
            // Vérifier congé annuel
            $reqCongeJour = mysqli_query($conn, "SELECT 1 FROM conge 
                WHERE mecano='".$r1[0]."' 
                AND datedebut <= '".$d."' 
                AND datefin >= '".$d."'");
            if(mysqli_num_rows($reqCongeJour)) {
                $estEnConge = true;
            }
            
            // Vérifier autre congé (maladie/AT/sanction)
            if(!$estEnConge) {
                $reqAutreCongeJour = mysqli_query($conn, "SELECT 1 FROM autreconge 
                    WHERE mecano='".$r1[0]."' 
                    AND datedebut <= '".$d."' 
                    AND datefin >= '".$d."'");
                if(mysqli_num_rows($reqAutreCongeJour)) {
                    $estEnConge = true;
                }
            }
            
            // Si pas en congé, compter comme absence
            if(!$estEnConge) {
                $nbabsence++;
            }
        }
    }

    // Calcul de la base du mois
    $baseMois = max(0, 26 - $nbabsence - $nbautrecongeAT - $nbautrecongeMaladie - $nbautrecongeSanction - $nbautrecongeSanssolde);
        
        // Update summary totals
        $total_base += $baseMois;
        $total_absence += $nbabsence;
        $total_conge += $nbconge;
        $total_at += $nbautrecongeAT;
        $total_maladie += $nbautrecongeMaladie;
        $total_sanction += $nbautrecongeSanction;
		$total_autre += $nbautrecongeautre;
		$total_sanssolde += $nbautrecongeSanssolde;
        $total_ferie += $nbFerieTravailles;
        $total_repos += $nbReposTravailles;
        $employee_count++;
        
        $i++;
		
		$panier = 0;
if ($r1[6] == "MariéMaitrise") {
    $panier = ($baseMois- $nbconge - $nbautrecongeautre) * 256;
}else if ($r1[6] == "MariéExecution") {
    $panier = ($baseMois- $nbconge - $nbautrecongeautre) * 180;
}else if ($r1[6] == "CélibExecution") {
    $panier = ($baseMois- $nbconge - $nbautrecongeautre) * 148;
}else if ($r1[6] == "CélibMaitrise") {
    $panier = $baseMois * 0;
}
        // Afficher la ligne dans le tableau récapitulatif
        echo '<tr>
                <td>'.$i.'</td>
                <td>'.$r1[0].'</td>
                <td>'.$r1[1].'</td>
                <td>'.$r1[6].'</td>
                <td class="base-mois">'.$baseMois.'</td>
                <td>'.$nbabsence.'</td>
                <td>'.$nbconge.'</td>
                <td>'.$nbautrecongeAT.'</td>
                <td>'.$nbautrecongeMaladie.'</td>
                <td>'.$nbautrecongeSanction.'</td>
				<td>'.$nbautrecongeautre.'</td>
                <td>'.$nbautrecongeSanssolde.'</td>
                <td>'.$nbFerieTravailles.'</td>
                <td>'.$nbReposTravailles.'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.$panier.'</td>
				              </tr>';
    }
    
    // Add summary row
    echo '</tbody><tfoot><tr class="summary-row">
            <td colspan="4">TOTAL ('.$employee_count.' employés)</td>
            <td>'.$total_base.'</td>
            <td>'.$total_absence.'</td>
            <td>'.$total_conge.'</td>
            <td>'.$total_at.'</td>
            <td>'.$total_maladie.'</td>
            <td>'.$total_sanction.'</td>
			<td>'.$total_autre.'</td>
			<td>'.$total_sanssolde.'</td>
            <td>'.$total_ferie.'</td>
            <td>'.$total_repos.'</td>
			<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
          </tr></tfoot></table>';
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
    document.onreadystatechange = function () {
        if (document.readyState !== "complete") {
            document.querySelector("body").style.visibility = "hidden";
            document.getElementById("loading_indicator").style.visibility = "visible";
        } else {
            setTimeout(() => {
                document.getElementById("loading_indicator").style.display = "none";
                document.querySelector("body").style.visibility = "visible";
                
                // Initialize DataTable
                $('#recapTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    pageLength: 100,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
                    }
                });
            }, 1000)
        }
    };

    // Reset filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        window.location.href = window.location.pathname;
    });
</script>
</body>
</html>