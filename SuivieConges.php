<?php
// SuivieConges.php - Version unifiée
session_start();
require('DbConnexion.php');
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="JS/xlsx.full.min.js"></script>
<script src="JS/MyScript.js"></script>

<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #17a2b8;
    --medical-color: #e74c3c;
    --medical-light: #ff6b6b;
    --light-bg: #f8f9fa;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    --gradient-primary: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    --gradient-success: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    --gradient-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138d75 100%);
    --gradient-medical: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
    transition: all 0.3s ease;
}

.header-section {
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
    color: white;
    transition: all 0.3s ease;
}

/* Style spécifique pour chaque type de congé */
.header-section.type-0 { background: var(--gradient-primary); }
.header-section.type-1 { background: var(--gradient-info); }
.header-section.type-3 { background: var(--gradient-warning); }
.header-section.type-5 { background: var(--gradient-medical); }

.header-section::before {
    content: "";
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 200%;
    background: rgba(255, 255, 255, 0.1);
    transform: rotate(45deg);
}

.header-title {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    text-align: center;
    position: relative;
}

.header-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    text-align: center;
    position: relative;
}

.mobile-message {
    display: none;
    background: var(--warning-color);
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: 600;
    box-shadow: var(--card-shadow);
}

@media (max-width: 768px) {
    .mobile-message {
        display: block;
    }
}

.controls-section {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
}

.filter-section {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
}

.filter-input {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.filter-input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}

.btn-modern {
    border-radius: 12px;
    padding: 12px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: var(--hover-shadow);
}

.btn-clear {
    background: var(--light-bg);
    color: var(--primary-color);
}

.btn-clear:hover {
    background: #e9ecef;
}

.btn-export {
    background: var(--gradient-success);
    color: white;
}

.btn-export:hover {
    background: linear-gradient(135deg, #229954 0%, #27ae60 100%);
    color: white;
}

.table-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    margin-bottom: 30px;
    overflow-x: auto;
}

#myTable {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

#myTable thead {
    background: var(--gradient-primary);
    color: white;
}

#myTable th {
    padding: 16px 12px;
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    position: relative;
    transition: all 0.3s ease;
}

#myTable th:hover {
    background: rgba(255, 255, 255, 0.1);
}

#myTable tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

#myTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

#myTable td {
    padding: 14px 12px;
    text-align: center;
    vertical-align: middle;
}

/* Style pour les lignes d'arrêt maladie actives */
.current-leave {
    background: linear-gradient(90deg, rgba(231, 76, 60, 0.08) 0%, transparent 100%);
    border-right: 4px solid var(--medical-color);
    position: relative;
}

.current-leave::before {
    content: "🩺";
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
}

.comment-cell {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.comment-cell:hover {
    white-space: normal;
    overflow: visible;
    background: var(--light-bg);
    border-radius: 8px;
    padding: 10px;
    position: relative;
    z-index: 10;
    box-shadow: var(--card-shadow);
}

.badge-year {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    background: var(--gradient-info);
    color: white;
}

.duration-badge {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
}

.medical-indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--medical-color);
    display: inline-block;
    margin-right: 8px;
    animation: pulse 2s infinite;
    box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
}

@keyframes pulse {
    0% { transform: scale(0.9); opacity: 0.7; }
    50% { transform: scale(1.2); opacity: 1; }
    100% { transform: scale(0.9); opacity: 0.7; }
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #dee2e6;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 20px;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--secondary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.quick-filters {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
}

