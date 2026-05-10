<html>
<head>
<style type = "text/css">
    p {page-break-after: always;}
    .agent-section {
        page-break-inside: avoid;
        margin-bottom: 20px;
    }
    .page-break {
        page-break-before: always;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }
    td {
        padding: 4px;
        text-align: center;
        border: 1px solid #000;
    }
    .title {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>
</head>
<body>
<?php
//$selected_annee = isset($_GET['annee']) ? intval($_GET['annee']) : $annees_disponibles[0];
require("DBconnexion.php");

$stmt = mysqli_prepare($conn, "SELECT DISTINCT annee FROM annee ORDER BY annee DESC");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $annee_dispo);
$annees_disponibles = [];
while (mysqli_stmt_fetch($stmt)) {
    $annees_disponibles[] = $annee_dispo;
}
mysqli_stmt_close($stmt);


$annee = isset($_GET['annee']) ? intval($_GET['annee']) : 0;
// Afficher le sélecteur d'année
echo '<div class="year-selector">';
echo '<form method="GET" action="">';
echo '<label for="annee">Choisir l\'année : </label>';
echo '<select name="annee" id="annee">';

$currentYear = $_GET['annee'] ?? date('Y');

for ($annees = 2025; $annees < 2060; $annees++) {

    $selected = ($annees == $currentYear) ? 'selected' : '';

    echo '<option value="' . $annees . '" ' . $selected . '>' . $annees . '</option>';
}

echo '</select>';
echo '<button type="submit">Afficher</button>';
echo '</form>';
echo '</div>';

// Récupérer tous les agents
$stmt = mysqli_prepare($conn, "SELECT mecano, nom FROM stuf WHERE contrastage IN (0,1,3) ORDER BY mecano ASC");
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $mecano, $nom);
$agents = [];
while (mysqli_stmt_fetch($stmt)) {
    $agents[] = ['mecano' => $mecano, 'nom' => $nom];
}
mysqli_stmt_close($stmt);

$agent_count = 0;

