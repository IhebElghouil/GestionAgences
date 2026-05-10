<?php
require 'DbConnexion.php';

$row = $_POST['row'] ?? 'region';
$col = $_POST['col'] ?? 'etat';

// Définir les valeurs de contrastage à inclure (toutes sauf Inactif)
$valid_contratsage = [0, 1, 3, 5, 6]; // Valeurs qui ont des libellés spécifiques

// Définir le CASE statement pour le mapping contratsage
$contratsage_case = "
    CASE s.contrastage
        WHEN 0 THEN 'مترسّم'
        WHEN 1 THEN 'متربص'
        WHEN 3 THEN 'ملحق لدى الشركة'
        WHEN 5 THEN 'ملحق خارج الشركة'
        WHEN 6 THEN 'إحالة على عدم المباشرة الخاصة'
    END";

// Requête pivot dynamique avec filtre pour exclure Inactif
$sql = "
SELECT 
    " . ($row == 'region' ? 'd.depar' : ($row == 'service' ? 'sv.libellet' : $contratsage_case)) . " as row_label,
    " . ($col == 'region' ? 'd.depar' : ($col == 'service' ? 'sv.libellet' : $contratsage_case)) . " as col_label,
    COUNT(*) as total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
LEFT JOIN service sv ON s.idservice = sv.id
WHERE s.contrastage IN (" . implode(',', $valid_contratsage) . ")
GROUP BY row_label, col_label
ORDER BY row_label, col_label
";

$result = $conn->query($sql);
$pivot = [];
$rows = [];
$cols = [];

if ($result && $result->num_rows > 0) {
    while($row_data = $result->fetch_assoc()) {
        $row_label = $row_data['row_label'] ?? 'Unknown';
        $col_label = $row_data['col_label'] ?? 'Unknown';
        $total = $row_data['total'];
        
        // Ne pas inclure si le label est NULL ou vide
        if ($row_label && $col_label) {
            $rows[$row_label] = true;
            $cols[$col_label] = true;
            
            if (!isset($pivot[$row_label])) {
                $pivot[$row_label] = [];
            }
            
            $pivot[$row_label][$col_label] = $total;
        }
    }
}

// Alternative: Si vous voulez aussi exclure les lignes/colonnes "Inactif" du résultat
// même si elles apparaissent à cause d'autres conditions
$row_keys = array_keys($rows);
$col_keys = array_keys($cols);

// Filtrer pour enlever tout label "Inactif" qui pourrait encore apparaître
$row_keys = array_filter($row_keys, function($key) {
    return $key !== 'Inactif' && $key !== null && $key !== '';
});

$col_keys = array_filter($col_keys, function($key) {
    return $key !== 'Inactif' && $key !== null && $key !== '';
});

sort($row_keys);
sort($col_keys);

// Générer le tableau HTML
$html = '<div class="table-responsive">';
$html .= '<table class="table table-bordered table-striped table-hover">';
$html .= '<thead class="table-dark">';
$html .= '<tr>';
$html .= '<th>' . htmlspecialchars(ucfirst($row)) . ' ⬇️ / ' . htmlspecialchars(ucfirst($col)) . ' ➡️</th>';

foreach($col_keys as $col_label) {
    $html .= '<th>' . htmlspecialchars($col_label) . '</th>';
}
$html .= '<th>Total</th>';
$html .= '</tr>';
$html .= '</thead>';

$html .= '<tbody>';
$grand_total = 0;

foreach($row_keys as $row_label) {
    $html .= '<tr>';
    $html .= '<td><strong>' . htmlspecialchars($row_label) . '</strong></td>';
    
    $row_total = 0;
    foreach($col_keys as $col_label) {
        $val = $pivot[$row_label][$col_label] ?? 0;
        $html .= '<td class="text-center">' . number_format($val, 0, ',', ' ') . '</td>';
        $row_total += $val;
    }
    
    $grand_total += $row_total;
    $html .= '<td class="text-center"><strong>' . number_format($row_total, 0, ',', ' ') . '</strong></td>';
    $html .= '</tr>';
}

$html .= '</tbody>';

// Pied de tableau avec totaux par colonne
if (!empty($col_keys) && !empty($row_keys)) {
    $html .= '<tfoot class="table-primary">';
    $html .= '<tr>';
    $html .= '<th>Total</th>';
    
    foreach($col_keys as $col_label) {
        $col_total = 0;
        foreach($row_keys as $row_label) {
            $col_total += $pivot[$row_label][$col_label] ?? 0;
        }
        $html .= '<th class="text-center">' . number_format($col_total, 0, ',', ' ') . '</th>';
    }
    
    $html .= '<th class="text-center">' . number_format($grand_total, 0, ',', ' ') . '</th>';
    $html .= '</tr>';
    $html .= '</tfoot>';
}

$html .= '</table>';
$html .= '</div>';

// Ajouter un résumé
$html .= '<div class="alert alert-success mt-3">';
$html .= '<i class="fas fa-check-circle"></i> ';
$html .= 'Total des enregistrements actifs: <strong>' . number_format($grand_total, 0, ',', ' ') . '</strong><br>';
$html .= 'Répartition par ' . htmlspecialchars($row) . ' : <strong>' . count($row_keys) . '</strong> catégories<br>';
$html .= 'Répartition par ' . htmlspecialchars($col) . ' : <strong>' . count($col_keys) . '</strong> catégories';
$html .= '</div>';

echo $html;
?>