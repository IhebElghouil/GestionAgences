<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام المغادرة و الإلحاق و الإحالة على عدم المباشرة</title>
    
    <?php
    session_start();
    ?>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 95%;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }
        
        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
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
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 0;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            display: none;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 15px 25px;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .form-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-body {
            padding: 25px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-color);
        }
        
        .form-control-custom {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow-x: auto;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }
        
        .custom-table {
            margin-bottom: 0;
            width: 100%;
            min-width: 800px;
        }
        
        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            text-align: center;
            position: sticky;
            top: 0;
        }
        
        .custom-table tbody td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
            text-align: center;
        }
        
        .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .warning-row {
            background-color: rgba(243, 156, 18, 0.2) !important;
            font-weight: 600;
        }
        
        .warning-message {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            border-right: 5px solid var(--warning-color);
            font-weight: 500;
            text-align: center;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--primary-color);
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        .nav-tabs .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .action-buttons-table {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-table {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.85rem;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-primary { background-color: var(--secondary-color); color: white; }
        .badge-warning { background-color: var(--warning-color); color: white; }
        .badge-danger { background-color: var(--accent-color); color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-success { background-color: var(--success-color); color: white; }
        .badge-secondary { background-color: #95a5a6; color: white; }
        
        .months-left {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 15px;
            display: inline-block;
            min-width: 80px;
        }
        
        .months-green { background-color: #d4edda; color: #155724; }
        .months-yellow { background-color: #fff3cd; color: #856404; }
        .months-red { background-color: #f8d7da; color: #721c24; }
        .dossier-green {
    background-color: #d4edda;
    color: #155724;
}

.dossier-red {
    background-color: #f8d7da;
    color: #721c24;
}
        .print-options {
            display: none;
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            box-shadow: var(--card-shadow);
        }
        
        .print-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            margin-bottom: 15px;
        }
        
        .filter-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .stopped-followup {
            background-color: rgba(231, 76, 60, 0.1) !important;
        }
        
        .document-item {
            transition: all 0.3s ease;
        }
        
        .document-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .progress {
            height: 20px;
            border-radius: 10px;
        }
        
        .progress-bar {
            transition: width 0.3s ease;
        }
        
        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }
        
        .close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Document count badge */
        .doc-count-badge {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            line-height: 20px;
            text-align: center;
            margin-left: 5px;
            position: relative;
            top: -8px;
            right: -5px;
        }
        
        .doc-count-badge.zero {
            background-color: #95a5a6;
        }
        
        .btn-documents {
            position: relative;
        }
        
        /* Modal XL size */
        .modal-xl {
            max-width: 90%;
            width: 90%;
        }
        
        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1140px;
                width: 1140px;
            }
        }
        
        #viewDocumentModal .modal-dialog {
            max-width: 90%;
            width: 90%;
            margin: 1.75rem auto;
        }
        
        #viewDocumentModal .modal-body {
            padding: 0;
            height: 80vh;
            overflow: auto;
        }
        
        #documentViewer {
            width: 100%;
            height: 100%;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }
        
        #documentViewer embed,
        #documentViewer iframe,
        #documentViewer object {
            width: 100%;
            height: 80vh;
            border: none;
        }
        
        #documentViewer img {
            max-width: 100%;
            max-height: 80vh;
        }
        
        @media (max-width: 768px) {
            body { padding: 10px; }
            .header-title { font-size: 1.8rem; }
            .form-grid, .filter-grid { grid-template-columns: 1fr; }
            .action-buttons { flex-direction: column; }
            .stats-value { font-size: 2rem; }
            .action-buttons-table { flex-direction: column; }
            .form-buttons { flex-direction: column; }
            .modal-xl { max-width: 95%; width: 95%; }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <div class="container-main">
        <div class="header-section">
            <h1 class="header-title">متابعة المغادرة و الإلحاق</h1>
            <?php include('SessionControl.php'); ?>
        </div>
        
        <?php
        require('connection.php');
        include('menu.php');
        
        $stats = ['total_departs' => 0, 'retired' => 0, 'current_year' => 0, 
                  'total_detachements_out' => 0, 'total_detachements_in' => 0, 'total_nonservice' => 0];
        
        if (isset($_SESSION['congidGA'])) {
            $isAdmin = ($_SESSION['departement'] === "admin");
            $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
            $current_year = date('Y');
            
            $query_depart = $isAdmin ? 
                "SELECT COUNT(*) as total, SUM(CASE WHEN observation = 'تقاعد' THEN 1 ELSE 0 END) as retired, SUM(CASE WHEN annee = $current_year THEN 1 ELSE 0 END) as current_year FROM depart" :
                "SELECT COUNT(*) as total, SUM(CASE WHEN observation = 'تقاعد' THEN 1 ELSE 0 END) as retired, SUM(CASE WHEN annee = $current_year THEN 1 ELSE 0 END) as current_year FROM depart LEFT JOIN stuf ON stuf.mecano = depart.mecano WHERE stuf.dep IN (" . implode(',', $departements) . ")";
            
            $result = mysqli_query($connection, $query_depart);
            if ($row = mysqli_fetch_assoc($result)) {
                $stats['total_departs'] = $row['total'] ?? 0;
                $stats['retired'] = $row['retired'] ?? 0;
                $stats['current_year'] = $row['current_year'] ?? 0;
            }
            
            $stats['total_detachements_out'] = 0;
            $stats['total_detachements_in'] = 0;
            $stats['total_nonservice'] = 0;
        }
        ?>

        <div class="action-buttons">
            <button onclick="exportTableToExcel('myTable', 'ListeDépart.xlsx')" class="btn btn-success btn-custom">
                <i class="fas fa-file-excel"></i> تصدير إلى Excel
            </button>
            <button onclick="togglePrintOptions()" class="btn btn-secondary btn-custom">
                <i class="fas fa-print"></i> طباعة
            </button>
            <?php if (isset($_SESSION['congidGA']) && $_SESSION['departement'] === "admin"): ?>
                <button onclick="toggleForm()" id="toggleFormBtn" class="btn btn-primary btn-custom">
                    <i class="fas fa-user-plus"></i> إضافة مغادر
                </button>
                <button onclick="toggleDetachementForm()" id="toggleDetachementFormBtn" class="btn btn-info btn-custom">
                    <i class="fas fa-exchange-alt"></i> إضافة ملحق
                </button>
            <?php endif; ?>
            <button id="clearFilters" class="btn btn-outline-secondary btn-custom">
                <i class="fas fa-filter"></i> مسح الفلاتر
            </button>
        </div>

        <div class="print-options" id="printOptions">
            <h5 class="mb-3">خيارات الطباعة</h5>
            <div class="print-buttons">
                <button class="btn btn-primary" onclick="printTable('depart')">طباعة المغادرين</button>
                <button class="btn btn-info" onclick="printTable('detachement')">طباعة الملحقين</button>
                <button class="btn btn-success" onclick="printTable('all')">طباعة الكل</button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row">
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['total_departs']; ?></div><div class="stats-label">إجمالي المغادرون</div></div></div>
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['retired']; ?></div><div class="stats-label">المتقاعدين</div></div></div>
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['current_year']; ?></div><div class="stats-label">مغادرين هذا العام</div></div></div>
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['total_detachements_out']; ?></div><div class="stats-label">ملحقين خارج الشركة</div></div></div>
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['total_detachements_in']; ?></div><div class="stats-label">ملحقين داخل الشركة</div></div></div>
            <div class="col-md-2"><div class="stats-card"><div class="stats-value"><?php echo $stats['total_nonservice']; ?></div><div class="stats-label">في عدم المباشرة</div></div></div>
        </div>

        <!-- Form Section for Depart -->
        <div class="form-section" id="departForm">
            <div class="form-header">
                <div class="form-header-title"><i class="fas fa-user-plus"></i> إضافة مغادر جديد</div>
                <button type="button" class="close-btn" onclick="toggleForm()"><i class="fas fa-times"></i></button>
            </div>
            <div class="form-body">
                <form action="AddDepart.php" method="GET">
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">الرقم الآلي</label>
                            <select name="mecano" id="mecano" class="form-control form-control-custom" required>
                                <option value="">---- اختر الرقم الآلي ----</option>
                                <?php
                                $result = mysqli_query($connection, "SELECT DISTINCT mecano FROM stuf WHERE contrastage IN (0,1,3) ORDER BY mecano ASC");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="'.$row['mecano'].'">'.$row['mecano'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">الإسم و اللقب</label>
                            <input type="text" id="nom" name="nom" class="form-control form-control-custom" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">وحدة الإرتباط</label>
                            <input type="text" id="departement" name="departement" class="form-control form-control-custom" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تاريخ الولادة</label>
                            <input type="text" id="daterecrutement" name="daterecrutement" class="form-control form-control-custom" readonly>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">تاريخ بلوغ سنّ الستين</label>
                            <input type="text" id="dateretraite" name="dateretraite" class="form-control form-control-custom" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تاريخ المغادرة</label>
                            <input type="date" id="datedepart" name="datedepart" class="form-control form-control-custom" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">رصيد الإجازات</label>
                            <input type="text" id="restconge" name="restconge" class="form-control form-control-custom" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">باقي الوزارة</label>
                            <input type="number" id="ministere" name="ministere" value="0" min="0" class="form-control form-control-custom">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">سنة المغادرة</label>
                            <select name="anneedepart" class="form-control form-control-custom" required>
                                <?php for ($year = 2011; $year <= 2035; $year++) echo "<option value='$year'>$year</option>"; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">السبب</label>
                            <select name="cause" class="form-control form-control-custom" required>
                                <option value="تقاعد">تقاعد</option><option value="عزل">عزل</option><option value="وفاة">وفاة</option>
                                <option value="إنهاء تربّص">إنهاء تربّص</option><option value="إلحاق">إلحاق</option>
                                <option value="تقاعد مبكّر">تقاعد مبكّر</option><option value="إنهاء إلحاق">إنهاء إلحاق</option>
                                <option value="إحالة على عدم المباشرة">إحالة على عدم المباشرة</option>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <input type="submit" id="envoie" value="حفظ البيانات" class="btn btn-success btn-custom" style="display: none; width: 100%;">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Section for Detachement -->
        <div class="form-section" id="detachementForm">
            <div class="form-header">
                <div class="form-header-title"><i class="fas fa-exchange-alt"></i> <span id="detachementFormTitle">إضافة ملحق جديد</span></div>
                <button type="button" class="close-btn" onclick="toggleDetachementForm()"><i class="fas fa-times"></i></button>
            </div>
            <div class="form-body">
                <form id="detachementFormData" method="POST">
                    <input type="hidden" id="detachementId" name="id">
                    <input type="hidden" id="detachementStatut" name="statut" value="0">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">الرقم الآلي</label><input type="text" id="detachementMecano" name="mecano" class="form-control form-control-custom" required></div>
                        <div class="form-group"><label class="form-label">الإسم و اللقب</label><input type="text" id="detachementNom" name="nomprenom" class="form-control form-control-custom" required></div>
                        <div class="form-group"><label class="form-label">جهة الإلحاق</label><input type="text" id="detachementAffectation" name="affectation" class="form-control form-control-custom" required></div>
                        <div class="form-group"><label class="form-label">المؤسّسة الآصليّة</label><input type="text" id="detachementSource" name="source" class="form-control form-control-custom" required></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">الوضعية</label><select id="detachementSituation" name="situation" class="form-control form-control-custom" required>
                            <option value="ملحق خارج الشركة">ملحق خارج الشركة</option><option value="ملحق لدى الشركة">ملحق لدى الشركة</option>
                            <option value="إنهاء إلحاق">إنهاء إلحاق</option><option value="إحالة على عدم المباشرة">إحالة على عدم المباشرة</option>
                        </select></div>
                        <div class="form-group"><label class="form-label">تاريخ الإلحاق</label><input type="date" id="detachementDate" name="datedetachement" class="form-control form-control-custom" required></div>
                        <div class="form-group"><label class="form-label">الفترة (بالسنوات)</label><input type="number" id="detachementPeriode" name="periode" class="form-control form-control-custom" min="1" max="5" value="1" required></div>
                        <div class="form-group"><label class="form-label">الملف</label><select id="detachementDossier" name="dossier" class="form-control form-control-custom">
                            <option value='مؤشّر من رئاسة الحكومة'>مؤشّر من رئاسة الحكومة</option>
                            <option value='في طور التأشير (رئاسة الحكومة)'>في طور التأشير (رئاسة الحكومة)</option>
                            <option value='في طور التأشير (جهة الإلحاق)'>في طور التأشير (جهة الإلحاق)</option>
                            <option value='في طور التجديد (رئاسة الحكومة)'>في طور التجديد (رئاسة الحكومة)</option>
                            <option value='في طور التجديد (جهة الإلحاق)'>في طور التجديد (جهة الإلحاق)</option>
                            <option value='في طور الإدماج (رئاسة الحكومة)'>في طور الإدماج (رئاسة الحكومة)</option>
                            <option value='في طور الإدماج (جهة الإلحاق)'>في طور الإدماج (جهة الإلحاق)</option>
                        </select></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">التجديدات</label>
                            <div class="d-flex gap-3 mt-2 flex-wrap">
                                <div class="d-flex align-items-center gap-2"><input type="number" id="detachementRenouvellement1" name="renouvellemnt1" class="form-control form-control-custom" min="0" max="5" value="0" style="width: 80px;"><label>تجديد 1</label></div>
                                <div class="d-flex align-items-center gap-2"><input type="number" id="detachementRenouvellement2" name="renouvellemnt2" class="form-control form-control-custom" min="0" max="5" value="0" style="width: 80px;"><label>تجديد 2</label></div>
                                <div class="d-flex align-items-center gap-2"><input type="number" id="detachementRenouvellement3" name="renouvellemnt3" class="form-control form-control-custom" min="0" max="5" value="0" style="width: 80px;"><label>تجديد 3</label></div>
                            </div>
                        </div>
                        <div class="form-group" style="grid-column: span 3;"><label class="form-label">ملاحظات</label><textarea id="detachementObservations" name="observations" class="form-control form-control-custom" rows="3"></textarea></div>
                    </div>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check"><input class="form-check-input" type="checkbox" id="editClosedetachement" name="close_detachement"><label class="form-check-label"><i class="fas fa-lock me-2"></i>إغلاق الملف</label></div>
                    </div>
                    <div class="form-buttons">
                        <button type="submit" class="btn btn-success btn-custom"><i class="fas fa-save"></i> <span id="saveDetachementText">حفظ بيانات الملحق</span></button>
                        <button type="button" class="btn btn-secondary btn-custom" onclick="toggleDetachementForm()"><i class="fas fa-times"></i> إلغاء</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabs Section -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="depart-tab" data-bs-toggle="tab" data-bs-target="#depart" type="button"><i class="fas fa-user-minus me-2"></i>المغادرون</button></li>
            <li class="nav-item"><button class="nav-link" id="detachement-tab" data-bs-toggle="tab" data-bs-target="#detachement" type="button"><i class="fas fa-exchange-alt me-2"></i>الملحقون</button></li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Depart Tab -->
            <div class="tab-pane fade show active" id="depart" role="tabpanel">
                <div class="filters-section">
                    <h5 class="mb-3">فلاتر البحث - المغادرون</h5>
                    <div class="filter-grid">
                        <input type="text" id="filterMecano" class="form-control column-filter-depart" data-column="0" placeholder="البحث بالرقم الآلي...">
                        <input type="text" id="filterNom" class="form-control column-filter-depart" data-column="1" placeholder="البحث بالإسم...">
                        <input type="text" id="filterDepartement" class="form-control column-filter-depart" data-column="2" placeholder="البحث بوحدة الإرتباط...">
                        <input type="text" id="filterAnnee" class="form-control column-filter-depart" data-column="8" placeholder="البحث بالسنة...">
                        <input type="text" id="filterCause" class="form-control column-filter-depart" data-column="9" placeholder="البحث بسبب المغادرة...">
                    </div>
                </div>
                <div class="table-container">
                    <table id="myTable" class="custom-table">
                        <thead>
                            <tr><th>الرقم الآلي</th><th>الإسم و اللقب</th><th>وحدة الإرتباط</th><th>تاريخ الولادة</th><th>تاريخ بلوغ سنّ الستين</th><th>تاريخ المغادرة</th><th>رصيد الإجازات</th><th>باقي الوزارة</th><th>سنة المغادرة</th><th>السبب</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_SESSION['congidGA'])) {
                                $annee_result = mysqli_query($connection, "SELECT annee FROM annee");
                                $annee_row = mysqli_fetch_row($annee_result);
                                $annee = $annee_row[0];
                                
                                $query = "SELECT DISTINCT depart.mecano, nom, datenaissance, datedepart, dateretraite, annee, dep.depar, cministere, rest, DATEDIFF(dateretraite, CURRENT_DATE()), observation 
                                          FROM depart LEFT JOIN stuf ON stuf.mecano = depart.mecano LEFT JOIN dep ON stuf.dep = dep.id LEFT JOIN nbconge ON stuf.mecano = nbconge.mecano 
                                          WHERE depart.annee >= " . ($_SESSION['departement'] === "admin" ? $annee-1 : $annee) . " ORDER BY datedepart ASC";
                                
                                $result = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $date1 = date('Y-m-d', strtotime($row['datedepart']));
                                    $date2 = date('Y-m-d');
                                    $row_class = ($date1 < $date2) ? 'warning-row' : '';
                                    echo "<tr class='$row_class'>";
                                    echo "<td><span class='badge badge-primary'>" . htmlspecialchars($row['mecano']) . "</span></td>";
                                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['depar']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['datenaissance']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['dateretraite']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['datedepart']) . "</td>";
                                    echo "<td><span class='badge badge-primary'>" . htmlspecialchars($row['rest']) . "</span></td>";
                                    echo "<td><span class='badge badge-warning'>" . htmlspecialchars($row['cministere']) . "</span></td>";
                                    echo "<td>" . htmlspecialchars($row['annee']) . "</td>";
                                    echo "<td><span class='badge badge-danger'>" . htmlspecialchars($row['observation']) . "</span></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="warning-message"><i class="fas fa-exclamation-triangle me-2"></i> يمنع العون من العمل خلال الفترة الفاصلة بين تاريخ بلوغه سنّ الستين و تاريخ المغادرة</div>
            </div>

            <!-- Detachement Tab -->
            <div  id="detachement" role="tabpanel">
            <div  id="detachement" role="tabpanel">
                <div class="filters-section">
                    <h5 class="mb-3">فلاتر البحث - الملحقون</h5>
                    <div class="filter-grid">
                        <input type="text" id="filterDetMecano" class="form-control column-filter-detachement" data-column="0" placeholder="البحث بالرقم الآلي...">
                        <input type="text" id="filterDetNom" class="form-control column-filter-detachement" data-column="1" placeholder="البحث بالإسم...">
                        <input type="text" id="filterDetAffectation" class="form-control column-filter-detachement" data-column="2" placeholder="البحث بجهة الإلحاق...">
                        <input type="text" id="filterDetSource" class="form-control column-filter-detachement" data-column="3" placeholder="البحث بالمؤسّسة الآصليّة...">
                        <select id="filterDetSituation" class="form-control column-filter-detachement" data-column="4"><option value="">جميع الوضعيات</option><option value="ملحق خارج الشركة">ملحق خارج الشركة</option><option value="ملحق لدى الشركة">ملحق لدى الشركة</option><option value="إنتهت الإعارة">إنتهت الإعارة</option><option value="إحالة على عدم المباشرة">إحالة على عدم المباشرة</option></select>
                        <select id="filterDetStatut" class="form-control"><option value="">جميع حالات المتابعة</option><option value="0">قيد المتابعة</option><option value="1">متابعة موقوفة</option></select>
                    </div>
                    <div class="filter-group">
                        <div class="filter-title">فلتر حسب المدّة المتبقّية:</div>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check"><input class="form-check-input" type="radio" name="filterMonthsLeft" value="all" checked><label class="form-check-label">الكل</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="filterMonthsLeft" value="expired"><label class="form-check-label">منتهي المدة</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="filterMonthsLeft" value="less3"><label class="form-check-label">أقل من 3 أشهر</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="filterMonthsLeft" value="3to6"><label class="form-check-label">3 إلى 6 أشهر</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="filterMonthsLeft" value="more6"><label class="form-check-label">أكثر من 6 أشهر</label></div>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                 <!-- Detachement Table - الجزء المصحح -->