foreach ($agents as $agent) {
    $mecano = $agent['mecano'];
    $nom = $agent['nom'];
    
    // Saut de page pour chaque nouvel agent (sauf le premier)
    if ($agent_count > 0) {
        echo '<p></p>';
    }
    
    // Récupérer le solde de congés de l'agent
	$table = ($annee == $annee_dispo) ? 'nbconge' : 'nbconge' . intval($annee);


    $stmt = mysqli_prepare($conn, "SELECT (nbj1 + nbj2) AS Anciensolde, nbj1 AS Restsolde, nbj2 AS Actuelsolde FROM $table WHERE mecano = ?");
	
    mysqli_stmt_bind_param($stmt, "s", $mecano);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Anciensolde, $Restsolde, $Actuelsolde);
    $hasSolde = mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$hasSolde) {
        $Anciensolde = 0;
        $Restsolde = 0;
        $Actuelsolde = 0;
    }

    // Calcul des jours de maladie
    $DateDebutRequete = "$annee-01-01";
    $DateFinRequete = "$annee-12-31";

    $sqlMaladie = "SELECT 
        SUM(
            DATEDIFF(
                LEAST(?, datefin),
                GREATEST(?, datedebut)
            )+1
        ) AS TotalJoursMaladie 
        FROM autreconge 
        WHERE datedebut <= ? 
        AND datefin >= ? 
        AND TYPE = 5 
        AND mecano = ?";

    $stmtMaladie = $conn->prepare($sqlMaladie);
    $stmtMaladie->bind_param("sssss", $DateFinRequete, $DateDebutRequete, $DateFinRequete, $DateDebutRequete, $mecano);
    $stmtMaladie->execute();
    $stmtMaladie->bind_result($NbJourMaladie);
    $stmtMaladie->fetch();
    $stmtMaladie->close();

    if ($NbJourMaladie === null) {
        $NbJourMaladie = 0;
    }

    // Tableau des tranches de maladie
    $maladieBrackets = array(
        array(7, 19, 1), array(20, 32, 2), array(33, 45, 3),
        array(46, 58, 4), array(59, 71, 5), array(72, 84, 6),
        array(85, 97, 7), array(98, 110, 8), array(111, 123, 9),
        array(124, 136, 10), array(137, 149, 11), array(150, 162, 12),
        array(163, 175, 13), array(176, 188, 14), array(189, 201, 15),
        array(202, 214, 16), array(215, 227, 17), array(228, 240, 18),
        array(241, 253, 19), array(254, 266, 20), array(267, 279, 21),
        array(280, 292, 22), array(293, 305, 23), array(306, 318, 24),
        array(319, 331, 25), array(332, 344, 26), array(345, 357, 27),
        array(358, 370, 28)
    );

    $NbJourMaladieSoustract = 0;
    foreach ($maladieBrackets as $bracket) {
        if ($NbJourMaladie >= $bracket[0] && $NbJourMaladie <= $bracket[1]) {
            $NbJourMaladieSoustract = $bracket[2];
            break;
        }
    }

    // Récupérer les congés de l'agent
    $stmt = mysqli_prepare($conn, "SELECT stuf.mecano,conge.* FROM stuf LEFT JOIN conge on stuf.mecano=conge.mecano WHERE annee = ? AND stuf.mecano = ? AND contrastage IN (0,1,3) ORDER BY datedebut ASC");
    mysqli_stmt_bind_param($stmt, "ss", $annee, $mecano);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $conges = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    $numrows = count($conges);
    $i = 0;
    $page_number = 1;
    $rows_on_current_page = 0;
    $max_rows_per_page = 23; // 
    $solde_courant = $Anciensolde;
    $first_page_header_done = false;

    // Calculer le nombre total de lignes (congés + solde initial + solde final)
    $total_rows = $numrows + 1; // +1 pour la ligne de solde final
    if ($numrows > 0) {
        $total_rows++; // +1 pour la première ligne avec solde initial
    }

    // Calculer le nombre de pages nécessaires
    $total_pages = ceil($total_rows / $max_rows_per_page);

    for ($page = 1; $page <= $total_pages; $page++) {
        if ($page > 1) {
            echo '<p></p>';
        }
        echo '<h2 align="right">الشّركة الجهويّة للنّقل بقابس</h2>';
        echo '<h2 align="center">ملفّ العطل السّنويّة - ' . htmlspecialchars($mecano)." - ". htmlspecialchars($nom);
        if ($total_pages > 1) {
            echo ' (صفحة ' . $page . '/' . $total_pages . ')';
        }
        echo '</h2>';
        
        echo '<table width="100%" border="1" cellspacing="0" dir=rtl>';
        
        // En-tête du tableau (sur chaque page)
        echo '<tr>
                <td width="15%" rowspan=2><div align="center" class="title">السنة</div></td>
                <td width="15%" rowspan=2><div align="center" class="title">رصيد الإجازات المتبقيّة</div></td>
                <td width="40%" colspan=4><div align="center" class="title">الإجازة المطلوبة</div></td>
                <td width="35%" rowspan=2><div align="center" class="title">ملاحظات</div></td>
              </tr>
              <tr>
                <td><div align="center" class=title>الرقم</div></td>
                <td><div align="center" class=title>من</div></td>
                <td><div align="center" class=title>إلى</div></td>
                <td><div align="center" class=title>عدد الأيام</div></td>
              </tr>';
        
        $rows_on_current_page = 2; // On compte déjà les 2 lignes d'en-tête
        
        // Calculer quelles lignes afficher sur cette page
        $start_row = ($page - 1) * $max_rows_per_page;
        $end_row = min($page * $max_rows_per_page, $total_rows);
        
        // Réinitialiser $i pour chaque agent
        $i = 0;
        $solde_courant = $Anciensolde;
        $row_counter = 0;
        
        // Traiter les congés
        if ($numrows > 0) {
            foreach ($conges as $r) {
                $row_counter++;
                
                // Vérifier si cette ligne doit être affichée sur cette page
                if ($row_counter > $start_row && $row_counter <= $end_row) {
                    if ($i == 0) {
                        echo '<tr>
                                <td align=center>' . $r['annee'] . '</td>';
                        
                        if ($NbJourMaladieSoustract > 0) {
                            $solde_apres_maladie = $Anciensolde - $NbJourMaladieSoustract;
                            echo '<td align=center dir=rtl>(' . $Actuelsolde . '+' . $Restsolde . ')=' . $NbJourMaladieSoustract . '-' . $Anciensolde . '=' . $solde_apres_maladie . '</td>';
                            $solde_courant = $solde_apres_maladie;
                        } else {
                            echo '<td align=center dir=rtl>(' . $Actuelsolde . '+' . $Restsolde . ')=' . $Anciensolde . '</td>';
                            $solde_courant = $Anciensolde;
                        }
                        
                        echo '<td align=center>' . $r['id'] . '</td>
                              <td align=center>' . $r['datedebut'] . '</td>
                              <td align=center>' . $r['datefin'] . '</td>
                              <td align=center>' . $r['nbjours'] . '</td>';
                        
                        if ($NbJourMaladieSoustract > 0) {
                            echo '<td>خصم بعنوان المرض<span> ' . $NbJourMaladieSoustract . '</span> أيّام</td></tr>';
                        } else {
                            echo '<td></td></tr>';
                        }
                        
                        $solde_courant = $solde_courant - $r['nbjours'];
                    } else {
                        echo '<tr>
                                <td align=center>' . $r['annee'] . '</td>
                                <td align=center dir=rtl>' . $solde_courant . '</td>
                                <td align=center>' . $r['id'] . '</td>
                                <td align=center>' . $r['datedebut'] . '</td>
                                <td align=center>' . $r['datefin'] . '</td>
                                <td align=center>' . $r['nbjours'] . '</td>
                                <td></td></tr>';
                        
                        $solde_courant = $solde_courant - $r['nbjours'];
                    }
                    
                    $i = $i + 1;
                } else if ($row_counter <= $start_row) {
                    // Si on n'affiche pas cette ligne, on met quand à jour le solde courant
                    if ($i == 0) {
                        if ($NbJourMaladieSoustract > 0) {
                            $solde_courant = $Anciensolde - $NbJourMaladieSoustract;
                        } else {
                            $solde_courant = $Anciensolde;
                        }
                        $solde_courant = $solde_courant - $r['nbjours'];
                    } else {
                        $solde_courant = $solde_courant - $r['nbjours'];
                    }
                    $i = $i + 1;
                }
                
                // Dernière ligne avec solde final (si c'est la dernière ligne à afficher)
                if ($row_counter == $numrows && $row_counter >= $start_row && $row_counter < $end_row) {
                    echo '<tr>
                            <td align=center>' . $r['annee'] . '</td>
                            <td align=center>' . $solde_courant . '</td>
                            <td align=center></td>
                            <td align=center></td>
                            <td align=center></td>
                            <td align=center></td>
                            <td></td></tr>';
                }
            }
        } else {
            // Aucun congé pour cet agent - afficher sur la première page seulement
            if ($page == 1) {
                echo '<tr>
                        <td align=center>' . $annee . '</td>
                        <td align=center dir=rtl>(' . $Actuelsolde . '+' . $Restsolde . ')=' . $Anciensolde . '</td>
                        <td align=center> </td>
                        <td align=center> </td>
                        <td align=center> </td>
                        <td align=center> </td>
                        <td></td></tr>';
            }
        }
        
        echo '</table>';
    }
    
    $agent_count++;
}

mysqli_close($conn);
?>
</body>
</html>