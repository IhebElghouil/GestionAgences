<?php
session_start();
require('connection.php');

if (!isset($_SESSION['congidGA'])) {
    echo '<tr><td colspan="15" class="text-center text-danger">الرجاء تسجيل الدخول</td></tr>';
    exit;
}
   $doc_count_query = "SELECT detachement_id, COUNT(*) as doc_count FROM detachement_documents GROUP BY detachement_id";
                $doc_count_result = mysqli_query($connection, $doc_count_query);
                $doc_counts = [];
                if ($doc_count_result) {
                    while ($dc = mysqli_fetch_assoc($doc_count_result)) {
                        $doc_counts[$dc['detachement_id']] = $dc['doc_count'];
                    }
                }
                
                $query = "SELECT id, mecano, nomprenom, affectation, source, situation, datedetachement, periode, 
                                 renouvellemnt1, renouvellemnt2, renouvellemnt3, dossier, observations, statut,
                                 DATE_ADD(datedetachement, INTERVAL (COALESCE(NULLIF(TRIM(periode), ''), 0) + 
                                 COALESCE(NULLIF(TRIM(renouvellemnt1), ''), 0) + 
                                 COALESCE(NULLIF(TRIM(renouvellemnt2), ''), 0) + 
                                 COALESCE(NULLIF(TRIM(renouvellemnt3), ''), 0)) YEAR) AS date_fin
                          FROM detachement 
                          ORDER BY CASE WHEN statut = 1 THEN 1 ELSE 0 END";
                
                $result = mysqli_query($connection, $query);
                
                if (!$result) {
                    echo '<tr><td colspan="15" class="text-center text-danger">خطأ في الاستعلام: ' . mysqli_error($connection) . '</td></tr>';
                } elseif (mysqli_num_rows($result) == 0) {
                    echo '<tr><td colspan="15" class="text-center text-muted">لا توجد بيانات</td></tr>';
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
    $formattedDate = !empty($row['datedetachement']) ? date('Y-m-d', strtotime($row['datedetachement'])) : '';
    $dateFinObj = new DateTime($row['date_fin']);
    $aujourdhui = new DateTime();
    $interval = $aujourdhui->diff($dateFinObj);
    $moisRestants = ($interval->y * 12) + $interval->m;
    
    if ($row['statut'] == 1) {
        $date_fin_class = 'months-red';
        $moisText = 'متابعة موقوفة';
        $monthsLeftCategory = 'stopped';
        $row_class = 'stopped-followup';
    } else {
        $row_class = '';
        if ($dateFinObj < $aujourdhui) {
            $date_fin_class = 'months-red';
            $moisText = 'منتهي';
            $monthsLeftCategory = 'expired';
        } elseif ($moisRestants < 3) {
            $date_fin_class = 'months-yellow';
            $moisText = $moisRestants . ' شهر';
            $monthsLeftCategory = 'less3';
        } elseif ($moisRestants <= 6) {
            $date_fin_class = 'months-yellow';
            $moisText = $moisRestants . ' شهر';
            $monthsLeftCategory = '3to6';
        } else {
            $date_fin_class = 'months-green';
            $moisText = $moisRestants . ' شهر';
            $monthsLeftCategory = 'more6';
        }
    }
    
    $docCount = isset($doc_counts[$row['id']]) ? $doc_counts[$row['id']] : 0;
    
    echo '<tr class="fade-in ' . $row_class . '" data-months-left="' . $monthsLeftCategory . '" data-statut="' . $row['statut'] . '">';
    echo '<td><span class="badge badge-info">' . htmlspecialchars($row['mecano']) . '</span></td>';
    echo '<td>' . htmlspecialchars($row['nomprenom']) . '</td>';
    echo '<td>' . htmlspecialchars($row['affectation']) . '</td>';
    echo '<td>' . htmlspecialchars($row['source']) . '</td>';
    
    $badge_class = 'badge-primary';
    if ($row['situation'] == 'ملحق خارج الشركة') $badge_class = 'badge-warning';
    if ($row['situation'] == 'ملحق لدى الشركة') $badge_class = 'badge-success';
    if ($row['situation'] == 'إنتهت الإعارة') $badge_class = 'badge-secondary';
    if ($row['situation'] == 'إحالة على عدم المباشرة') $badge_class = 'badge-danger';
    
    echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($row['situation']) . '</span></td>';
    echo '<td>' . htmlspecialchars($formattedDate) . '</td>';
    echo '<td>' . htmlspecialchars($row['periode']) . '</td>';
    echo '<td>' . htmlspecialchars($row['renouvellemnt1']) . '</td>';
    echo '<td>' . htmlspecialchars($row['renouvellemnt2']) . '</td>';
    echo '<td>' . htmlspecialchars($row['renouvellemnt3']) . '</td>';
    echo '<td><span class="months-left ' . $date_fin_class . '">' . $moisText . '</span></td>';
    
    // Cellule dossier avec style conditionnel
    $dossier_value = htmlspecialchars($row['dossier']);
$dossier_class = '';

if ($dossier_value === 'مؤشّر من رئاسة الحكومة') {
    $dossier_class = 'dossier-green';
} else {
    $dossier_class = 'dossier-red';
}

echo '<td><span class="months-left ' . $dossier_class . '">' . $dossier_value . '</span></td>';
    
    echo '<td>' . htmlspecialchars($row['observations']) . '</td>';
    echo '<td>' . ($row['statut'] == 1 ? '<span class="badge badge-danger">متابعة موقوفة</span>' : '<span class="badge badge-success">قيد المتابعة</span>') . '</td>';
    
    if (isset($_SESSION['departement']) && $_SESSION['departement'] === "admin") {
        echo '<td><div class="action-buttons-table">';
        echo '<button class="btn btn-warning btn-sm edit-detachement" 
                data-id="' . $row['id'] . '" 
                data-mecano="' . htmlspecialchars($row['mecano']) . '" 
                data-nomprenom="' . htmlspecialchars($row['nomprenom']) . '" 
                data-affectation="' . htmlspecialchars($row['affectation']) . '" 
                data-source="' . htmlspecialchars($row['source']) . '" 
                data-situation="' . htmlspecialchars($row['situation']) . '" 
                data-datedetachement="' . $formattedDate . '" 
                data-periode="' . $row['periode'] . '" 
                data-renouvellemnt1="' . $row['renouvellemnt1'] . '" 
                data-renouvellemnt2="' . $row['renouvellemnt2'] . '" 
                data-renouvellemnt3="' . $row['renouvellemnt3'] . '" 
                data-dossier="' . htmlspecialchars($row['dossier']) . '" 
                data-observations="' . htmlspecialchars($row['observations']) . '" 
                data-statut="' . $row['statut'] . '">
                <i class="fas fa-edit"></i>
              </button>';
        
        // Document button with count badge
        echo '<button class="btn btn-info btn-sm view-documents" data-id="' . $row['id'] . '" data-nomprenom="' . htmlspecialchars($row['nomprenom']) . '">';
        echo '<i class="fas fa-paperclip"></i>';
        if ($docCount > 0) {
            echo '<span class="doc-count-badge">' . $docCount . '</span>';
        }
        echo '</button>';
        
        echo '<button class="btn btn-danger btn-sm delete-detachement" data-id="' . $row['id'] . '" data-nomprenom="' . htmlspecialchars($row['nomprenom']) . '">
                <i class="fas fa-trash"></i>
              </button>';
              
        if ($row['statut'] != 1) {
            echo '<button class="btn btn-sm btn-success" onclick="closedetachement(' . $row['id'] . ')">
                    <i class="fas fa-lock"></i>
                  </button>';
        }
        echo '</div></td>';
    }
    echo '</tr>';
	?>