<div class="table-container">
    <table id="detachementTable" class="custom-table">
        <thead>
            <tr>
                <th>الرقم الآلي</th>
                <th>الإسم و اللقب</th>
                <th>جهة الإلحاق</th>
                <th>المؤسّسة الآصليّة</th>
                <th>الوضعية</th>
                <th>تاريخ الإلحاق</th>
                <th>الفترة</th>
                <th>تجديد1</th>
                <th>تجديد2</th>
                <th>تجديد3</th>
                <th>المدّة المتبقّية</th>
                <th>الملف</th>
                <th>ملاحظات</th>
                <th>حالة المتابعة</th>
                <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] === "admin"): ?>
                    <th>الإجراءات</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_SESSION['congidGA'])) {
                // Récupérer le nombre de documents pour chaque agent
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
}
                }
            } else {
                echo '<tr><td colspan="15" class="text-center text-danger">الرجاء تسجيل الدخول</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
                <div class="warning-message"><i class="fas fa-info-circle me-2"></i> هذه القائمة تحتوي على بيانات الملحقين من جدول الإعارة</div>
            </div>
        </div>
    </div>

    <!-- Document Upload Modal -->
    <div class="modal fade" id="documentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-paperclip me-2"></i> <span id="modalDetachementName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="documentTabs">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#uploadTab"><i class="fas fa-upload"></i> رفع مستند</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#listTab"><i class="fas fa-folder-open"></i> المستندات</button></li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="uploadTab">
                            <form id="uploadDocumentForm" enctype="multipart/form-data">
                                <input type="hidden" id="uploadDetachementId" name="detachement_id">
                                <div class="mb-3"><label class="form-label">اختر المستند:</label><input type="file" class="form-control" id="documentFile" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" required><small class="text-muted">الأنواع: PDF, DOC, DOCX, JPG, PNG, XLS, XLSX (حد أقصى 10MB)</small></div>
                                <div class="mb-3"><label class="form-label">الوصف:</label><textarea class="form-control" id="documentDescription" name="description" rows="3"></textarea></div>
                                <div class="progress mb-3" style="display:none"><div class="progress-bar" style="width:0%">0%</div></div>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> رفع المستند</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="listTab"><div id="documentsList"><div class="text-center text-muted"><i class="fas fa-spinner fa-spin fa-2x"></i><p>جاري تحميل المستندات...</p></div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Document Modal -->
    <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-alt"></i> <span id="viewDocumentTitle"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="documentViewer" class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>جاري تحميل المستند...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">تأكيد الحذف</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p>هل أنت متأكد من حذف الملحق: <strong id="deleteAgentName"></strong>؟</p><p class="text-danger">لا يمكن التراجع عن هذا الإجراء.</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button><button type="button" class="btn btn-danger" id="confirmDelete">حذف</button></div></div></div></div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-success text-white"><h5 class="modal-title"><i class="fas fa-check-circle"></i> تم بنجاح</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p id="successMessage"></p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button></div></div></div></div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white"><h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> خطأ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p id="errorMessage"></p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button></div></div></div></div>

    <script>
    // Variables globales
    let currentDetachementId = null;
    let deleteId = null;

    // Toggle forms
    function toggleForm() {
        const form = document.getElementById("departForm");
        const btn = document.getElementById("toggleFormBtn");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
            if(btn) btn.innerHTML = '<i class="fas fa-eye-slash"></i><span>إخفاء النموذج</span>';
        } else {
            form.style.display = "none";
            if(btn) btn.innerHTML = '<i class="fas fa-user-plus"></i><span>إضافة مغادر</span>';
        }
    }

    function toggleDetachementForm() {
        const form = document.getElementById("detachementForm");
        const btn = document.getElementById("toggleDetachementFormBtn");
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
            if(btn) btn.innerHTML = '<i class="fas fa-eye-slash"></i><span>إخفاء النموذج</span>';
            resetDetachementForm();
        } else {
            form.style.display = "none";
            if(btn) btn.innerHTML = '<i class="fas fa-exchange-alt"></i><span>إضافة ملحق</span>';
        }
    }

    function resetDetachementForm() {
        $('#detachementFormData')[0].reset();
        $('#detachementId').val('');
        $('#detachementStatut').val('0');
        $('#saveDetachementText').text('حفظ بيانات الملحق');
        $('#detachementFormTitle').text('إضافة ملحق جديد');
        $('#editClosedetachement').prop('checked', false).prop('disabled', false);
    }

    function togglePrintOptions() {
        $("#printOptions").slideToggle();
    }

    function printTable(type) {
        let content = '', title = '';
        if(type === 'depart') { content = document.getElementById('depart').innerHTML; title = 'قائمة المغادرين'; }
        else if(type === 'detachement') { content = document.getElementById('detachement').innerHTML; title = 'قائمة الملحقين'; }
        else { content = document.getElementById('myTabContent').innerHTML; title = 'قائمة المغادرين والملحقين'; }
        
        const w = window.open('', '_blank');
        w.document.write(`
            <!DOCTYPE html><html dir="rtl"><head><meta charset="UTF-8"><title>${title}</title>
            <style>body{font-family:Arial;margin:20px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px;text-align:center}th{background:#2c3e50;color:#fff}</style>
            </head><body><h2>${title}</h2><div>${content}</div><script>window.onload=()=>window.print();<\/script></body></html>
        `);
        w.document.close();
    }

    // AJAX pour les infos utilisateur
    $('#mecano').on('change', function(){
        const mecano = $(this).val();
        if(mecano){
            $.ajax({
                url: 'get_user_info.php',
                type: 'GET',
                data: {mecano: mecano},
                dataType: 'json',
                success: function(data){
                    $('#nom').val(data.nom);
                    $('#departement').val(data.departement);
                    $('#daterecrutement').val(data.daterecrutement);
                    $('#restconge').val(data.restconge);
                    $('#dateretraite').val(data.dateretraite);
                    $('#envoie').show();
                },
                error: function(){
                    $('#nom,#departement,#daterecrutement,#restconge,#dateretraite').val('');
                    $('#envoie').hide();
                }
            });
        }
    });

    // Edit detachement
    $(document).on('click', '.edit-detachement', function(){
        $('#detachementId').val($(this).data('id'));
        $('#detachementMecano').val($(this).data('mecano'));
        $('#detachementNom').val($(this).data('nomprenom'));
        $('#detachementAffectation').val($(this).data('affectation'));
        $('#detachementSource').val($(this).data('source'));
        $('#detachementSituation').val($(this).data('situation'));
        $('#detachementDate').val($(this).data('datedetachement'));
        $('#detachementPeriode').val($(this).data('periode'));
        $('#detachementRenouvellement1').val($(this).data('renouvellemnt1'));
        $('#detachementRenouvellement2').val($(this).data('renouvellemnt2'));
        $('#detachementRenouvellement3').val($(this).data('renouvellemnt3'));
        $('#detachementDossier').val($(this).data('dossier'));
        $('#detachementObservations').val($(this).data('observations'));
        $('#detachementStatut').val($(this).data('statut'));
        
        if($(this).data('statut') == 1) {
            $('#editClosedetachement').prop('checked', true).prop('disabled', true);
        } else {
            $('#editClosedetachement').prop('checked', false).prop('disabled', false);
        }
        
        $('#saveDetachementText').text('تحديث بيانات الملحق');
        $('#detachementFormTitle').text('تعديل بيانات الملحق');
        $('#detachementForm').show();
        $('#toggleDetachementFormBtn').html('<i class="fas fa-eye-slash"></i><span>إخفاء النموذج</span>');
        $('html, body').animate({scrollTop: $('#detachementForm').offset().top - 100}, 500);
    });

    // Delete detachement
    $(document).on('click', '.delete-detachement', function(){
        deleteId = $(this).data('id');
        $('#deleteAgentName').text($(this).data('nomprenom'));
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function(){
        if(deleteId){
            $.ajax({
                url: 'delete_detachement.php',
                type: 'POST',
                data: {id: deleteId},
                dataType: 'json',
                success: function(res){
                    if(res.success) location.reload();
                    else showError(res.message);
                },
                error: () => showError('حدث خطأ في الحذف')
            });
        }
        $('#deleteModal').modal('hide');
    });

    // Save detachement
    $('#detachementFormData').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'save_detachement.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
                if(res.success){
                    showSuccess('تم حفظ البيانات بنجاح');
                    setTimeout(() => location.reload(), 1500);
                } else showError(res.message);
            },
            error: () => showError('حدث خطأ في الاتصال')
        });
    });

    function closedetachement(id){
        if(confirm('هل أنت متأكد من إغلاق هذا الملف؟')){
            $.ajax({
                url: 'close_detachement.php',
                type: 'POST',
                data: {id: id},
                success: function(){
                    showSuccess('تم إغلاق الملف بنجاح');
                    setTimeout(() => location.reload(), 1500);
                },
                error: () => showError('حدث خطأ أثناء إغلاق الملف')
            });
        }
    }

    // Filtres
    function filterDepartTable(){
        let filters = {};
        $('.column-filter-depart').each(function(){ if($(this).val()) filters[$(this).data('column')] = $(this).val().toLowerCase(); });
        $('#myTable tbody tr').each(function(){
            let show = true, cells = $(this).find('td');
            for(let col in filters){
                if(cells.length > col && cells.eq(col).text().toLowerCase().indexOf(filters[col]) === -1){ show = false; break; }
            }
            $(this).toggle(show);
        });
    }

    function filterDetachementTable(){
        let textFilters = {};
        $('.column-filter-detachement').each(function(){ if($(this).val()) textFilters[$(this).data('column')] = $(this).val().toLowerCase(); });
        const monthsFilter = $('input[name="filterMonthsLeft"]:checked').val();
        const statutFilter = $('#filterDetStatut').val();
        
        $('#detachementTable tbody tr').each(function(){
            let show = true, cells = $(this).find('td');
            for(let col in textFilters){
                if(cells.length > col && cells.eq(col).text().toLowerCase().indexOf(textFilters[col]) === -1){ show = false; break; }
            }
            if(show && monthsFilter !== 'all' && $(this).data('months-left') !== monthsFilter) show = false;
            if(show && statutFilter !== '' && $(this).data('statut').toString() !== statutFilter) show = false;
            $(this).toggle(show);
        });
    }

    $('.column-filter-depart').on('keyup change', filterDepartTable);
    $('.column-filter-detachement').on('keyup change', filterDetachementTable);
    $('input[name="filterMonthsLeft"]').on('change', filterDetachementTable);
    $('#filterDetStatut').on('change', filterDetachementTable);
    $('#clearFilters').on('click', function(){
        $('.column-filter-depart, .column-filter-detachement').val('');
        $('input[name="filterMonthsLeft"][value="all"]').prop('checked', true);
        $('#filterDetStatut').val('');
        filterDepartTable();
        filterDetachementTable();
    });

    // Document functions
    function openDocumentsModal(detachementId, detachementName){
        currentDetachementId = detachementId;
        $('#modalDetachementName').text('مستندات: ' + detachementName);
        $('#uploadDetachementId').val(detachementId);
        $('#uploadDocumentForm')[0].reset();
        $('.progress').hide();
        $('.progress-bar').css('width', '0%').text('0%');
        $('#documentModal').modal('show');
        loadDocumentsList(detachementId);
    }

    function loadDocumentsList(detachementId){
        $('#documentsList').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>جاري التحميل...</p></div>');
        $.ajax({
            url: 'get_documents_detachement.php',
            type: 'GET',
            data: {detachement_id: detachementId},
            dataType: 'json',
            success: function(res){
                if(res.success && res.documents && res.documents.length > 0){
                    let html = '<div class="list-group">';
                    res.documents.forEach(doc => {
                        let size = (doc.file_size/1024).toFixed(2) + ' KB';
                        if(doc.file_size > 1024*1024) size = (doc.file_size/(1024*1024)).toFixed(2) + ' MB';
                        let icon = 'fa-file-alt';
                        if(doc.document_type === 'pdf') icon = 'fa-file-pdf';
                        else if(doc.document_type === 'doc' || doc.document_type === 'docx') icon = 'fa-file-word';
                        else if(doc.document_type === 'xls' || doc.document_type === 'xlsx') icon = 'fa-file-excel';
                        else if(['jpg','jpeg','png'].includes(doc.document_type)) icon = 'fa-file-image';
                        
                        html += `<div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div><i class="fas ${icon} fa-lg me-2"></i><strong>${escapeHtml(doc.document_name)}</strong></div>
                                <div>
                                    <button class="btn btn-sm btn-primary" onclick="viewDocument('${doc.file_path}','${doc.document_name}')"><i class="fas fa-eye"></i> عرض</button>
                                    <button class="btn btn-sm btn-secondary" onclick="downloadDocument('${doc.file_path}','${doc.document_name}')"><i class="fas fa-download"></i> تحميل</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteDocument(${doc.id})"><i class="fas fa-trash"></i> حذف</button>
                                </div>
                            </div>
                            <div class="mt-2"><small><i class="fas fa-calendar"></i> ${doc.upload_date} | <i class="fas fa-user"></i> ${escapeHtml(doc.uploaded_by)} | ${size}</small></div>
                            ${doc.description ? `<div class="mt-1"><small><i class="fas fa-comment"></i> ${escapeHtml(doc.description)}</small></div>` : ''}
                        </div>`;
                    });
                    html += '</div>';
                    $('#documentsList').html(html);
                } else {
                    $('#documentsList').html('<div class="text-center text-muted py-5"><i class="fas fa-folder-open fa-3x"></i><p>لا توجد مستندات</p></div>');
                }
            },
            error: () => $('#documentsList').html('<div class="text-center text-danger py-5"><i class="fas fa-exclamation-triangle fa-3x"></i><p>خطأ في التحميل</p></div>')
        });
    }

    function viewDocument(path, name){
        $('#viewDocumentTitle').text(name);
        const ext = path.split('.').pop().toLowerCase();
        if(['jpg','jpeg','png','gif'].includes(ext)){
            $('#documentViewer').html(`<img src="${path}" class="img-fluid" style="max-height:80vh">`);
        } else if(ext === 'pdf'){
            $('#documentViewer').html(`<embed src="${path}" type="application/pdf" width="100%" height="80vh">`);
        } else {
            $('#documentViewer').html(`<div class="text-center py-5"><i class="fas fa-file-alt fa-4x"></i><p>لا يمكن عرض هذا النوع</p><a href="${path}" download class="btn btn-primary"><i class="fas fa-download"></i> تحميل</a></div>`);
        }
        $('#viewDocumentModal').modal('show');
    }

    function downloadDocument(path, name){
        const a = document.createElement('a');
        a.href = path;
        a.download = name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function deleteDocument(id){
        if(confirm('هل أنت متأكد من حذف هذا المستند؟')){
            $.ajax({
                url: 'delete_document_detachment.php',
                type: 'POST',
                data: {document_id: id},
                dataType: 'json',
                success: function(res){
                    if(res.success){
                        showSuccess(res.message);
                        loadDocumentsList(currentDetachementId);
                        // Mettre à jour le badge du nombre de documents
                        updateDocumentCount(currentDetachementId);
                    } else showError(res.message);
                },
                error: () => showError('حدث خطأ في الحذف')
            });
        }
    }

    function updateDocumentCount(detachementId){
        $.ajax({
            url: 'get_documents_count.php',
            type: 'GET',
            data: {detachement_id: detachementId},
            dataType: 'json',
            success: function(res){
                if(res.success){
                    const btn = $(`.view-documents[data-id="${detachementId}"]`);
                    const oldBadge = btn.find('.doc-count-badge');
                    if(res.count > 0){
                        if(oldBadge.length){
                            oldBadge.text(res.count);
                        } else {
                            btn.append(`<span class='doc-count-badge'>${res.count}</span>`);
                        }
                    } else {
                        oldBadge.remove();
                    }
                }
            }
        });
    }

    // Upload document
// Upload document - نسخة مبسطة مع ضمان إخفاء شريط التقدم
$('#uploadDocumentForm').on('submit', function(e) {
    e.preventDefault();
    
    const file = $('#documentFile')[0].files[0];
    if(!file){ 
        showError('الرجاء اختيار ملف'); 
        return; 
    }
    
    const ext = file.name.split('.').pop().toLowerCase();
    const allowed = ['pdf','doc','docx','jpg','jpeg','png','xls','xlsx'];
    if(!allowed.includes(ext)){ 
        showError('نوع الملف غير مسموح'); 
        return; 
    }
    
    if(file.size > 10*1024*1024){ 
        showError('حجم الملف يتجاوز 10MB'); 
        return; 
    }
    
    const formData = new FormData(this);
    const progress = $('.progress');
    const progressBar = $('.progress-bar');
    const btn = $(this).find('button[type="submit"]');
    const originalBtnHtml = btn.html();
    
    // إظهار شريط التقدم
    progress.show();
    progressBar.css('width', '0%').text('0%');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الرفع...');
    
    $.ajax({
        url: 'upload_document_detachement.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        timeout: 30000,
        xhr: function(){
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e){
                if(e.lengthComputable){
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.css('width', percent + '%').text(percent + '%');
                }
            });
            return xhr;
        },
        success: function(res){
            // إخفاء شريط التقدم فوراً
            progress.hide();
            progressBar.css('width', '0%').text('0%');
            btn.prop('disabled', false).html(originalBtnHtml);
            
            let result;
            try { 
                result = typeof res === 'string' ? JSON.parse(res) : res; 
            } catch(e){ 
                showError('خطأ في استجابة الخادم'); 
                return; 
            }
            
            if(result.success){
                showSuccess(result.message);
                $('#uploadDocumentForm')[0].reset();
                loadDocumentsList(currentDetachementId);
                updateDocumentCount(currentDetachementId);
                $('#listTab').tab('show');
            } else {
                showError(result.message);
            }
        },
        error: function(xhr, status, error){
            // إخفاء شريط التقدم فوراً
            progress.hide();
            progressBar.css('width', '0%').text('0%');
            btn.prop('disabled', false).html(originalBtnHtml);
            
            let errorMsg = 'خطأ في الرفع: ';
            if(xhr.responseText){
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg += response.message || error;
                } catch(e) {
                    errorMsg += 'خطأ في الخادم';
                }
            } else {
                errorMsg += error;
            }
            showError(errorMsg);
        }
    });
});

    $(document).on('click', '.view-documents', function(){
        openDocumentsModal($(this).data('id'), $(this).data('nomprenom'));
    });

    function showSuccess(msg){ $('#successMessage').text(msg); $('#successModal').modal('show'); }
    function showError(msg){ $('#errorMessage').text(msg); $('#errorModal').modal('show'); }
    function escapeHtml(text){ if(!text) return ''; return $('<div>').text(text).html(); }

    $(document).ready(function(){
        $('#departForm, #detachementForm, #printOptions').hide();
    });
    </script>
</body>
</html>