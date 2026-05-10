<?php
session_start();
include('menu.php'); 
include('DbConnexion.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>متابعة الحضور - Suivi des présences</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <style>
        #loading_indicator {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 9999;
            display: none;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        .recap-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .recap-table th, .recap-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        
        .recap-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        
        .recap-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .recap-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .filter-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filter-group {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 10px;
        }
        
        .filter-group label {
            font-weight: 600;
            margin-right: 5px;
        }
        
        .filter-group select, .filter-group input {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 6px 20px;
            color: white;
            cursor: pointer;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            border: none;
            padding: 6px 20px;
            color: white;
            cursor: pointer;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        
        @media (max-width: 768px) {
            .filter-group {
                display: block;
                margin-bottom: 10px;
            }
            .recap-table {
                font-size: 11px;
            }
        }
        
        .toast-container-top-center {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
        }
    </style>
</head>
<body>

<!-- Toast notification -->
<div class="toast-container-top-center">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body">
                ✅ Veuillez d'abord enregistrer les présences pour que les données soient correctes !
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    </div>
</div>

<div id="loading_indicator"></div>

<div class="header">
    <h2>
        <?php 
        $mois_noms = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $mois_actuel = isset($_GET['mois']) ? (int)$_GET['mois'] : date('n');
        $annee_actuelle = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
        echo "Suivi des présences - " . $mois_noms[$mois_actuel] . " " . $annee_actuelle;
        ?>
    </h2>
</div>

<div class="filter-container">
    <form method="GET" action="" id="filterForm">
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
                <option value="">--------</option>
                <?php
                $services = mysqli_query($conn, "SELECT id, libellet FROM service ORDER BY libellet");
                while($service = mysqli_fetch_assoc($services)) {
                    $selected = (($_GET['service'] ?? '') == $service['id'] ? 'selected' : '');
                    echo "<option value='{$service['id']}' $selected>" . htmlspecialchars($service['libellet']) . "</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="mois">Mois:</label>
            <select name="mois" id="mois">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($mois_actuel == $i) ? 'selected' : ''; ?>>
                        <?php echo $mois_noms[$i]; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="annee">Année:</label>
            <select name="annee" id="annee">
                <?php for($i = date('Y') - 3; $i <= date('Y') + 2; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($annee_actuelle == $i) ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
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
        
        <button type="submit" class="btn-primary">Filtrer</button>
        <button type="button" id="resetFilters" class="btn-secondary">Réinitialiser</button>
    </form>
</div>

<?php
// Initialisation des variables
$total_base = 0;
$total_absence = 0;
$total_conge = 0;
$total_at = 0;
$total_maladie = 0;
$total_sanction = 0;
$total_ferie = 0;
$total_repos = 0;
$total_autre = 0;
$total_sanssolde = 0;

// Récupération des paramètres
$region = $_GET['region'] ?? 'gabes';
$service = $_GET['service'] ?? '';
$mois = isset($_GET['mois']) ? (int)$_GET['mois'] : date('n');
$annee = isset($_GET['annee']) ? (int)$_GET['annee'] : date('Y');
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';

// Détermination de la période
if(empty($date_debut)) {
    $d1 = date('Y-m-d', strtotime("$annee-$mois-01"));
} else {
    $d1 = $date_debut;
}

if(empty($date_fin)) {
    $d2 = date('Y-m-t', strtotime($d1));
} else {
    $d2 = $date_fin;
}

// Table de pointage selon l'année
$table_pointage = ($annee > 2023) ? "pointageall" : "pointageallhistory";

// Construction de la requête employés
/*$sql_employees = "SELECT DISTINCT S.mecano, S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut 
                  FROM stuf S 
                  LEFT JOIN service SV ON SV.id = S.idservice 
                  WHERE contrastage IN (0, 1, 3) AND pointagemachine = 1";*/
				  
// Construction de la requête employés

// Calculer la date de fin du mois sélectionné
$date_fin_mois = date('Y-m-t', strtotime("$annee-$mois-01"));

$sql_employees = "SELECT DISTINCT S.mecano, S.nom, S.etat, S.idservice, SV.libellet, S.jrepos, S.statut 
                 FROM stuf S 
                 LEFT JOIN service SV ON SV.id = S.idservice 
                 WHERE contrastage IN (0,1,3) 
                 AND pointagemachine = 1 
                 AND (S.date_debut_pointage <= '$date_fin_mois' OR S.date_debut_pointage IS NULL)";

// Filtre par région
if(empty($service)) {
    if($region == 'kebili') {
        $sql_employees .= " AND S.idservice IN (15,16)";
    } elseif($region == 'parcgabes') {
        $sql_employees .= " AND S.idservice IN (14,4,45,12)";
    }
} else {
    $sql_employees .= " AND S.idservice = " . intval($service);
}

$sql_employees .= " ORDER BY S.idservice, S.dep, S.mecano";

$result_employees = mysqli_query($conn, $sql_employees);
if(!$result_employees) {
    die("Erreur SQL: " . mysqli_error($conn));
}

// Récupération des jours fériés
$feries = [];
$sql_feries = "SELECT deb, fin FROM congenational WHERE annee='$annee' AND type IN (1,2)";
$result_feries = mysqli_query($conn, $sql_feries);
if($result_feries) {
    while($ferie = mysqli_fetch_assoc($result_feries)) {
        $start = str_pad($ferie['deb'], 4, '0', STR_PAD_LEFT);
        $end = str_pad($ferie['fin'], 4, '0', STR_PAD_LEFT);
        $start_date = DateTime::createFromFormat('Ymd', $annee . $start);
        $end_date = DateTime::createFromFormat('Ymd', $annee . $end);
        
        if($start_date && $end_date) {
            $current = clone $start_date;
            while($current <= $end_date) {
                $feries[$current->format('Y-m-d')] = true;
                $current->modify('+1 day');
            }
        }
    }
}

// Jours de la semaine
$jours_semaine = [
    0 => ['en' => 'Sunday', 'ar' => 'الأحد'],
    1 => ['en' => 'Monday', 'ar' => 'الإثنين'],
    2 => ['en' => 'Tuesday', 'ar' => 'الثلاثاء'],
    3 => ['en' => 'Wednesday', 'ar' => 'الأربعاء'],
    4 => ['en' => 'Thursday', 'ar' => 'الخميس'],
    5 => ['en' => 'Friday', 'ar' => 'الجمعة'],
    6 => ['en' => 'Saturday', 'ar' => 'السبت'],
    10 => ['en' => 'SatSun', 'ar' => 'السبت والأحد']
];

// Génération des dates de la période
$period = new DatePeriod(
    new DateTime($d1),
    new DateInterval('P1D'),
    (new DateTime($d2))->modify('+1 day')
);
$dates = iterator_to_array($period);

// Tableau HTML
echo '<div style="overflow-x: auto; margin: 20px;">';
echo '<table id="recapTable" class="recap-table display nowrap" style="width:100%">';
echo '<thead>';
echo '<tr>
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
        <th>Panier</th>
      </tr>';
echo '</thead><tbody>';

$i = 1;
while($employee = mysqli_fetch_assoc($result_employees)) {
    $mecano = $employee['mecano'];
    $nom = htmlspecialchars($employee['nom']);
    $statut = $employee['statut'];
    $jrepos = $employee['jrepos'];
    
    $jour_repos = $jours_semaine[$jrepos]['en'] ?? '';
    
    // Récupération des pointages
    $sql_pointages = "SELECT date FROM $table_pointage WHERE mecano = '$mecano' AND date BETWEEN '$d1' AND '$d2'";
    $result_pointages = mysqli_query($conn, $sql_pointages);
    $pointages = [];
    if($result_pointages) {
        while($p = mysqli_fetch_assoc($result_pointages)) {
            $pointages[$p['date']] = true;
        }
    }
    
    // Récupération des congés depuis la table "conge" (sans colonne type)
    // Par défaut, on considère que c'est un congé annuel (type 1)
    $sql_conges = "SELECT datedebut, datefin FROM conge WHERE mecano = '$mecano' 
                   AND datedebut <= '$d2' AND datefin >= '$d1'";
    $result_conges = mysqli_query($conn, $sql_conges);
    $conges = [];
    if($result_conges) {
        while($c = mysqli_fetch_assoc($result_conges)) {
            $conges[] = [
                'datedebut' => $c['datedebut'],
                'datefin' => $c['datefin'],
                'type' => 1  // Type par défaut pour "congé annuel"
            ];
        }
    }
    
    // Récupération des autres congés (autreconge) - avec colonne type
    $sql_autres_conges = "SELECT datedebut, datefin, type FROM autreconge WHERE mecano = '$mecano' 
                          AND datedebut <= '$d2' AND datefin >= '$d1'";
    $result_autres = mysqli_query($conn, $sql_autres_conges);
    if($result_autres) {
        while($c = mysqli_fetch_assoc($result_autres)) {
            $conges[] = [
                'datedebut' => $c['datedebut'],
                'datefin' => $c['datefin'],
                'type' => $c['type']
            ];
        }
    }
    
    // Initialisation des compteurs
    $absence = 0;
    $ferieTrav = 0;
    $reposTrav = 0;
    $joursPointes = 0;
    $conges_counts = ['annuel' => 0, 'maladie' => 0, 'at' => 0, 'sanction' => 0, 'autre' => 0, 'sanssolde' => 0];
    
    // Parcours des dates
    foreach($dates as $date) {
        $date_str = $date->format('Y-m-d');
        $jour_nom = $date->format('l');
        
        $est_repos = ($jour_repos == $jour_nom) || ($jour_repos == "SatSun" && in_array($jour_nom, ['Saturday', 'Sunday']));
        $est_ferie = isset($feries[$date_str]);
        $est_pointage = isset($pointages[$date_str]);
        
        // Vérification si en congé ce jour
        $est_conge = false;
        $type_conge = null;
        foreach($conges as $conge) {
            if($date_str >= $conge['datedebut'] && $date_str <= $conge['datefin']) {
                $est_conge = true;
                $type_conge = $conge['type'];
                break;
            }
        }
        
        if($est_ferie && $est_pointage) {
            $ferieTrav++;
        }
        
        if($est_repos && $est_pointage) {
            $reposTrav++;
        }
        
        if($est_pointage && !$est_repos && !$est_ferie) {
            $joursPointes++;
        }
        
        if(!$est_pointage && !$est_repos && !$est_ferie && !$est_conge) {
            $absence++;
        }
        
        // Comptage des congés
        if($est_conge) {
            // Mapping des types de congés
            // Types courants: 1=congé annuel, 5=maladie, 6=AT, 9=sans solde, 10=sanction/suspension
            switch((int)$type_conge) {
                case 5:
                    $type = 'maladie';
                    break;
                case 6:
                    $type = 'at';
                    break;
                case 9:
                    $type = 'sanssolde';
                    break;
                case 10:
                    $type = 'sanction';
                    break;
                case 1:
                case 2:
                case 3:
                case 4:
                case 7:
                case 8:
                case 11:
                case 12:
                case 13:
                case 14:
                    $type = 'autre';
                    break;
                default:
                    $type = 'annuel';
            }
            
            if(in_array($type, ['annuel', 'sanction', 'autre'])) {
                if(!$est_repos && !$est_ferie) {
                    $conges_counts[$type]++;
                }
            } else {
                $conges_counts[$type]++;
            }
        }
    }
    
    // Calcul de la base du mois
    $nbr_jours_mois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
    $absence_calcul_base = $absence + $conges_counts['at'] + $conges_counts['maladie'] + 
                          $conges_counts['sanction'] + $conges_counts['sanssolde'];
    
    if($absence_calcul_base == 0) {
        $baseMois = 26;
    } elseif($absence_calcul_base < $nbr_jours_mois) {
        if($joursPointes > (26 - $absence_calcul_base)) {
            $baseMois = $joursPointes + $conges_counts['annuel'] + $conges_counts['autre'];
        } else {
            $baseMois = max(0, 26 - $absence_calcul_base);
        }
    } else {
        $baseMois = min(26, $joursPointes + $conges_counts['annuel'] + $conges_counts['autre']);
    }
    
    // Calcul du panier
    $jours_panier = $baseMois - $conges_counts['annuel'] - $conges_counts['autre'];
    switch($statut) {
        case 'MariéMaitrise':
            $panier = $jours_panier * 0.256;
            break;
        case 'MariéExecution':
            $panier = $jours_panier * 0.180;
            break;
        case 'CélibExecution':
            $panier = $jours_panier * 0.148;
            break;
        case 'CélibMaitrise':
            $panier = $jours_panier * 0.240;
            break;
        default:
            $panier = 0;
    }
    $panier = round($panier, 3);
    
    // Mise à jour des totaux
    $total_base += $baseMois;
    $total_absence += $absence;
    $total_conge += $conges_counts['annuel'];
    $total_at += $conges_counts['at'];
    $total_maladie += $conges_counts['maladie'];
    $total_sanction += $conges_counts['sanction'];
    $total_autre += $conges_counts['autre'];
    $total_sanssolde += $conges_counts['sanssolde'];
    $total_ferie += $ferieTrav;
    $total_repos += $reposTrav;
    
    // Affichage de la ligne
    echo "<tr>
            <td style='text-align:center'>{$i}</td>
            <td style='text-align:center'>{$mecano}</td>
            <td>{$nom}</td>
            <td>{$statut}</td>
            <td style='text-align:center'>{$baseMois}</td>
            <td style='text-align:center'>{$absence}</td>
            <td style='text-align:center'>{$conges_counts['annuel']}</td>
            <td style='text-align:center'>{$conges_counts['at']}</td>
            <td style='text-align:center'>{$conges_counts['maladie']}</td>
            <td style='text-align:center'>{$conges_counts['sanction']}</td>
            <td style='text-align:center'>{$conges_counts['autre']}</td>
            <td style='text-align:center'>{$conges_counts['sanssolde']}</td>
            <td style='text-align:center'>{$ferieTrav}</td>
            <td style='text-align:center'>{$reposTrav}</td>
            <td style='text-align:center'>" . number_format($panier, 3, '.', '') . "</td>
           </tr>";
    $i++;
}

// Ligne de total
if($i > 1) {
    echo '<tfoot>';
    echo '<tr style="background-color: #2c3e50; color: white; font-weight: bold;">
            <td colspan="4"><strong>TOTAUX</strong></td>
            <td style="text-align:center"><strong>' . round($total_base, 2) . '</strong></td>
            <td style="text-align:center"><strong>' . $total_absence . '</strong></td>
            <td style="text-align:center"><strong>' . $total_conge . '</strong></td>
            <td style="text-align:center"><strong>' . $total_at . '</strong></td>
            <td style="text-align:center"><strong>' . $total_maladie . '</strong></td>
            <td style="text-align:center"><strong>' . $total_sanction . '</strong></td>
            <td style="text-align:center"><strong>' . $total_autre . '</strong></td>
            <td style="text-align:center"><strong>' . $total_sanssolde . '</strong></td>
            <td style="text-align:center"><strong>' . $total_ferie . '</strong></td>
            <td style="text-align:center"><strong>' . $total_repos . '</strong></td>
            <td></td>
           </tr>';
    echo '</tfoot>';
}

echo '</tbody>}</table>';
echo '</div>';

mysqli_close($conn);
?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script>
$(document).ready(function() {
    // Afficher le toast
    var toastEl = document.getElementById('liveToast');
    if(toastEl) {
        var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        toast.show();
    }
    
    // Initialiser DataTable
    var table = $('#recapTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn-sm' },
            { extend: 'csv', className: 'btn-sm' },
            { extend: 'excel', className: 'btn-sm' },
            { extend: 'pdf', className: 'btn-sm' },
            { extend: 'print', className: 'btn-sm' },
            { extend: 'colvis', className: 'btn-sm' }
        ],
        pageLength: 50,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
        },
        responsive: true,
        scrollX: true,
        order: [[0, 'asc']]
    });
    
    // Réinitialisation des filtres
    $('#resetFilters').on('click', function() {
        window.location.href = window.location.pathname;
    });
    
    // Cache le loader
    $('#loading_indicator').hide();
});
</script>

</body>
</html>