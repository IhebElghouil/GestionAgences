<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php session_start(); ?>
<?php $type = (isset($_GET['type']) && $_GET['type'] == "lait") ? "الحليب" : "تذاكر الأكل"; ?>
<title dir=rtl> متابعة <?php echo $type; ?></title>
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
        background-color: #9fe7f5;
        color: #333;
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
        background-color: #9fe7f5;
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
<?php include('menu.php'); ?>
<div class="header">

    <h2><?php echo htmlspecialchars($_GET['annee'] ?? '').'/'.htmlspecialchars($_GET['mois'] ?? ''); ?>متابعة <?php echo $type; ?></h2>
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
                include('DbConnexion.php');
                
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
		
		<div class="filter-group">
            <label for="date_fin">Type:</label>
             <select name="type" id="type">
			 <option value='restaurant' <?php echo (($_GET['type'] ?? '') == 'restaurant' ? 'selected' : ''); ?>>Tickets Restaurants</option>
			 <option value='lait' <?php echo (($_GET['type'] ?? '') == 'lait' ? 'selected' : ''); ?>>Bons Lait</option>
			 </select>
        </div>
        
        <button type="submit">Filtrer</button>
        <button type="button" id="resetFilters">Réinitialiser</button>
    </form>
</div>

<?php

function getWorkingDaysInMonth($month, $year) {
    $workingDays = 0;

    // Nombre de jours dans le mois
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$year-$month-$day";
        $dayOfWeek = date('N', strtotime($date)); // 1 (lundi) à 7 (dimanche)

        if ($dayOfWeek < 6) { // 1 à 5 = lundi à vendredi
            $workingDays++;
        }
    }

    return $workingDays;
}