.year-filter {
    background: white;
    color: var(--primary-color);
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 10px 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.year-filter:hover, .year-filter.active {
    background: var(--gradient-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
    border-color: var(--secondary-color);
}

@media (max-width: 768px) {
    body {
        padding: 15px;
    }
    
    .header-title {
        font-size: 1.8rem;
    }
    
    .header-subtitle {
        font-size: 1rem;
    }
    
    .controls-section, .filter-section {
        padding: 15px;
    }
    
    .btn-modern {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    #myTable {
        font-size: 0.85rem;
    }
    
    #myTable th, #myTable td {
        padding: 10px 8px;
    }
    
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .comment-cell {
        max-width: 150px;
    }
}

@media (max-width: 576px) {
    body {
        padding: 10px;
    }
    
    .header-section {
        padding: 20px 15px;
    }
    
    .header-title {
        font-size: 1.5rem;
    }
    
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .btn-modern span {
        display: none;
    }
    
    .btn-modern {
        padding: 10px;
        width: 100%;
    }
    
    .comment-cell {
        max-width: 100px;
    }
}

/* Animation for table rows */
#myTable tbody tr {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Staggered animation for table rows */
#myTable tbody tr:nth-child(1) { animation-delay: 0.1s; }
#myTable tbody tr:nth-child(2) { animation-delay: 0.2s; }
#myTable tbody tr:nth-child(3) { animation-delay: 0.3s; }
#myTable tbody tr:nth-child(4) { animation-delay: 0.4s; }
#myTable tbody tr:nth-child(5) { animation-delay: 0.5s; }
#myTable tbody tr:nth-child(6) { animation-delay: 0.6s; }
#myTable tbody tr:nth-child(7) { animation-delay: 0.7s; }
#myTable tbody tr:nth-child(8) { animation-delay: 0.8s; }
#myTable tbody tr:nth-child(9) { animation-delay: 0.9s; }
#myTable tbody tr:nth-child(10) { animation-delay: 1.0s; }
</style>

<title>نظام متابعة الرخص</title>
</head>
<body>

<?php include('menu.php'); ?>

<?php
// Récupération des paramètres
$typeInput = $_GET['typeSuivie'] ?? '';
$typeInput1 = $_GET['type'] ?? '';

// Détermination du titre et du style en fonction du type
$title = '';
$subtitle = '';
$headerClass = '';

switch ($typeInput) {
    case 3:
        $title = 'متابعة المهام';
        $subtitle = 'نظام متابعة وتتبع المهام والأعمال';
        $headerClass = 'type-3';
        break;
    case 1:
        $title = 'متابعة حلقات التكوين';
        $subtitle = 'نظام متابعة حلقات التكوين والدورات التدريبية';
        $headerClass = 'type-1';
        break;
    case 0:
        $title = 'متابعة الرخص الإستثنائيّة';
        $subtitle = 'نظام متابعة الرخص الإستثنائية للموظفين';
        $headerClass = 'type-0';
        break;
    case 5:
        $title = 'متابعة الرّخص المرضيّة';
        $subtitle = 'نظام متكامل لمتابعة وإدارة الرخص المرضية للموظفين';
        $headerClass = 'type-5';
        break;
    default:
        $title = 'متابعة الرخص';
        $subtitle = 'نظام متابعة الرخص';
        $headerClass = 'type-0';
}
?>

<div class="header-section <?php echo $headerClass; ?>">
    <h1 class="header-title">
        <?php if ($typeInput == 5): ?>
            <i class="fas fa-heartbeat me-3"></i>
        <?php endif; ?>
        <?php echo $title; ?>
    </h1>
    <p class="header-subtitle"><?php echo $subtitle; ?></p>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards" id="statsContainer">
    <div class="stat-card">
        <div class="stat-value" id="totalConges">0</div>
        <div class="stat-label" id="totalLabel">إجمالي الرخص</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalEmployees">0</div>
        <div class="stat-label" id="employeesLabel">عدد الموظفين</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="currentYear"><?php echo date('Y'); ?></div>
        <div class="stat-label" id="yearLabel">السنة الحالية</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="latestYear">0</div>
        <div class="stat-label" id="latestLabel">أحدث سنة</div>
    </div>
</div>

<?php if ($typeInput == 5): ?>
<!-- Quick Year Filters (spécifique aux congés maladie) -->
<div class="quick-filters" id="yearFilters"></div>
<?php endif; ?>

<div class="controls-section">
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <button id="clearFilters" class="btn-modern btn-clear">
            <i class="fas fa-trash-alt"></i>
            <span>مسح كل الفلاتر</span>
        </button>
        
        <button onclick="exportToExcel()" class="btn-modern btn-export">
            <i class="fas fa-file-excel"></i>
            <span>تصدير إلى Excel</span>
        </button>
    </div>
</div>

<div class="filter-section">
    <div class="row g-3">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                <input type="text" id="filterMecano" class="form-control filter-input" data-column="0" placeholder="البحث بالرقم الآلي...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                <input type="text" id="filterNom" class="form-control filter-input" data-column="1" placeholder="البحث بالإسم أو اللقب...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" id="filterDateDebut" class="form-control filter-input" data-column="2" placeholder="التاريخ من...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                <input type="date" id="filterDateFin" class="form-control filter-input" data-column="3" placeholder="التاريخ إلى...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                <input type="text" id="filterAffectation" class="form-control filter-input" data-column="4" placeholder="وحدة الإرتباط...">
            </div>
        </div>
        <?php if ($typeInput != 5): ?>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-comment"></i></span>
                <input type="text" id="filterComment" class="form-control filter-input" data-column="5" placeholder="الملاحظات...">
            </div>
        </div>
        <?php else: ?>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-clock"></i></span>
                <input type="number" id="filterJours" class="form-control filter-input" data-column="5" placeholder="عدد الأيام...">
            </div>
        </div>
        <?php endif; ?>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar"></i></span>
                <input type="text" id="filterAnnee" class="form-control filter-input" data-column="<?php echo ($typeInput != 5) ? '6' : '6'; ?>" placeholder="السنة...">
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <table id="myTable" dir="rtl">
        <thead>
            <tr class="header">
                <th style="width:10%;">الرقم الآلي</th>
                <th style="width:15%;">الإسم و اللقب</th>
                <th style="width:10%;">التاريخ من</th>
                <th style="width:10%;">التاريخ إلى</th>
                <th style="width:10%;">عدد الأيام</th>
                <th style="width:15%;">وحدة الإرتباط</th>
                <?php if ($typeInput != 5): ?>
                <th style="width:25%;">ملاحظات</th>
                <?php endif; ?>
                <th style="width:5%;">السنة</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($_SESSION['congidGA'])) {
                $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
                $totalConges = 0;
                $totalEmployees = 0;
                $latestYear = 0;
                $totalDays = 0;
                $currentYearLeaves = 0;
                $uniqueDepartments = [];
                $employees = [];
                $currentYear = date('Y');
                $uniqueYears = [];

                // Construction de la requête en fonction du type
                if ($typeInput == 5) {
                    // Pour les congés maladie
                    if ($_SESSION['departement'] !== "admin") {
                        $placeholders = implode(',', array_fill(0, count($departements), '?'));
                        $types = str_repeat('i', count($departements));
                        $query = "
                            SELECT 
                                autreconge.mecano, 
                                nom, 
                                datedebut, 
                                datefin, 
                                (DATEDIFF(datefin, datedebut) + 1) as nbj,
                                dep.depar, 
                                anne
                            FROM autreconge
                            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                            LEFT JOIN dep ON stuf.dep = dep.id
                            WHERE type = 5 AND contrastage IN (0,1,3) 
                            AND stuf.dep IN ($placeholders)
                            ORDER BY anne DESC, datedebut DESC
                        ";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, $types, ...$departements);
                    } else {
                        $stmt = mysqli_prepare($connection, "
                            SELECT 
                                autreconge.mecano, 
                                nom, 
                                datedebut, 
                                datefin, 
                                (DATEDIFF(datefin, datedebut) + 1) as nbj,
                                dep.depar, 
								commentaire, 
                                anne
                            FROM autreconge
                            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                            LEFT JOIN dep ON stuf.dep = dep.id
                            WHERE type = 5 AND contrastage IN (0,1,3)
                            ORDER BY anne DESC, datedebut DESC
                        ");
                    }
                } else {
                    // Pour les autres types (0, 1, 3)
                    if ($_SESSION['departement'] !== "admin") {
                        $placeholders = implode(',', array_fill(0, count($departements), '?'));
                        $types = str_repeat('i', count($departements));
                        $query = "
                            SELECT 
                                autreconge.mecano, 
                                nom, 
                                datedebut, 
                                datefin, 
                                nbj,
                                dep.depar, 
                                commentaire, 
                                anne
                            FROM autreconge
                            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                            LEFT JOIN dep ON stuf.dep = dep.id
                            WHERE type = ($typeInput1) AND type2 IN ($typeInput)
                            AND contrastage IN (0,1,3) 
                            AND stuf.dep IN ($placeholders)
                            ORDER BY anne DESC
                        ";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, $types, ...$departements);
                    } else {
                        $stmt = mysqli_prepare($connection, "
                            SELECT 
                                autreconge.mecano, 
                                nom, 
                                datedebut, 
                                datefin, 
                                nbj,
                                dep.depar, 
                                commentaire, 
                                anne
                            FROM autreconge
                            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                            LEFT JOIN dep ON stuf.dep = dep.id
                            WHERE type = ($typeInput1) AND type2 IN ($typeInput)
                            AND contrastage IN (0,1,3)
                            ORDER BY anne DESC
                        ");
                    }
                }

                if ($stmt && mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_bind_result($stmt, $mecano, $nom, $datedebut, $datefin, $nbj, $depar, $commentaire, $anne);
                    
                    while (mysqli_stmt_fetch($stmt)) {
                        $totalConges++;
                        $totalDays += intval($nbj);
                        
                        if (!in_array($mecano, $employees)) {
                            $employees[] = $mecano;
                            $totalEmployees = count($employees);
                        }
                        
                        if (!in_array($depar, $uniqueDepartments)) {
                            $uniqueDepartments[] = $depar;
                        }
                        
                        if ($anne == $currentYear) {
                            $currentYearLeaves++;
                        }
                        
                        if ($anne > $latestYear) {
                            $latestYear = $anne;
                        }
                        
                        if (!in_array($anne, $uniqueYears)) {
                            $uniqueYears[] = $anne;
                        }
                        
                        // Vérifier si c'est un congé en cours (pour maladie)
                        $isCurrentLeave = ($typeInput == 5 && date('Y-m-d') >= $datedebut && date('Y-m-d') <= $datefin);
                        $rowClass = $isCurrentLeave ? 'current-leave' : '';
                        
                        echo '<tr class="' . $rowClass . '">';
                        echo '<td>' . htmlspecialchars($mecano);
                        if ($isCurrentLeave && $typeInput == 5) {
                            echo '<span class="medical-indicator" title="إجازة مرضية حالية"></span>';
                        }
                        echo '</td>';
                        echo '<td dir="rtl">' . htmlspecialchars($nom) . '</td>';
                        echo '<td>' . htmlspecialchars($datedebut) . '</td>';
                        echo '<td>' . htmlspecialchars($datefin) . '</td>';
                        
                        if ($typeInput == 5) {
                            echo '<td><span class="duration-badge">' . htmlspecialchars($nbj) . ' يوم</span></td>';
                        } else {
                            echo '<td>' . htmlspecialchars($nbj) . '</td>';
                        }
                        
                        echo '<td>' . htmlspecialchars($depar) . '</td>';
                        
                        if ($typeInput != 5) {
                            echo '<td class="comment-cell">' . htmlspecialchars($commentaire) . '</td>';
                        }
                        
                        echo '<td><span class="badge-year">' . htmlspecialchars($anne) . '</span></td>';
                        echo '</tr>';
                    }
                }
                
                mysqli_stmt_close($stmt);
                mysqli_close($connection);
                
                // Affichage des statistiques JavaScript
                $averageDays = $totalConges > 0 ? round($totalDays / $totalConges, 1) : 0;
                
                if ($typeInput == 5) {
                    // Statistiques spécifiques maladie
                    echo '<script>
                        document.getElementById("totalLabel").textContent = "إجمالي الرخص المرضية";
                        document.getElementById("employeesLabel").textContent = "عدد الموظفين المرضى";
                        document.getElementById("yearLabel").textContent = "رخص السنة الحالية";
                        document.getElementById("latestLabel").textContent = "متوسط مدة الرخص";
                        document.getElementById("totalConges").textContent = "' . $totalConges . '";
                        document.getElementById("totalEmployees").textContent = "' . $totalEmployees . '";
                        document.getElementById("currentYear").textContent = "' . $currentYearLeaves . '";
                        document.getElementById("latestYear").textContent = "' . $averageDays . '";
                        
                        // Populate year filters
                        const uniqueYears = ' . json_encode($uniqueYears) . ';
                        let yearFiltersHTML = "";
                        uniqueYears.sort().reverse().forEach(year => {
                            const isCurrentYear = year == ' . $currentYear . ';
                            yearFiltersHTML += `<button class="year-filter ${isCurrentYear ? "active" : ""}" data-year="${year}">${year}</button>`;
                        });
                        document.getElementById("yearFilters").innerHTML = yearFiltersHTML;
                    </script>';
                } else {
                    echo '<script>
                        document.getElementById("totalLabel").textContent = "إجمالي الرخص";
                        document.getElementById("employeesLabel").textContent = "عدد الموظفين";
                        document.getElementById("yearLabel").textContent = "السنة الحالية";
                        document.getElementById("latestLabel").textContent = "أحدث سنة";
                        document.getElementById("totalConges").textContent = "' . $totalConges . '";
                        document.getElementById("totalEmployees").textContent = "' . $totalEmployees . '";
                        document.getElementById("latestYear").textContent = "' . $latestYear . '";
                    </script>';
                }
            } else {
                echo '<script language="Javascript">document.location.replace("index.php");</script>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Variables globales pour les types
const currentType = <?php echo $typeInput; ?>;
const isMedicalType = (currentType == 5);

function filterTable() {
    var filters = {};
    $('.filter-input').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    var visibleRows = 0;
    var employees = [];
    var totalDays = 0;
    var latestYear = 0;
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        for (var col in filters) {
            if (filters.hasOwnProperty(col)) {
                var cellText = cells.eq(col).text().toLowerCase();
                if (cellText.indexOf(filters[col]) === -1) {
                    showRow = false;
                    break;
                }
            }
        }
        
        $(this).toggle(showRow);
        
        if (showRow) {
            visibleRows++;
            var days = parseInt(cells.eq(4).text()) || 0;
            totalDays += days;
            
            var mecano = cells.eq(0).text();
            if (!employees.includes(mecano)) {
                employees.push(mecano);
            }
            
            var yearText = cells.eq(isMedicalType ? 6 : 7).text();
            var year = parseInt(yearText);
            if (year > latestYear) latestYear = year;
        }
    });
    
    // Update statistics after filtering
    if (isMedicalType) {
        var avgDays = visibleRows > 0 ? Math.round(totalDays / visibleRows) : 0;
        document.getElementById("totalConges").textContent = visibleRows;
        document.getElementById("totalEmployees").textContent = employees.length;
        document.getElementById("latestYear").textContent = avgDays;
    } else {
        document.getElementById("totalConges").textContent = visibleRows;
        document.getElementById("totalEmployees").textContent = employees.length;
        document.getElementById("latestYear").textContent = latestYear;
    }
}

// Fonction d'export améliorée
function exportToExcel() {
    const table = document.getElementById('myTable');
    const rows = table.querySelectorAll('tr');
    let csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll('td, th');
        for (let j = 0; j < cols.length; j++) {
            let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").trim();
            row.push('"' + text + '"');
        }
        csv.push(row.join(","));
    }
    
    let csvString = csv.join('\n');
    let blob = new Blob(["\uFEFF" + csvString], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement("a");
    let url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "Liste_RH.xlsx");
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Appliquer les filtres
$('.filter-input').on('keyup change', filterTable);

// Nettoyer les filtres
$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    if (isMedicalType) {
        $('.year-filter').removeClass('active');
        $('.year-filter[data-year="' + new Date().getFullYear() + '"]').addClass('active');
    }
    filterTable();
});

// Filtre par année (pour maladie)
$(document).on('click', '.year-filter', function() {
    $('.year-filter').removeClass('active');
    $(this).addClass('active');
    $('#filterAnnee').val($(this).data('year'));
    filterTable();
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('#myTable tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = (index * 0.1) + 's';
    });
    
    if (isMedicalType) {
        const currentYear = new Date().getFullYear();
        if ($('.year-filter.active').length === 0) {
            $('.year-filter[data-year="' + currentYear + '"]').addClass('active');
        }
    }
});
</script>

</body>
</html>