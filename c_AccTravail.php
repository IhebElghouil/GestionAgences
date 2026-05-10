<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!--   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/xlsx.full.min.js"></script>
<script src="JS/MyScript.js"></script>
<?php
session_start();
?>
<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #17a2b8;
    --light-bg: #f8f9fa;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    --gradient-primary: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    --gradient-success: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    --gradient-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138d75 100%);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
    transition: all 0.3s ease;
}

.header-section {
    background: var(--gradient-primary);
    color: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
}

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

.filter-input, .filter-select {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.filter-input:focus, .filter-select:focus {
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

.btn-print {
    background: var(--gradient-info);
    color: white;
}

.btn-print:hover {
    background: linear-gradient(135deg, #138d75 0%, #117a65 100%);
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

/* Status badges */
.status-expired {
    background: var(--gradient-danger);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}

.status-expiring {
    background: var(--gradient-warning);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}

.status-valid {
    background: var(--gradient-success);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}

.status-invalid {
    background: var(--gradient-info);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}

/* Row status colors */
.tr-expired {
    background: linear-gradient(135deg, #e54342 0%, #c0392b 100%) !important;
    color: white;
    border-left: 4px solid #c0392b;
}

.tr-expired:hover {
    background: linear-gradient(135deg, #d13232 0%, #a93226 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 67, 66, 0.3);
}

.tr-expiring {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white;
    border-left: 4px solid #e67e22;
}

.tr-expiring:hover {
    background: linear-gradient(135deg, #e67e22 0%, #d35400 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(243, 156, 18, 0.3);
}

.tr-today {
    background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%) !important;
    color: #333;
    border-left: 4px solid #f39c12;
}

.tr-today:hover {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(241, 196, 15, 0.3);
}

.comment-cell {
    max-width: 200px;
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

/* Badges pour les types d'accidents */
.badge-primary {
    background: var(--gradient-primary);
    color: white;
}

.badge-warning {
    background: var(--gradient-warning);
    color: white;
}

.badge-danger {
    background: var(--gradient-danger);
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.badge-success {
    background: var(--gradient-success);
    color: white;
}

.badge-info {
    background: var(--gradient-info);
    color: white;
}

.badge-accident {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    color: white;
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

/* Tooltip styles */
.tooltip-wrapper {
    position: relative;
    display: inline-block;
}

.tooltip-content {
    visibility: hidden;
    width: 300px;
    background: white;
    color: #333;
    text-align: center;
    border-radius: 8px;
    padding: 12px;
    position: absolute;
    z-index: 1000;
    bottom: 125%;
    right: 50%;
    transform: translateX(50%);
    box-shadow: var(--hover-shadow);
    border: 1px solid #e9ecef;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip-wrapper:hover .tooltip-content {
    visibility: visible;
    opacity: 1;
}

.tooltip-content strong {
    color: var(--danger-color);
    display: block;
    margin-bottom: 8px;
}

/* Modal styles */
.modal {
    z-index: 9999;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: var(--hover-shadow);
}

.btn-edit {
    background: var(--gradient-info);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.btn-edit:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, #138d75 0%, #117a65 100%);
}

/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    .table-container, .table-container * {
        visibility: visible;
    }
    
    .table-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        border-radius: 0;
    }
    
    .no-print {
        display: none !important;
    }
    
    #myTable {
        font-size: 12px;
    }
    
    #myTable th, #myTable td {
        padding: 8px 6px;
    }
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
    
    .tooltip-content {
        width: 250px;
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
    
    .tooltip-content {
        width: 200px;
        font-size: 0.8rem;
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


<title>Accidents de Travail</title>
</head>
<body>
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="editModalContent">
      <!-- Le contenu AJAX sera injecté ici -->
    </div>
  </div>
</div>

<?php include('menu.php'); ?>

<div class="header-section">
    <h1 class="header-title">متابعة حوادث الشغل</h1>
    <p class="header-subtitle">عرض شامل لحوادث الشغل والرخص الطبية المرتبطة بها</p>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-value" id="totalAccidents">0</div>
        <div class="stat-label">إجمالي الحوادث</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="expiredCount">0</div>
        <div class="stat-label">منتهية الصلاحية</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="expiringCount">0</div>
        <div class="stat-label">قريبة الإنتهاء</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="currentYear"><?php echo date('Y'); ?></div>
        <div class="stat-label">السنة الحالية</div>
    </div>
</div>

<div class="controls-section">
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <button id="clearFilters" class="btn-modern btn-clear">
            <i class="fas fa-trash-alt"></i>
            <span>مسح كل الفلاتر</span>
        </button>
        
        <div class="d-flex gap-2">
            <button onclick="exportTableToExcel('myTable', 'ListeAT.xlsx')" class="btn-modern btn-export">
                <i class="fas fa-file-excel"></i>
                <span>تصدير إلى Excel</span>
            </button>
            
            <button onclick="printTable()" class="btn-modern btn-print">
                <i class="fas fa-print"></i>
                <span>طباعة</span>
            </button>
        </div>
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
                <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                <input type="text" id="filterDateDebut" class="form-control filter-input" data-column="2" placeholder="البحث بالرتبة...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                <input type="date" id="filterAffectation" class="form-control filter-input" data-column="3" placeholder="البحث بالتاريخ من...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                <input type="date" id="filterObservation" class="form-control filter-input" data-column="4" placeholder="البحث بالتاريخ إلى...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-shield-alt"></i></span>
                <input type="text" id="filterDateFin" class="form-control filter-input" data-column="6" placeholder="البحث برقم الضمان الإجتماعي...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                <input type="text" id="filterUnite" class="form-control filter-input" data-column="7" placeholder="البحث بوحدة الإرتباط...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-ambulance"></i></span>
                <select id="filterTypeAccident" class="form-control filter-select" data-column="8">
                    <option value="">جميع أنواع الحوادث</option>
                    <option value="تمديد">تمديد</option>
                    <option value="انتكاسة">انتكاسة</option>
                    <option value="حادث أولي">حادث أولي</option>
                    <option value="غير محدد">غير محدد</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-calendar"></i></span>
                <input type="text" id="filterAnnee" class="form-control filter-input" data-column="10" placeholder="البحث بالسنة...">
            </div>
        </div>
        <!-- Nouveaux filtres ajoutés -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-filter"></i></span>
                <select id="filterAgence" class="form-control filter-select" data-column="7">
                    <option value="">جميع الوحدات</option>
                    <?php
                    if (isset($_SESSION['congidGA'])) {
                        include('DbConnexion.php');
                          $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
                        
                        if ($_SESSION['departement'] === "admin") {
                            $sql = "SELECT id, depar FROM dep ORDER BY depar";
                            $result = mysqli_query($connection, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['depar'] . "'>" . $row['depar'] . "</option>";
                            }
                        } else {
							$placeholders = implode(',', array_fill(0, count($departements), '?'));
                            $types = str_repeat('i', count($departements));
								
                            $sql = "SELECT id, depar FROM dep WHERE id IN ($placeholders)";
                            $stmt = mysqli_prepare($connection, $sql);
                            mysqli_stmt_bind_param($stmt, $types, ...$departements);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['depar'] . "'>" . $row['depar'] . "</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-check-circle"></i></span>
                <select id="filterValidite" class="form-control filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="valide">صالحة</option>
                    <option value="expire">منتهية الصلاحية</option>
                    <option value="expire_soon">قريبة الإنتهاء</option>
                    <option value="invalide">غير صالحة</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <table id="myTable" dir="rtl">
        <thead>
            <tr class="header">
                <th style="width:7%;">الرقم الآلي</th>
                <th style="width:12%;">الإسم و اللقب</th>
                <th style="width:15%;">الرتبة</th>
                <th style="width:9%;">التاريخ من</th>
                <th style="width:9%;">التاريخ إلى</th>
                <th style="width:7%;">عدد الأيام</th>
                <th style="width:10%;">رقم الضمان الإجتماعي</th>
                <th style="width:10%;">وحدة الإرتباط</th>
                <th style="width:10%;">نوع الحادث</th>
                <th style="width:15%;">ملاحظات</th>
                <th style="width:5%;">السنة</th>
                <?php
                if ($_SESSION['departement']=="admin") {
                    echo '<th style="width:5%;">إجراءات</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($_SESSION['congidGA'])) {
                $departement = $_SESSION['departement'];
                include('DbConnexion.php');

                $totalAccidents = 0;
                $expiredCount = 0;
                $expiringCount = 0;

                // Prepare query based on whether the user is admin or not
                if ($departement !== "admin") {
                    $query = "
                        SELECT autreconge.mecano, nom, datedebut, datefin, dep.depar, commentaire, anne, titres.libellet, autreconge.id, valide, DATEDIFF(datefin, CURRENT_DATE()), ncnss, autreconge.type2
                        FROM autreconge
                        LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                        LEFT JOIN social ON stuf.mecano = social.mecano
                        LEFT JOIN titres ON titres.id = stuf.titre
                        LEFT JOIN dep ON stuf.dep = dep.id
                        WHERE type = 6 AND contrastage IN (0, 1, 3) AND stuf.dep IN ($placeholders)
                        ORDER BY valide DESC, datefin DESC
                    ";
					$stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, $types, ...$departements);
                } else {
                    $stmt = mysqli_prepare($connection, "
                        SELECT autreconge.mecano, nom, datedebut, datefin, dep.depar, commentaire, anne, titres.libellet, autreconge.id, valide, DATEDIFF(datefin, CURRENT_DATE()), ncnss, autreconge.type2
                        FROM autreconge
                        LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                        LEFT JOIN social ON stuf.mecano = social.mecano
                        LEFT JOIN titres ON titres.id = stuf.titre
                        LEFT JOIN dep ON stuf.dep = dep.id
                        WHERE type = 6 AND contrastage IN (0, 1, 3)
                        ORDER BY valide DESC, datefin DESC
                    ");
                }

                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $mecano, $nom, $datedebut, $datefin, $depar, $commentaire, $anne, $libellet, $id, $valide, $datefin_diff, $ncnss, $type2);

                while (mysqli_stmt_fetch($stmt)) {
                    $totalAccidents++;
                    
                    // Calculate the date difference
                    $DebutDate = $datedebut;
                    $FinDate = $datefin;
                    $daydiff = floor((abs(strtotime($FinDate) - strtotime($DebutDate)) / (60 * 60 * 24))) + 1;

                    // Déterminer le type d'accident
                    $typeAccident = '';
                    $badgeClass = '';
                    
                    switch($type2) {
                        case 9:
                            $typeAccident = 'تمديد';
                            $badgeClass = 'badge-warning badge-accident';
                            break;
                        case 10:
                            $typeAccident = 'انتكاسة';
                            $badgeClass = 'badge-danger badge-accident';
                            break;
                        case 11:
                            $typeAccident = 'حادث أولي';
                            $badgeClass = 'badge-primary badge-accident';
                            break;
                        default:
                            $typeAccident = 'غير محدد';
                            $badgeClass = 'badge-secondary badge-accident';
                            break;
                    }

                    // Determine row class based on date conditions
                    $rowClass = '';
                    $tooltipText = '';
                    $statusClass = '';
                    
                    if (($datefin_diff < 0) && ($valide == 1)) {
                        $rowClass = 'tr-expired';
                        $statusClass = 'status-expired';
                        $tooltipText = 'إنتهت صلوحية الشهادة الطبية لحادث الشغل';
                        $expiredCount++;
                    } elseif (($datefin_diff == 0) && ($valide == 1)) {
                        $rowClass = 'tr-today';
                        $statusClass = 'status-expiring';
                        $tooltipText = 'تنتهي صلوحية الشهادة الطبية لحادث الشغل اليوم';
                        $expiringCount++;
                    } elseif (($datefin_diff >= 1) && ($datefin_diff <= 10) && ($valide == 1)) {
                        $rowClass = 'tr-expiring';
                        $statusClass = 'status-expiring';
                        $jlettre = ($datefin_diff < 11) ? "أيّام" : "يوما";
                        $tooltipText = 'تنتهي صلوحية الشهادة الطبية لحادث الشغل خلال ' . $datefin_diff . ' ' . $jlettre;
                        $expiringCount++;
                    } elseif ($valide == 1) {
                        $statusClass = 'status-valid';
                    } else {
                        $statusClass = 'status-invalid';
                    }

                    echo "<tr class='$rowClass' data-status='$statusClass'>";
                    echo "<td>";
                    echo "<div class='tooltip-wrapper'>";
                    echo "<span class='spnDetails'>$mecano</span>";
                    if ($tooltipText) {
                        echo "<div class='tooltip-content'>";
                        echo "<strong>تنبيه</strong>";
                        echo "$tooltipText";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</td>";
                    
                    echo "<td dir='rtl'>$nom</td>";
                    echo "<td>$libellet</td>";
                    echo "<td>$datedebut</td>";
                    echo "<td>$datefin</td>";
                    echo "<td><span class='badge-year'>$daydiff</span></td>";
                    echo "<td>$ncnss</td>";
                    echo "<td>$depar</td>";
                    // Nouvelle colonne pour le type d'accident
                    echo "<td><span class='$badgeClass'>$typeAccident</span></td>";
                    echo "<td class='comment-cell'>$commentaire</td>";
                    echo "<td>$anne</td>";

                    if ($_SESSION['departement'] == "admin") {
                        echo '<td>
                            <a href="#" 
                               class="btn-edit edit-user-btn" 
                               data-mecano="' . htmlspecialchars($id) . '" 
                               data-type="' . htmlspecialchars(3) . '">
                               <i class="fas fa-edit"></i>
                            </a>
                        </td>';
                    }

                    echo "</tr>";
                }

                // Store statistics for JavaScript
                echo '<script>
                    document.getElementById("totalAccidents").textContent = "' . $totalAccidents . '";
                    document.getElementById("expiredCount").textContent = "' . $expiredCount . '";
                    document.getElementById("expiringCount").textContent = "' . $expiringCount . '";
                </script>';

                mysqli_stmt_close($stmt);
            } else {
                echo '<script language="Javascript">
                    document.location.replace("index.php");
                </script>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('.edit-user-btn').on('click', function(e) {
        e.preventDefault();
        const mecano = $(this).data('mecano');
        const type = $(this).data('type');

        $('#editModalContent').load('page.php?mecano=' + encodeURIComponent(mecano) + '&type=' + encodeURIComponent(type), function() {
            $('#editModal').modal('show');
        });
    });
});

function filterTable() {
    var filters = {};
    $('.filter-input').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    // Filtre par agence
    var agenceFilter = $('#filterAgence').val().toLowerCase();
    
    // Filtre par validité
    var validiteFilter = $('#filterValidite').val();
    
    // Filtre par type d'accident
    var typeAccidentFilter = $('#filterTypeAccident').val().toLowerCase();
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        // Filtres de colonnes standards
        for (var col in filters) {
            if (filters.hasOwnProperty(col)) {
                var cellText = cells.eq(col).text().toLowerCase();
                if (cellText.indexOf(filters[col]) === -1) {
                    showRow = false;
                    break;
                }
            }
        }
        
        // Filtre par agence
        if (showRow && agenceFilter) {
            var agenceText = cells.eq(7).text().toLowerCase();
            if (agenceText !== agenceFilter) {
                showRow = false;
            }
        }
        
        // Filtre par type d'accident
        if (showRow && typeAccidentFilter) {
            var typeAccidentText = cells.eq(8).text().toLowerCase();
            if (typeAccidentText !== typeAccidentFilter) {
                showRow = false;
            }
        }
        
        // Filtre par validité
        if (showRow && validiteFilter) {
            var rowStatus = $(this).data('status');
            switch(validiteFilter) {
                case 'valide':
                    showRow = (rowStatus === 'status-valid');
                    break;
                case 'expire':
                    showRow = (rowStatus === 'status-expired');
                    break;
                case 'expire_soon':
                    showRow = (rowStatus === 'status-expiring');
                    break;
                case 'invalide':
                    showRow = (rowStatus === 'status-invalid');
                    break;
            }
        }
        
        $(this).toggle(showRow);
    });
    
    updateFilteredStats();
}

function updateFilteredStats() {
    var visibleRows = $('#myTable tbody tr:visible');
    var totalAccidents = visibleRows.length;
    var expiredCount = 0;
    var expiringCount = 0;
    
    visibleRows.each(function() {
        if ($(this).hasClass('tr-expired')) {
            expiredCount++;
        } else if ($(this).hasClass('tr-expiring') || $(this).hasClass('tr-today')) {
            expiringCount++;
        }
    });
    
    document.getElementById("totalAccidents").textContent = totalAccidents;
    document.getElementById("expiredCount").textContent = expiredCount;
    document.getElementById("expiringCount").textContent = expiringCount;
}

$('.filter-input, .filter-select').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    $('.filter-select').val('');
    filterTable();
});

function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    filename = filename?filename+'.xlsx':'excel_data.xlsx';
    
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}

function printTable() {
    var printWindow = window.open('', '_blank');
    
    // Créer une table simplifiée sans les colonnes non désirées
    var originalTable = document.getElementById('myTable');
    var simplifiedTable = originalTable.cloneNode(true);
    
    // Supprimer les tooltips
    var tooltipWrappers = simplifiedTable.querySelectorAll('.tooltip-wrapper');
    tooltipWrappers.forEach(function(wrapper) {
        var span = wrapper.querySelector('.spnDetails');
        if (span) {
            wrapper.outerHTML = span.outerHTML;
        }
    });
    
    // Identifier les index des colonnes à supprimer
    var headers = simplifiedTable.querySelectorAll('th');
    var colonneAnneeIndex = -1;
    var colonneActionsIndex = -1;
    
    // Trouver l'index de la colonne "السنة"
    headers.forEach(function(header, index) {
        if (header.textContent.trim() === 'السنة') {
            colonneAnneeIndex = index;
        }
        if (header.textContent.includes('إجراءات')) {
            colonneActionsIndex = index;
        }
    });
    
    // Supprimer les colonnes identifiées
    var allRows = simplifiedTable.querySelectorAll('tr');
    allRows.forEach(function(row) {
        var cells = row.querySelectorAll('td, th');
        
        // Supprimer la colonne "السنة" si trouvée
        if (colonneAnneeIndex !== -1 && cells.length > colonneAnneeIndex) {
            cells[colonneAnneeIndex].remove();
        }
        
        // Supprimer la colonne "إجراءات" si trouvée
        if (colonneActionsIndex !== -1 && cells.length > colonneActionsIndex) {
            cells[colonneActionsIndex].remove();
        }
    });
    
    printWindow.document.write(`
        <html dir="rtl">
        <head>
            <title>طباعة - حوادث الشغل</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 20px; 
                    direction: rtl;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 20px 0; 
                    font-size: 12px;
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: center; 
                }
                th { 
                    background-color: #2c3e50; 
                    color: white; 
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    border-bottom: 2px solid #2c3e50;
                    padding-bottom: 10px;
                }
                .footer { 
                    text-align: center; 
                    margin-top: 20px; 
                    font-size: 12px; 
                    color: #666;
                }
                .comment-cell {
                    max-width: 250px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .badge-accident {
                    padding: 4px 8px;
                    border-radius: 12px;
                    font-size: 10px;
                    font-weight: bold;
                    color: white;
                }
                @media print {
                    body { margin: 10px; }
                    .no-print { display: none; }
                    table { font-size: 10px; }
                    th, td { padding: 6px 4px; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>متابعة حوادث الشغل</h2>
                <p>تاريخ الطباعة: ${new Date().toLocaleDateString('Fr-Fr')}</p>
            </div>
            ${simplifiedTable.outerHTML}
            <div class="footer">
                <p>عدد السجلات: ${$('#myTable tbody tr:visible').length}</p>
                <p>تم الطباعة بواسطة نظام متابعة حوادث الشغل</p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
</body>
</html>