function getNombreJoursFeries($conn, $mois, $annee) {
    $ferie_dates = [];

    $stmt = $conn->prepare("SELECT deb, fin FROM congenational WHERE annee = ? AND (type = 1 OR type = 2)");
    $stmt->bind_param("s", $annee);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $start_md = str_pad($row['deb'], 4, '0', STR_PAD_LEFT);
        $end_md = str_pad($row['fin'], 4, '0', STR_PAD_LEFT);

        try {
            $start_date = new DateTime("$annee-" . substr($start_md, 0, 2) . "-" . substr($start_md, 2, 2));
            $end_date = new DateTime("$annee-" . substr($end_md, 0, 2) . "-" . substr($end_md, 2, 2));
        } catch (Exception $e) {
            continue; // ignore les dates invalides
        }

        while ($start_date <= $end_date) {
            if ((int)$start_date->format('m') == (int)$mois) {
                $ferie_dates[$start_date->format('Y-m-d')] = true;
            }
            $start_date->modify('+1 day');
        }
    }

    return count($ferie_dates);
}

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
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'gabes' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'kebili' && $_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice IN (15,16) AND SV.id IN (15,16) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'kebili' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice IN (15,16) AND SV.id IN (15,16) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'parcgabes' && $_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice IN (14,4,45,12) AND SV.id IN (14,4,45,12) ORDER BY S.idservice, S.dep, S.mecano");
        }
        elseif ($region == 'parcgabes' && $_GET['annee'] <= "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice IN (14,4,45,12) AND SV.id IN (14,4,45,12) ORDER BY S.idservice, S.dep, S.mecano");
        }
    }
    else {
        $serv = $_GET['service'] ?? '';
        if($_GET['annee'] > "2023") {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageall A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice='".$serv."' ORDER BY S.idservice, S.dep, S.mecano");
        }
        else {
            $req1 = mysqli_query($conn,"SELECT DISTINCT (S.mecano), S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut FROM stuf S LEFT JOIN service SV ON (SV.id = S.idservice) LEFT JOIN pointageallhistory A ON (S.mecano = A.mecano AND DATE BETWEEN '".$d1."' and '".$d2."') WHERE contrastage IN (0, 1, 3) AND lait = 1 AND S.idservice='".$serv."' ORDER BY S.idservice, S.dep, S.mecano");
        }
    }

    // Afficher le tableau récapitulatif
    echo '<table id="recapTable" class="recap-table display nowrap" style="width:100%">';
    echo '<thead><tr>
            <th>N°</th>
            <th>Matricule</th>
            <th>Nom</th>
			<th>Nom Fr</th>
            <th>Service</th>
            <th>∑ J.Travaillés</th>
            <th>∑ Absences</th>
            <th>∑ C.A</th>
            <th>∑ AT</th>
            <th>∑ Maladie</th>
            <th>∑ Susp</th>
			<th>∑ Autres</th>
			<th>∑ S.Solde</th>';
			if(isset($_GET['type']) && $_GET['type'] == "lait") {
			echo "<th>J.Fériés Trav</th>
			<th>J.Repos Trav</th>
			<th>Val.Bons</th>"; }
			else
			{
			echo "<th>Nb.Fériés Trav</th>
			<th>Nb.Déplacements</th>
			<th>Nb.Tickets</th>";
			}
			
			echo '
			</tr></thead><tbody>';




// 1. Determine table name for pointage history
$table_pointage = ($currentYear > 2023) ? "pointageall" : "pointageallhistory";

// 2. Build the filter for employees based on region/service
 $filter_employee_sql = "";

if (isset($_GET['service']) && is_numeric($_GET['service'])) {
    $service = intval($_GET['service']);
    $filter_employee_sql = "AND S.idservice = $service";
}


// 3. Fetch ALL relevant employees in ONE query
if($_GET['type']=="lait"){
$employee_sql = "SELECT DISTINCT S.mecano, S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut, FR.nomFr, S.pointagemachine, D.dep, S.titre
                 FROM stuf S
                 LEFT JOIN service SV ON SV.id = S.idservice
				 LEFT JOIN social FR ON S.mecano = FR.mecano
				 LEFT JOIN dep D ON S.dep = D.id
                 WHERE contrastage IN (0,1,3) AND lait = 1 $filter_employee_sql
                 ORDER BY S.idservice, S.dep, S.mecano";
}
else
{
$employee_sql = "SELECT DISTINCT S.mecano, S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut, FR.nomFr, S.pointagemachine, D.dep, S.titre
                 FROM stuf S
                 LEFT JOIN service SV ON SV.id = S.idservice
				 LEFT JOIN social FR ON S.mecano = FR.mecano
				  LEFT JOIN dep D ON S.dep = D.id
                 WHERE contrastage IN (0,1,3) $filter_employee_sql
                 ORDER BY S.idservice, S.dep, S.mecano";

}	
				 
				$employees_result = $conn->query($employee_sql);
if (!$employees_result || $employees_result->num_rows === 0) {
    die("Aucun employé trouvé avec les critères choisis !");
}
$employees = $employees_result->fetch_all(MYSQLI_ASSOC);
$mecanos_in_filter = array_column($employees, 'mecano');
$mecano_list_sql = implode("','", $mecanos_in_filter);

// 4. Fetch ALL pointage data for these employees and period in ONE query
$pointage_data = [];
if (!empty($mecanos_in_filter)) {
    $pointage_sql = "SELECT mecano, date FROM $table_pointage WHERE mecano IN ('$mecano_list_sql') AND date BETWEEN '$d1' AND '$d2'";
    $pointage_result = $conn->query($pointage_sql);
    while ($row = $pointage_result->fetch_assoc()) {
        $pointage_data[$row['mecano']][$row['date']] = true;
    }
}

// 5. Fetch ALL conge data for these employees and period in ONE or two queries
$conge_data = [];
if (!empty($mecanos_in_filter)) {
    $conge_sql = "SELECT mecano, NULL AS type, datedebut, datefin FROM conge WHERE mecano IN ('$mecano_list_sql') AND datedebut <= '$d2' AND datefin >= '$d1'
                  UNION ALL
                  SELECT mecano, type, datedebut, datefin FROM autreconge WHERE mecano IN ('$mecano_list_sql') AND datedebut <= '$d2' AND datefin >= '$d1'";
    $conge_result = $conn->query($conge_sql);
    while ($row = $conge_result->fetch_assoc()) {
        $conge_data[$row['mecano']][] = $row;
    }
}

// 6. Fetch ALL national holidays in ONE query
$ferie_dates = [];
$ferie_sql = "SELECT deb, fin FROM congenational WHERE annee='$currentYear' AND ((type=2) OR (type=1))";
$ferie_result = $conn->query($ferie_sql);
while ($row = $ferie_result->fetch_assoc()) {
    $start_md = str_pad($row['deb'], 4, '0', STR_PAD_LEFT);
    $end_md = str_pad($row['fin'], 4, '0', STR_PAD_LEFT);
    
    // Convert to full dates for current year to check against $dates array
    $current_date_obj = new DateTime($currentYear . '-' . substr($start_md, 0, 2) . '-' . substr($start_md, 2, 2));
    $end_date_obj = new DateTime($currentYear . '-' . substr($end_md, 0, 2) . '-' . substr($end_md, 2, 2));

    while ($current_date_obj <= $end_date_obj) {
        $ferie_dates[$current_date_obj->format('Y-m-d')] = true;
        $current_date_obj->modify('+1 day');
    }
}

// Liste des jours
$jours = [
    0 => ["Sunday", "الأحد"],
    1 => ["Monday", "الإثنين"],
    2 => ["Tuesday", "الثلاثاء"],
    3 => ["Wednesday", "الأربعاء"],
    4 => ["Thursday", "الخميس"],
    5 => ["Friday", "الجمعة"],
    6 => ["Saturday", "السبت"],
    10 => ["SatSun", "السبت و الأحد"]
];
        
// 7. Loop through each employee (now with data pre-fetched)
$i = 1;
// Génération des dates
$dates = [];
$dstart = new DateTime($d1);
$dend = (new DateTime($d2))->modify('+1 day');
while ($dstart < $dend) {
    $dates[] = clone $dstart;
    $dstart->modify('+1 day');
}
foreach ($employees as $employee) {
	
    extract($employee); // $mecano, $nom, $etat, $idservice, $libellet, $jrepos, $statut
    [$jrepos_name, $jreposTraduit] = $jours[$jrepos] ?? ["", ""];

    // Initialize counts for the current employee
    $absence = $ferieTrav = $reposTrav = $joursTav= 0;
    $conges_employee = ["annuel" => 0, "maladie" => 0, "at" => 0, "sanction" => 0, "autre" => 0, "sanssolde" => 0];

    // Process pre-fetched congés for the current employee
    foreach (($conge_data[$mecano] ?? []) as $conge) {
        $conge_start = new DateTime($conge['datedebut']);
        $conge_end = new DateTime($conge['datefin']);

        $overlap_start = max($conge_start, new DateTime($d1));
        $overlap_end = min($conge_end, new DateTime($d2));

        if ($overlap_start <= $overlap_end) {
           $type = match ((int)($conge['type'])) {
    5 => 'maladie', 6 => 'at', 10 => 'sanction', 9 => 'sanssolde',
    1,2,3,4,7,8,11,12,13,14 => 'autre', default => 'annuel'
};

$current = clone $overlap_start;
while ($current <= $overlap_end) {
    $date_str = $current->format('Y-m-d');
    $jour_name = $current->format('l');

    $isRepos = ($jrepos_name == $jour_name || ($jrepos_name == "SatSun" && in_array($jour_name, ["Saturday", "Sunday"])));
    $isFerie = isset($ferie_dates[$date_str]);

     if ($type === 'annuel') {
                // Pour les congés annuels, on ne compte pas les jours de repos et fériés
                if (!$isRepos && !$isFerie) {
                    $conges_employee[$type]++;
                }
            } else {
                // Pour tous les autres types de congés, on compte tous les jours sauf les fériés
                // mais on inclut les jours de repos
                if (!$isFerie) {
                    $conges_employee[$type]++;
                }
            }

    $current->modify('+1 day');
}
        }
    }

    // Iterate through dates for THIS employee, checking pre-fetched data
    foreach ($dates as $dateObj) {
        $date_str = $dateObj->format('Y-m-d');
        $jour_name = $dateObj->format('l');

        $isRepos = ($jrepos_name == $jour_name || ($jrepos_name == "SatSun" && in_array($jour_name, ["Saturday", "Sunday"])));
        $isFerie = isset($ferie_dates[$date_str]); // Check pre-fetched national holidays
        $travaille = isset($pointage_data[$mecano][$date_str]); // Check pre-fetched pointage
if ($travaille)
{
	$joursTav++;
}
        if ($isFerie && $travaille) $ferieTrav++;
        if ($isRepos && $travaille) $reposTrav++;

        if (!$travaille && !$isRepos && !$isFerie) {
            // Check if there's any active conge for this specific day for this employee
            $is_on_conge_today = false;
            foreach (($conge_data[$mecano] ?? []) as $conge) {
                if ($date_str >= $conge['datedebut'] && $date_str <= $conge['datefin']) {
                    $is_on_conge_today = true;
                    break;
                }
            }
            if (!$is_on_conge_today) {
                $absence++;
            }
        }
    }
    
    // ... (Calculate baseMois, panier, and echo row) ...
    $baseMois = max(0, 26 - $absence - $conges_employee['at'] - $conges_employee['maladie'] - $conges_employee['sanction'] - $conges_employee['sanssolde']);
    $valBons=$joursTav*1.5*1.3;
    $joursTavaille = ($pointagemachine == 0) ? ' ??' : $joursTav;
    $Absences = ($pointagemachine == 0) ? ' ??' : $absence;
    $bgcolor = ($pointagemachine == 0) ? ' bgcolor="red"' : '';

// if titre is chauffeur, chauffeur1, chaffeur2.... la loi lui permet le déplacement alors afficher ??
$deplacement=in_array($titre, [6, 7, 8, 21, 24,25,35,36]) ? '??' : 0;

$bgcolor1 = ($deplacement == '??') ? ' bgcolor="red"' : '';

$nbjoursmois= ($jrepos==10)? getWorkingDaysInMonth($_GET['mois'], $_GET['annee']) : 26;
if($Absences==' ??'){$absence=0;}
$nombreFeries = getNombreJoursFeries($conn, $_GET['mois'], $_GET['annee']);
$nbBons=($nbjoursmois - $absence - $conges_employee['at'] - $conges_employee['maladie'] - $conges_employee['sanction'] - $conges_employee['sanssolde']<=0) ? '0' : $nbjoursmois - $absence - $conges_employee['at'] - $conges_employee['maladie'] - $conges_employee['sanction'] - $conges_employee['sanssolde'];

$typeAffichage = (isset($_GET['type']) && $_GET['type'] == "lait")
    ? number_format($valBons, 3, '.', '')
    : $nbBons;
$type = $_GET['type'] ?? '';
$typeAffichageJourFerié = in_array($type, ['lait', 'restaurant']) ? $ferieTrav : $nombreFeries;
$typeAffichageRepos = (isset($_GET['type']) && $_GET['type'] == "lait")
    ? $reposTrav
    : $deplacement;
echo "<tr><td>$i</td><td>$mecano</td><td>$nom</td><td>$nomFr</td><td>$dep</td><td $bgcolor>$joursTavaille</td><td $bgcolor>$Absences</td><td>{$conges_employee['annuel']}</td><td>{$conges_employee['at']}</td><td>{$conges_employee['maladie']}</td><td>{$conges_employee['sanction']}</td><td>{$conges_employee['autre']}</td><td>{$conges_employee['sanssolde']}</td><td>$typeAffichageJourFerié</td><td $bgcolor1>$typeAffichageRepos</td><td  $bgcolor>$typeAffichage</td></tr>";
   // echo "<tr><td>$i</td><td>$mecano</td><td>$nom</td><td>$nomFr</td><td>$libellet</td><td>$joursTav</td><td>$absence</td><td>{$conges_employee['annuel']}</td><td>{$conges_employee['at']}</td><td>{$conges_employee['maladie']}</td><td>{$conges_employee['sanction']}</td><td>{$conges_employee['autre']}</td><td>{$conges_employee['sanssolde']}</td><td>$ferieTrav</td><td>$reposTrav</td><td>".number_format($valBons, 3, '.', '')."</td></tr>";
    $i++;
}
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
                        url: 'js/fr-FR.json'
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