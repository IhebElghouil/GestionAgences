<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الأعوان</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/xlsx.full.min.js"></script>
    <script src="JS/MyScript.js"></script>
	<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php  session_start(); ?>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e3e9f7 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
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
        #statsContainer {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 10px;
}
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
/* Style unifié avec des bordures colorées */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    border-top: 4px solid;
}

/* Bordures de différentes couleurs */
.stat-card[data-type="all"] {
    border-top-color: #667eea;
}

.stat-card[data-type="actif"] {
    border-top-color: #38ef7d;
}

.stat-card[data-type="permanent"] {
    border-top-color: #3498db;
}

.stat-card[data-type="trainee"] {
    border-top-color: #f39c12;
}

.stat-card[data-type="attached_internal"] {
    border-top-color: #2980b9;
}

.stat-card[data-type="attached_external"] {
    border-top-color: #9b59b6;
}

.stat-card[data-type="indisponible"] {
    border-top-color: #e74c3c;
}

.stat-card[data-type="filtered"] {
    border-top-color: #7f8c8d;
}

/* Effet de survol */
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}


        
        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color) 0%, transparent 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
			 cursor: pointer;
        }
        .stat-card:active {
    transform: scale(0.98);
}
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .stat-total .stat-value { color: var(--primary-color); }
        .stat-permanent .stat-value { color: var(--success-color); }
        .stat-trainee .stat-value { color: var(--warning-color); }
        .stat-attached .stat-value { color: var(--info-color); }
        .stat-filtered .stat-value { color: #9b59b6; }
        
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

    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 15px;
}

        .filter-input {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            font-size: 0.9rem;
			width: 100%;
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
            background: var(--success-color);
            color: white;
        }

        .btn-export:hover {
            background: #229954;
            color: white;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .add-employee-btn {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            margin-bottom: 20px;
        }
        
        .add-employee-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
            background: linear-gradient(135deg, #229954 0%, #1e8449 100%);
        }
        
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            border-right: 4px solid var(--secondary-color);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .form-control-custom {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control-custom:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: white;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }
        
        .custom-table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            border: none;
            font-size: 0.95rem;
            text-align: center;
            position: sticky;
            top: 0;
        }
        
        .custom-table tbody td {
            padding: 12px 8px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .custom-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transform: scale(1.01);
        }
        
        .badge-custom {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
        }
        
        .badge-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
            color: white;
        }
        
        .badge-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #138d75 100%);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, var(--accent-color) 0%, #c0392b 100%);
            color: white;
        }
        
        .mobile-message {
            display: none;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 500;
            box-shadow: var(--card-shadow);
        }
        
        .employee-id {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .age-badge {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .seniority-badge {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .modal-content {
            border-radius: 15px;
            box-shadow: var(--hover-shadow);
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .alert-success {
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            box-shadow: var(--card-shadow);
        }
        
        .filtered-results {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
            box-shadow: var(--card-shadow);
        }
        
        .filtering-active {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @media (max-width: 1200px) {
            .custom-table {
                font-size: 0.85rem;
            }
            
            .custom-table thead th,
            .custom-table tbody td {
                padding: 10px 6px;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .mobile-message {
                display: block;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .custom-table {
                font-size: 0.8rem;
            }
            
            .custom-table thead th,
            .custom-table tbody td {
                padding: 8px 4px;
            }
        }
        
        .situation-badge {
            font-size: 0.7rem;
            padding: 3px 6px;
            border-radius: 10px;
            margin-top: 2px;
            display: block;
        }
        
        .weekend-badge {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container-main">
        <!-- Modal Structure -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="editModalContent">
                    <!-- AJAX content will be loaded here -->
                </div>
            </div>
        </div>

        <div class="header-section">
            <h1 class="header-title">إدارة الأعوان</h1>
           <?php
        include('SessionControl.php');
        ?>
        </div>
		
        <?php 
		
        include('menu.php'); 
                require('DbConnexion.php');
        
        // Afficher les messages d'erreur (si existants)
if (!empty($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle me-2"></i>
            ' . htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['error_message']);
}

// Afficher les messages de succès (si existants)
if (!empty($_SESSION['success_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; margin-bottom: 20px; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); border: none;">
            <i class="fas fa-check-circle me-2"></i>
            ' . htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8') . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['success_message']);
}
?>

        <!-- Add Employee Button -->
        <?php if ($_SESSION['departement'] == "admin"): ?>
        <button class="add-employee-btn ajout-user-btn">
            <i class="fa-solid fa-user-plus"></i>
            <span>إضافة عون جديد</span>
        </button>
        <?php endif; ?>

        <!-- Stats Section -->
        <div class="stats-section" id="statsContainer">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Filtered Results Message -->
        <div class="filtered-results" id="filteredResultsMessage" style="display: none;">
            <i class="fas fa-filter me-2"></i>
            تم العثور على <span id="resultsCount">0</span> نتيجة من أصل <span id="totalCount">0</span>
        </div>

        <!-- Action Buttons -->
        <div class="controls-section">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <button id="clearFilters" class="btn-modern btn-clear">
                    <i class="fas fa-trash-alt"></i>
                    <span>مسح كل الفلاتر</span>
                </button>
                
                <button id="exportExcelBtn" class="btn-modern btn-export">
                <i class="fas fa-file-excel"></i> تصدير إلى Excel
            </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <div class="input-group">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                        <input type="text" id="filterMecano" class="form-control filter-input column-filter" data-column="1" placeholder="الرقم الآلي...">
                    </div>
                </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                        <input type="text" id="filterNom" class="form-control filter-input column-filter" data-column="2" placeholder="الإسم أو اللقب...">
                    </div>
               
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" id="filterTitre" class="form-control filter-input column-filter" data-column="3" placeholder="تاريخ الولادة...">
                    </div>
               
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" id="filterNumCarte" class="form-control filter-input column-filter" data-column="4" placeholder="تاريخ الإنتداب...">
                    </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="material-icons"></i></span>
                        <input type="text" id="filterDepar" class="form-control filter-input column-filter" data-column="5" placeholder="الرتبة...">
                    </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                        <input type="text" id="filterGrade" class="form-control filter-input column-filter" data-column="7" placeholder="السلك ...">
                    </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                        <input type="text" id="filterAge" class="form-control filter-input column-filter" data-column="9" placeholder="العمر...">
                    </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                        <input type="text" id="filterDateEmission" class="form-control filter-input column-filter" data-column="11" placeholder="رقم الضمان الإجتماعي...">
                    </div>
                
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                        <input type="text" id="filterFinValidite" class="form-control filter-input column-filter" data-column="13" placeholder="وحدة الإرتباط...">
                    </div>
                </div>
                
				
            </div>
        </div>

        <div class="mobile-message">
            <i class="fas fa-mobile-alt me-2"></i>
            لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
        </div>

        <!-- Table Section -->
            <div class="table-container">
        <div class="table-responsive">
            <table id="myTable" class="custom-table">
                <thead>
                    <tr>
                        <th>#</th><th>الرقم الآلي</th><th>الإسم واللقب</th><th>تاريخ الولادة</th><th>تاريخ الإنتداب</th>
                        <th>الرتبة</th><th>السلم</th><th>السلك</th><th>رقم بطاقة وطنية</th><th>العمر</th>
                        <th>الأقدمية</th> <th>ض.إجتماعي</th><th>التأمين</th><th>وحدة الإرتباط</th><th>الصفة</th><th>الجنس</th>
                        <?php if ($_SESSION['departement'] == "admin"): ?><th>الإجراءات</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (isset($_SESSION['congidGA'])) {
                        $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
                        $totalEmployees = 0;
                        $permanentEmployees = 0;
                        $traineeEmployees = 0;
                        $attachedEmployeesInternes= 0;
                        $attachedEmployeesExternes= 0;
                        $attachedEmployeesIndisponibles= 0;
						$actifEmployees=0;
                        
                        $query = "
                            SELECT DISTINCT 
                                stuf.mecano, 
                                stuf.nom, 
                                stuf.daten, 
                                stuf.daterec, 
                                stuf.cin, 
                                titres.libellet AS titre_libelle,
                                stuf.echelle,
                                stuf.degree,
                                stuf.sexe,
                                stuf.fil,
                                stuf.contrastage,
                                dep.depar,
                                stuf.jrepos,
								social.ncnss, 
								social.nassurance
                            FROM stuf
                            LEFT JOIN dep ON stuf.dep = dep.id
							LEFT JOIN social ON social.mecano = stuf.mecano
                            LEFT JOIN titres ON titres.id = stuf.titre
                            WHERE stuf.contrastage IN (0,1,3,5,6)
                        ";
                        
                        if ($_SESSION['departement'] !== "admin" && !empty($departements)) {
                            $placeholders = implode(',', array_fill(0, count($departements), '?'));
                            $query .= " AND stuf.dep IN ($placeholders)";
                        }
                        $query .= " GROUP BY stuf.mecano ORDER BY stuf.mecano ASC";
                        
                        $stmt = mysqli_prepare($connection, $query);
                        if ($_SESSION['departement'] !== "admin" && !empty($departements)) {
                            $types = str_repeat('i', count($departements));
                            mysqli_stmt_bind_param($stmt, $types, ...$departements);
                        }
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $mecano, $nom, $daten, $daterec, $cin, $titre_libelle, $echelle, $degree, $sexe, $statut, $contrastage, $depar, $jrepos, $ncnss, $nassurance);
                        
                        $dataRows = [];
                        $i = 0;
                        while (mysqli_stmt_fetch($stmt)) {
                            $i++;
                            $totalEmployees++;
                            
                            switch ($contrastage) {
                                case 0: $permanentEmployees++; break;
                                case 1: $traineeEmployees++; break;
                                case 3: $attachedEmployeesInternes++; break;
                                case 5: $attachedEmployeesExternes++; break;
                                case 6: $attachedEmployeesIndisponibles++; break;
                            }
                            $actifEmployees=$attachedEmployeesInternes+$traineeEmployees+$permanentEmployees;
                            $dateNaissance = date_create($daten);
                            $today = date_create('today');
                            $age = $dateNaissance ? $dateNaissance->diff($today)->y : 0;
                            
                            $dateEmbauche = date_create($daterec);
                            $anciennete = $dateEmbauche ? $dateEmbauche->diff($today)->y : 0;
                            
                            $silk = '';
                            if ($echelle !== null && $echelle !== '') {
                                $echelleVal = (int)$echelle;
                                if ($echelleVal >= 500) $silk = 'C';
                                elseif ($echelleVal < 300) $silk = 'E';
                                else $silk = 'M';
                            } else {
                                $silk = '-';
                            }
                            
                            $sifa = '';
                            switch ($contrastage) {
                                case 0: $sifa = 'مترسم'; break;
                                case 1: $sifa = 'متربص'; break;
                                case 3: $sifa = 'ملحق لدى الشركة'; break;
                                case 5: $sifa = 'ملحق خارج الشركة'; break;
                                case 6: $sifa = 'إحالة على عدم المباشرة'; break;
                                default: $sifa = 'متعاقد';
                            }
                            
                            $gender = ($sexe == 'M' || $sexe == 'm') ? 'ذكر' : (($sexe == 'F' || $sexe == 'f') ? 'أنثى' : '-');
                            
                            $dataRows[] = [
                                'index' => $i,
                                'mecano' => $mecano,
                                'nom' => $nom,
                                'daten' => $daten,
                                'daterec' => $daterec,
                                'titre_libelle' => $titre_libelle,
                                'echelle' => $echelle,
                                'degree' => $degree,
                                'cin' => $cin,
                                'age' => $age,
                                'anciennete' => $anciennete,
                                'depar' => $depar,
                                'silk' => $silk,
                                'fil' => $statut,
                                'sifa' => $sifa,
                                'sexe' => $gender,
                                'contrastage_raw' => $contrastage
                            ];
                            
                            echo '<tr>';
                            echo '<td><span class="badge-custom" style="background: linear-gradient(135deg, #3498db, #2980b9); color:white;">'.$i.'</span></td>';
                            echo '<td><span class="employee-id">'.htmlspecialchars($mecano).'</span></td>';
                            echo '<td><strong>'.htmlspecialchars($nom).'</strong></td>';
                            echo '<td>'.htmlspecialchars($daten).'</td>';
                            echo '<td>'.htmlspecialchars($daterec).'</td>';
                            echo '<td>'.htmlspecialchars($titre_libelle).'</td>';
                           $badgeSilk = $silk == 'C' ? 'badge-success' : ($silk == 'M' ? 'badge-warning' : 'badge-info');
                            echo '<td><span class="badge-custom '.$badgeSilk.'">'.$echelle.'</span></td>';
                            $badgeSilk = $silk == 'C' ? 'badge-success' : ($silk == 'M' ? 'badge-warning' : 'badge-info');
                            echo '<td><span class="badge-custom '.$badgeSilk.'">'.$silk.'</span></td>';
                            echo '<td>'.htmlspecialchars($cin).'</td>';
                            echo '<td><span class="badge-custom badge-purple">'.$age.' سنة</span></td>';
                            echo '<td><span class="badge-custom" style="background: linear-gradient(135deg, #e67e22, #d35400);">'.$anciennete.' سنة</span></td>';
							echo '<td><span class="badge-custom">'.htmlspecialchars($ncnss).'</span></td>';
							echo '<td><span class="badge-custom">'.htmlspecialchars($nassurance).'</span></td>';
                            echo '<td><span class="badge-custom">'.htmlspecialchars($depar).'</span></td>';
                            $sifaBadge = ($sifa == 'مترسم') ? 'badge-success' : (($sifa == 'متربص') ? 'badge-warning' : 'badge-info');
                            echo '<td><span class="badge-custom '.$sifaBadge.'">'.$sifa.'</span></td>';
                            echo '<td>'.htmlspecialchars($gender).'</td>';
                            if ($_SESSION['departement'] == "admin") {
    echo '<td>
        <button 
            class="btn btn-sm edit-user-btn" 
            data-mecano="'.htmlspecialchars($mecano).'"
            style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; border: none; border-radius: 10px; padding: 8px 12px;">
            <i class="fas fa-edit"></i>
        </button>

        <button 
            class="btn btn-sm attestation-btn-ar" 
            data-mecano="'.htmlspecialchars($mecano).'"
            style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; border: none; border-radius: 10px; padding: 8px 12px;">
            ع
        </button>
		<button 
            class="btn btn-sm attestation-btn" 
            data-mecano="'.htmlspecialchars($mecano).'"
            style="background: linear-gradient(135deg, #2ecc71, #27ae60); color: white; border: none; border-radius: 10px; padding: 8px 12px;">
            FR
        </button>
    </td>';
}
                            echo '</tr>';
                        }
                        mysqli_stmt_close($stmt);
                        mysqli_close($connection);
                        
                        echo '<script>var employeeData = ' . json_encode($dataRows) . ';</script>';
                       echo "<script>
    document.getElementById('statsContainer').innerHTML = `
        <div class='stat-card' data-type='all'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$totalEmployees</div>
                    <div class='stat-label'>إجمالي الأعوان</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-users'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='permanent'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$permanentEmployees</div>
                    <div class='stat-label'>مترسمين</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-user-check'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='trainee'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$traineeEmployees</div>
                    <div class='stat-label'>متربصين</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-user-graduate'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='attached_internal'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$attachedEmployeesInternes</div>
                    <div class='stat-label'>ملحقين داخل الشركة</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-building'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='attached_external'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$attachedEmployeesExternes</div>
                    <div class='stat-label'>ملحق خارج الشركة</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-external-link-alt'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='indisponible'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$attachedEmployeesIndisponibles</div>
                    <div class='stat-label'>إحالة على عدم المباشرة</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-user-clock'></i>
                </div>
            </div>
        </div>
		<div class='stat-card' data-type='actif'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value'>$actifEmployees</div>
                    <div class='stat-label'>مباشرون</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-user-clock'></i>
                </div>
            </div>
        </div>
        <div class='stat-card' data-type='filtered'>
            <div class='stat-card-content'>
                <div class='stat-info'>
                    <div class='stat-value' id='filteredCount'>$totalEmployees</div>
                    <div class='stat-label'>النتائج المصفاة</div>
                </div>
                <div class='stat-icon'>
                    <i class='fas fa-filter'></i>
                </div>
            </div>
        </div>
    `;
    document.getElementById('resultsCount').innerText = '$totalEmployees';
    document.getElementById('totalCount').innerText = '$totalEmployees';
    
    // Add click handlers for stat cards using your existing filter system
    $(document).on('click', '.stat-card', function() {
        var type = $(this).data('type');
        
        // Clear all existing filters first
        $('.column-filter').val('');
        
        if (type === 'all') {
            // Show all rows
            $('#myTable tbody tr').show();
        }
        else if (type === 'permanent') {
            // Hide all rows, then show only permanent
            $('#myTable tbody tr').hide();
            $('#myTable tbody tr').each(function() {
                // The situation column is column index 14 (0-based) in your table
                var situationText = $(this).find('td').eq(14).text().trim();
                if (situationText === 'مترسم') {
                    $(this).show();
                }
            });
        }
        else if (type === 'trainee') {
            $('#myTable tbody tr').hide();
            $('#myTable tbody tr').each(function() {
                var situationText = $(this).find('td').eq(14).text().trim();
                if (situationText === 'متربص') {
                    $(this).show();
                }
            });
        }
        else if (type === 'attached_internal') {
            $('#myTable tbody tr').hide();
            $('#myTable tbody tr').each(function() {
                var situationText = $(this).find('td').eq(14).text().trim();
                if (situationText === 'ملحق لدى الشركة') {
                    $(this).show();
                }
            });
        }
        else if (type === 'attached_external') {
            $('#myTable tbody tr').hide();
            $('#myTable tbody tr').each(function() {
                var situationText = $(this).find('td').eq(14).text().trim();
                if (situationText === 'ملحق خارج الشركة') {
                    $(this).show();
                }
            });
        }
        else if (type === 'indisponible') {
            $('#myTable tbody tr').hide();
            $('#myTable tbody tr').each(function() {
                var situationText = $(this).find('td').eq(14).text().trim();
                if (situationText === 'إحالة على عدم المباشرة') {
                    $(this).show();
                }
            });
        }
        else if (type === 'actif') {
    $('#myTable tbody tr').hide();
    $('#myTable tbody tr').each(function() {
        var situationText = $(this).find('td').eq(14).text().trim();
        if (situationText === 'مترسم' || situationText === 'متربص' || situationText === 'ملحق لدى الشركة') {
            $(this).show();
        }
    });
}
        // Update stats after filtering
        updateFilteredStats();
    });
    
    function updateFilteredStats() {
        var visibleCount = $('#myTable tbody tr:visible').length;
        var totalCount = $('#myTable tbody tr').length;
        
        $('#filteredCount').text(visibleCount);
        
        if (visibleCount === totalCount) {
            $('#filteredResultsMessage').hide();
        } else {
            $('#resultsCount').text(visibleCount);
            $('#totalCount').text(totalCount);
            $('#filteredResultsMessage').show();
        }
    }
</script>";}
                    ?>
                </tbody>
             </table>
        </div>
    </div>
</div>
    </div>

    <script>
        // Modal functionality
        $(document).ready(function() {
            $('.edit-user-btn').on('click', function(e) {
                e.preventDefault();
                const mecano = $(this).data('mecano');

                $('#editModalContent').load('Find_Emplyee.php?mecano=' + encodeURIComponent(mecano), function() {
                    $('#editModal').modal('show');
                });
            });
            
	$(document).on('click', '.attestation-btn', function() {
    let mecano = $(this).data('mecano');
    console.log("Generate attestation for:", mecano);

    // مثال: فتح صفحة attestation
    window.open('attestation.php?mecano=' + mecano, '_blank');
});

$(document).on('click', '.attestation-btn-ar', function() {
    let mecano = $(this).data('mecano');
    console.log("Generate attestation for:", mecano);

    // مثال: فتح صفحة attestation
    window.open('attestation_ar.php?mecano=' + mecano, '_blank');
});
			
            $('.ajout-user-btn').on('click', function(e) {
                e.preventDefault();
                $('#editModalContent').load('Ajout_Emplyee.php', function() {
                    $('#editModal').modal('show');
                });
            });
            
            // تحديث الإحصائيات عند تحميل الصفحة
            updateFilteredStats();
            updateFilteredMessage();
        });

        // دالة لتحديث عدد النتائج المصفاة والإحصائيات
        function updateFilteredStats() {
            const visibleRows = $('#myTable tbody tr:visible').length;
            const totalRows = $('#myTable tbody tr').length;
            
            // تحديث عدد النتائج المصفاة
            $('#filteredCount').text(visibleRows);
            
            // تحديث الإحصائيات بناءً على النتائج المصفاة
            updateDetailedStats(visibleRows);
        }

        // دالة لتحديث الإحصائيات التفصيلية بناءً على النتائج المصفاة
        function updateDetailedStats(visibleRowsCount) {
            let permanentCount = 0;
            let traineeCount = 0;
            let attachedCount = 0;
            
            $('#myTable tbody tr:visible').each(function() {
                const situationText = $(this).find('.situation-badge').text().trim();
                
                if (situationText === 'مترسم') {
                    permanentCount++;
                } else if (situationText === 'متربص') {
                    traineeCount++;
                } else if (situationText === 'ملحق') {
                    attachedCount++;
                }
            });
            
            // تحديث الإحصائيات في واجهة المستخدم
            $('.stat-total .stat-value').text(visibleRowsCount);
            $('.stat-permanent .stat-value').text(permanentCount);
            $('.stat-trainee .stat-value').text(traineeCount);
            $('.stat-attached .stat-value').text(attachedCount);
        }

        // دالة لتحديث رسالة النتائج المصفاة
        function updateFilteredMessage() {
            const visibleRows = $('#myTable tbody tr:visible').length;
            const totalRows = $('#myTable tbody tr').length;
            
            if (visibleRows === totalRows) {
                $('#filteredResultsMessage').hide();
            } else {
                $('#resultsCount').text(visibleRows);
                $('#totalCount').text(totalRows);
                $('#filteredResultsMessage').show();
                
                // إضافة تأثير مؤقت
                $('#filteredResultsMessage').addClass('filtering-active');
                setTimeout(() => {
                    $('#filteredResultsMessage').removeClass('filtering-active');
                }, 1500);
            }
        }

        // Filter table function
        function filterTable() {
            var filters = {};
            $('.column-filter').each(function() {
                if ($(this).val()) {
                    filters[$(this).data('column')] = $(this).val().toLowerCase();
                }
            });
            
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
            });
            
            // تحديث الإحصائيات والرسائل بعد التصفية
            updateFilteredStats();
            updateFilteredMessage();
        }
        
        $('.column-filter').on('keyup change', filterTable);
        
        $('#clearFilters').on('click', function() {
            $('.column-filter').val('');
            filterTable();
        });

        // Add hover effects and animations
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('#myTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
        });
		    $('#exportExcelBtn').on('click', function() {
        if(!window.employeeData || employeeData.length === 0) {
            Swal.fire('تنبيه', 'لا توجد بيانات للتصدير', 'warning');
            return;
        }
        
        let filteredData = [];
        for(let i=0; i<employeeData.length; i++) {
            let rowMecano = employeeData[i].mecano;
            let isVisible = false;
            $('#myTable tbody tr:visible').each(function() {
                let mecanoCell = $(this).find('td').eq(1).text().trim();
                if(mecanoCell == rowMecano) isVisible = true;
            });
            if(isVisible) filteredData.push(employeeData[i]);
        }
        
        if(filteredData.length === 0) {
            Swal.fire('تنبيه', 'لا توجد صفوف ظاهرة للتصدير', 'info');
            return;
        }
        
        let exportRows = [];
        let headers = [
            'رقم آلي', 'الإسم واللقب', 'ت-الولادة', 'الانتداب', 'الخطة', 
            'السلم', 'الدرجة', 'ب ت و', 'وحدة الارتباط', 
            'العمر', 'الأقدمية', 'السلك', 'الرتبة', 'الصفة', 'الجنس'
        ];
        exportRows.push(headers);
        
        for(let emp of filteredData) {
            let row = [
                emp.mecano,
                emp.nom,
                emp.daten,
                emp.daterec,
                emp.titre_libelle,
                emp.echelle,
                emp.degree,
                emp.cin,
                emp.depar,
                emp.age,
                emp.anciennete,
                emp.silk,
                emp.fil,
                emp.sifa,
                emp.sexe
            ];
            exportRows.push(row);
        }
        
        const ws = XLSX.utils.aoa_to_sheet(exportRows);
        ws['!cols'] = [{wch:12},{wch:20},{wch:12},{wch:12},{wch:18},{wch:10},{wch:10},{wch:15},{wch:18},{wch:8},{wch:8},{wch:8},{wch:15},{wch:12},{wch:10}];
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'ListePersonels');
        XLSX.writeFile(wb, 'ListePersonels_Organisee.xlsx');
        
        Swal.fire({
            icon: 'success',
            title: 'تم التصدير بنجاح',
            text: 'تم تصدير ' + filteredData.length + ' عون',
            timer: 2000,
            showConfirmButton: false
        });
    });
    </script>
</body>
</html>