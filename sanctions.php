<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام متابعة الإستجوابات والعقوبات</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/xlsx.full.min.js"></script>
    <script src="JS/MyScript.js"></script>
    <?php  session_start();  ?>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --warning-color: #e74c3c;
            --danger-color: #c0392b;
            --alert-color: #f39c12;
            --success-color: #27ae60;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--warning-color) 100%);
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
        
        .stats-section {
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
            border-top: 4px solid var(--warning-color);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--warning-color) 0%, transparent 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
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
        .stat-expired .stat-value { color: var(--danger-color); }
        .stat-urgent .stat-value { color: var(--alert-color); }
        .stat-pending .stat-value { color: var(--warning-color); }
        .stat-closed .stat-value { color: var(--success-color); }
        
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
        
        .filters-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            border-right: 4px solid var(--warning-color);
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
            border-color: var(--warning-color);
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
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
            background-color: rgba(231, 76, 60, 0.05);
            transform: scale(1.01);
        }
        
        .status-expired {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--danger-color);
        }
        
        .status-urgent {
            background: linear-gradient(90deg, rgba(243, 156, 18, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--alert-color);
        }
        
        .status-today {
            background: linear-gradient(90deg, rgba(241, 196, 15, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid #f1c40f;
        }
        
        .status-closed {
            background: linear-gradient(90deg, rgba(39, 174, 96, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--success-color);
        }
        
        .badge-custom {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #c0392b 100%);
            color: white;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #922b21 100%);
            color: white;
        }
        
        .badge-alert {
            background: linear-gradient(135deg, var(--alert-color) 0%, #e67e22 100%);
            color: white;
        }
        
        .badge-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
        }
        
        .report-number {
            background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .mobile-message {
            display: none;
            background: linear-gradient(135deg, var(--warning-color) 0%, #c0392b 100%);
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
        
        .date-badge {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .sanction-badge {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .conseil-badge {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .dates-arret-box {
            background: #fff3cd;
            border-radius: 8px;
            padding: 8px 12px;
            border-right: 3px solid #ffc107;
            max-width: 250px;
            margin: 0 auto;
            text-align: right;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .dates-arret-box:hover {
            background: #ffeaa7;
            transform: translateX(-5px);
        }
        
        .fault-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            border-right: 3px solid var(--warning-color);
            max-width: 250px;
            margin: 0 auto;
            text-align: right;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .fault-box:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }
        
        .file-badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 2px;
        }
        
        .file-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .urgency-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-left: 5px;
            animation: pulse 2s infinite;
        }
        
        .indicator-expired { background-color: var(--danger-color); }
        .indicator-urgent { background-color: var(--alert-color); }
        .indicator-today { background-color: #f1c40f; }
        .indicator-closed { background-color: var(--success-color); }
        
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }
        
        .modal-content {
            border-radius: 15px;
            box-shadow: var(--hover-shadow);
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--warning-color) 0%, #c0392b 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .edit-modal {
            max-width: 800px;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        
        .file-upload-container {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .file-upload-container:hover {
            border-color: var(--warning-color);
            background: #e9ecef;
        }
        
        .file-upload-container i {
            font-size: 2rem;
            color: var(--warning-color);
            margin-bottom: 10px;
        }
        
        .uploaded-files {
            margin-top: 15px;
        }
        
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
        }
        
        .file-name {
            flex: 1;
            text-align: right;
        }
        
        .file-actions {
            display: flex;
            gap: 5px;
        }
        
        .file-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 6px;
        }
        
        /* Styles pour les fichiers dans le modal */
        .modal .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 10px;
            background: white;
            border-radius: 6px;
            margin-bottom: 6px;
            border: 1px solid #e9ecef;
            font-size: 0.85rem;
        }
        
        .modal .file-name {
            flex: 1;
            text-align: right;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
            cursor: help;
        }
        
        .modal .file-actions {
            display: flex;
            gap: 5px;
            align-items: center;
            flex-shrink: 0;
        }
        
        /* Tooltip pour les noms de fichiers dans le modal */
        .modal .file-name {
            position: relative;
        }
        
        .modal .file-name:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            right: 0;
            background: #333;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: normal;
            z-index: 1000;
            margin-bottom: 5px;
            max-width: 250px;
            word-wrap: break-word;
        }
        
        .closed-badge {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .close-btn {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .close-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        /* تحسينات للفلاتر */
        .filter-group {
            position: relative;
        }
        
        .filter-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 5;
        }
        
        .filter-input {
            padding-right: 12px;
            padding-left: 40px;
        }
        
        .sanction-filter {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
        }
        
        .sanction-filter:focus {
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25);
        }
        
        .sanction-filter option {
            background: white;
            color: #333;
        }
        
        /* Delete button styles */
        .delete-btn {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        /* تنسيق النافذة المنبثقة للحذف */
        #deleteModal .modal-content {
            border: 2px solid #dc3545;
        }
        
        #deleteModal .modal-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        #confirmDeleteBtn:disabled {
            background: #6c757d;
            border-color: #6c757d;
        }
        
        /* زر عرض سجل الحذف */
        .history-btn {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        @media (max-width: 1200px) {
            .custom-table {
                font-size: 0.85rem;
            }
            
            .custom-table thead th,
            .custom-table tbody td {
                padding: 10px 6px;
            }
            
            .fault-box,
            .dates-arret-box {
                max-width: 200px;
                font-size: 0.75rem;
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
            
            .fault-box,
            .dates-arret-box {
                max-width: 150px;
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-main">
        <?php 
        include('menu.php'); 
        require('DbConnexion.php');
        
        // التحقق من الصلاحيات
        if (!isset($_SESSION['congidGA'])) {
            header("Location: login.php");
            exit();
        }
        ?>

        <div class="header-section">
            <h1 class="header-title">متابعة الإستجوابات و العقوبات</h1>
            <?php
            include('SessionControl.php');
            ?>
        </div>

        <!-- Stats Section -->
        <div class="stats-section" id="statsContainer">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button id="clearFilters" class="btn btn-secondary btn-custom">
                <i class="fas fa-filter-circle-xmark"></i>
                <span>مسح كل الفلاتر</span>
            </button>
            
            <button onclick="exportTableToExcel('myTable', 'ListeQuestionnaires.xlsx')" class="btn btn-success btn-custom">
                <i class="fas fa-file-excel"></i>
                <span>تصدير إلى Excel</span>
            </button>
            
            <?php if ($_SESSION['departement'] == "admin"): ?>
                <a href="delete_log.php" class="btn btn-dark btn-custom history-btn">
                    <i class="fas fa-history"></i>
                    <span>سجل الحذف</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <h5 class="mb-3"><i class="fas fa-filter me-2"></i>فلاتر البحث المتقدم</h5>
            <div class="filter-grid">
                <div class="filter-group">
                    <i class="fas fa-id-card filter-icon"></i>
                    <input type="text" id="filterMecano" class="form-control form-control-custom filter-input column-filter" data-column="0" placeholder="البحث بالرقم الآلي...">
                </div>
                
                <div class="filter-group">
                    <i class="fas fa-user filter-icon"></i>
                    <input type="text" id="filterNom" class="form-control form-control-custom filter-input column-filter" data-column="1" placeholder="البحث بالإسم أو اللقب...">
                </div>
                
                <div class="filter-group">
                    <i class="fas fa-gavel filter-icon"></i>
                    <select id="filterSanctionType" class="form-control form-control-custom sanction-filter filter-input">
                        <option value="">جميع أنواع العقوبات</option>
                        <option value="no_sanction">بدون عقوبة</option>
                        <option value="إنذار">إنذار</option>
                        <option value="توبيخ">توبيخ</option>
                        <option value="تذكير للإمتثال">تذكير للإمتثال</option>
                        <option value="حفظ">حفظ</option>
                        <option value="طرد">طرد</option>
                        <?php
                        for ($i = 1; $i <= 60; $i++) {
                            echo '<option value="إيقاف بـ ' . $i . ' يوم">إيقاف بـ ' . $i . ' يوم</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <i class="fas fa-calendar-days filter-icon"></i>
                    <input type="text" id="filterDatesArret" class="form-control form-control-custom filter-input column-filter" data-column="11" placeholder="البحث بتواريخ الإيقاف...">
                </div>
                
                <div class="filter-group">
                    <i class="fas fa-flag filter-icon"></i>
                    <select id="filterStatus" class="form-control form-control-custom filter-input">
                        <option value="">جميع الحالات</option>
                        <option value="expired">منتهية الصلاحية</option>
                        <option value="urgent">عاجلة</option>
                        <option value="today">تنتهي اليوم</option>
                        <option value="closed">مغلقة</option>
                    </select>
                </div>

                <div class="filter-group">
                    <i class="fas fa-gavel filter-icon"></i>
                    <select id="filterConseilDiscipline" class="form-control form-control-custom filter-input">
                        <option value="">مجلس تأديب (الكل)</option>
                        <option value="1">نعم</option>
                        <option value="0">لا</option>
                    </select>
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
                            <th>الرقم الآلي</th>
                            <th>الإسم و اللقب</th>
                            <th>الرتبة</th>
                            <th>رقم التقرير</th>
                            <th>تاريخ التقرير</th>
                            <th>تاريخ الإستلام</th>
                            <th>تاريخ المخالفة</th>
                            <th>تاريخ الإستجواب</th>
                            <th>المخالفة المرتكبة</th>
                            <th>العقوبة</th>
                            <th>مجلس تأديب</th>
                            <th>تواريخ الإيقاف</th>
                            <th>تاريخ العقوبة</th>
                            <th>الملفات المرفوعة</th>
                            <th>حالة الملف</th>
                            <?php if ($_SESSION['departement'] == "admin"): ?>
                                <th>الإجراءات</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($_SESSION['congidGA'])) {
                            $departement = $_SESSION['departement'];

                            // Initialize counters for stats
                            $totalSanctions = 0;
                            $expiredSanctions = 0;
                            $urgentSanctions = 0;
                            $todaySanctions = 0;
                            $closedSanctions = 0;

                            // Build SELECT query with all fields including conseil_discipline
                          $sql = "SELECT 
    idsanction, sanctions.mecano, sanctions.nom, sanctions.grade, datefaute, datequestionnaire, 
    faute, sanction, dates_arret, datesanction, Datestamp, 
    DATEDIFF(datequestionnaire, CURRENT_DATE()) as datediff,
    report_number, report_date,
    questionnaire_file, report_file, sanction_decision_file, datereception,
    sanctions.statut, conseil_discipline
    FROM sanctions ";

if ($_SESSION['departement'] !== "admin") {

    $departements = is_array($_SESSION['departement']) 
        ? $_SESSION['departement'] 
        : [$_SESSION['departement']];

    $placeholders = implode(',', array_fill(0, count($departements), '?'));

    $sql .= " 
        LEFT JOIN stuf ON sanctions.mecano = stuf.mecano 
        WHERE stuf.dep IN ($placeholders)
    ";
}

$sql .= " ORDER BY statut DESC, datequestionnaire ASC ";

$stmt = mysqli_prepare($connection, $sql);

if ($_SESSION['departement'] !== "admin") {
    $types = str_repeat('i', count($departements));
    mysqli_stmt_bind_param($stmt, $types, ...$departements);
}

mysqli_stmt_execute($stmt);
                            
                            // Bind all result variables including conseil_discipline
                            mysqli_stmt_bind_result($stmt, 
                                $idsanction, $mecano, $nom, $grade, $datefaute, 
                                $datequestionnaire, $faute, $sanction, $dates_arret, $datesanction, 
                                $Datestamp, $datediff, $report_number, $report_date,
                                $questionnaire_file, $report_file, $sanction_decision_file, $datereception,
                                $statut, $conseil_discipline
                            );

                            while (mysqli_stmt_fetch($stmt)) {
                                $totalSanctions++;
                                
                                // Determine status and count
                                $statusClass = '';
                                $urgencyIndicator = '';
                                
                                if ($statut == 'closed') {
                                    $statusClass = 'status-closed';
                                    $urgencyIndicator = 'indicator-closed';
                                    $closedSanctions++;
                                } else if ($datediff < 0) {
                                    $statusClass = 'status-expired';
                                    $urgencyIndicator = 'indicator-expired';
                                    $expiredSanctions++;
                                } else if ($datediff >= 1 && $datediff <= 10) {
                                    $statusClass = 'status-urgent';
                                    $urgencyIndicator = 'indicator-urgent';
                                    $urgentSanctions++;
                                } else if ($datediff == 0) {
                                    $statusClass = 'status-today';
                                    $urgencyIndicator = 'indicator-today';
                                    $todaySanctions++;
                                }

                                echo '<tr class="' . $statusClass . ' fade-in">';
                                
                                // Employee ID
                                echo '<td>';
                                echo '<span class="employee-id">' . htmlspecialchars($mecano) . '</span>';
                                if ($urgencyIndicator) {
                                    echo '<span class="urgency-indicator ' . $urgencyIndicator . '"></span>';
                                }
                                echo '</td>';
                                
                                // Name
                                echo '<td><strong>' . htmlspecialchars($nom) . '</strong></td>';
                                
                                // Grade
                                echo '<td><span class="badge badge-custom badge-primary">' . htmlspecialchars($grade) . '</span></td>';
                                
                                // Report Number
                                echo '<td><span class="report-number">' . (!empty($report_number) ? htmlspecialchars($report_number) : 'غير محدد') . '</span></td>';
                                
                                // Report Date
                                echo '<td><span class="date-badge">' . (!empty($report_date) ? htmlspecialchars($report_date) : 'غير محدد') . '</span></td>';
                                echo '<td><span class="date-badge">' . (!empty($datereception) ? htmlspecialchars($datereception) : 'غير محدد') . '</span></td>';
                                // Dates with badges
                                echo '<td><span class="date-badge">' . (!empty($datefaute) ? htmlspecialchars($datefaute) : 'غير محدد') . '</span></td>';
                                echo '<td><span class="date-badge">' . (!empty($datequestionnaire) ? htmlspecialchars($datequestionnaire) : 'غير محدد') . '</span></td>';
                                
                                // Fault description
                                $cleanFaute = htmlspecialchars(strip_tags($faute));
                                echo '<td>';
                                echo '<div class="fault-box" title="' . $cleanFaute . '">';
                                echo (strlen($cleanFaute) > 50 ? substr($cleanFaute, 0, 50) . '...' : $cleanFaute);
                                echo '</div>';
                                echo '</td>';
                                
                                // Sanction
                                echo '<td><span class="sanction-badge">' . htmlspecialchars($sanction) . '</span></td>';
                                
                                // Conseil de discipline
                                echo '<td>';
                                if ($conseil_discipline == 1) {
                                    echo '<span class="conseil-badge"><i class="fas fa-gavel me-1"></i>مجلس تأديب</span>';
                                } else {
                                    echo '<span class="badge bg-secondary"><i class="fas fa-times me-1"></i>لا</span>';
                                }
                                echo '</td>';
                                
                                // Dates d'arrêt
                                echo '<td>';
                                if (!empty($dates_arret)) {
                                    $cleanDatesArret = htmlspecialchars(strip_tags($dates_arret));
                                    echo '<div class="dates-arret-box" title="' . $cleanDatesArret . '">';
                                    echo (strlen($cleanDatesArret) > 30 ? substr($cleanDatesArret, 0, 30) . '...' : $cleanDatesArret);
                                    echo '</div>';
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                }
                                echo '</td>';
                                
                                // Sanction date
                                echo '<td><span class="date-badge">' . (!empty($datesanction) ? htmlspecialchars($datesanction) : 'غير محدد') . '</span></td>';
								?>
                                
                                
<?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>

<td>
    <?php
	// Uploaded files
	
    $hasFiles = false;

    if (!empty($questionnaire_file)) {
        ?>
        <span class="file-badge" onclick="downloadFile('<?= $questionnaire_file ?>', 'questionnaire')">
            <i class="fas fa-file-pdf me-1"></i>استجواب
        </span>
        <?php
        $hasFiles = true;
    }

    if (!empty($report_file)) {
        ?>
        <span class="file-badge" onclick="downloadFile('<?= $report_file ?>', 'report')">
            <i class="fas fa-file-word me-1"></i>تقرير
        </span>
        <?php
        $hasFiles = true;
    }

    if (!empty($sanction_decision_file)) {
        ?>
        <span class="file-badge" onclick="downloadFile('<?= $sanction_decision_file ?>', 'sanction')">
            <i class="fas fa-file-contract me-1"></i>قرار
        </span>
        <?php
        $hasFiles = true;
    }

    if (!$hasFiles) {
        echo '<span class="text-muted">لا توجد ملفات</span>';
    }
    ?>
</td>

<td>
    <?php if ($statut == 'closed'): ?>
        <span class="closed-badge">
            <i class="fas fa-check-circle me-1"></i>مغلق
        </span>
    <?php else: ?>
        <span class="badge badge-warning">
            <i class="fas fa-clock me-1"></i>قيد المعالجة
        </span>
    <?php endif; ?>
</td>

<?php endif; ?>
<?php
                                // Admin actions
                                if ($_SESSION['departement'] == "admin") {
                                    echo '<td>';
                                    echo '<button class="btn btn-sm btn-outline-primary edit-btn" 
                                        data-id="' . $idsanction . '" 
                                        data-mecano="' . htmlspecialchars($mecano) . '" 
                                        data-nom="' . htmlspecialchars($nom) . '" 
                                        data-grade="' . htmlspecialchars($grade) . '" 
                                        data-datefaute="' . htmlspecialchars($datefaute) . '" 
                                        data-datequestionnaire="' . htmlspecialchars($datequestionnaire) . '" 
                                        data-faute="' . htmlspecialchars($faute) . '" 
                                        data-sanction="' . htmlspecialchars($sanction) . '" 
                                        data-dates-arret="' . htmlspecialchars($dates_arret) . '" 
                                        data-datesanction="' . htmlspecialchars($datesanction) . '" 
                                        data-datestamp="' . htmlspecialchars($Datestamp) . '" 
                                        data-report-number="' . htmlspecialchars($report_number) . '" 
                                        data-report-date="' . htmlspecialchars($report_date) . '" 
                                        data-questionnaire-file="' . htmlspecialchars($questionnaire_file) . '" 
                                        data-report-file="' . htmlspecialchars($report_file) . '" 
                                        data-sanction-decision-file="' . htmlspecialchars($sanction_decision_file) . '" 
                                        data-datereception="' . htmlspecialchars($datereception) . '" 
                                        data-statut="' . htmlspecialchars($statut) . '"
                                        data-conseil-discipline="' . htmlspecialchars($conseil_discipline) . '">';
                                    echo '<i class="fas fa-edit"></i>';
                                    echo '</button>';
                                    echo '<a href="print_questionnaire.php?id=' . $idsanction . '" class="btn btn-sm btn-outline-primary" target="_blank">';
                                    echo '<i class="fas fa-print"></i>';
                                    echo '</a>';
                                    
                                    // Close button only for non-closed records
                                    if ($statut != 'closed') {
                                        echo '<button class="btn btn-sm btn-success close-btn ms-1" onclick="closeQuestionnaire(' . $idsanction . ')" title="إغلاق الملف">';
                                        echo '<i class="fas fa-lock"></i>';
                                        echo '</button>';
                                    }
                                    
                                    // Delete button
                                    echo '<button class="btn btn-sm btn-outline-danger ms-1 delete-btn" data-id="' . $idsanction . '" data-mecano="' . htmlspecialchars($mecano) . '" data-nom="' . htmlspecialchars($nom) . '" data-sanction="' . htmlspecialchars($sanction) . '" title="حذف الإستجواب">';
                                    echo '<i class="fas fa-trash"></i>';
                                    echo '</button>';
                                    
                                    echo '</td>';
                                }

                                echo '</tr>';
                            }

                            mysqli_stmt_close($stmt);
                            
                            // Update stats via JavaScript
                            echo '
                            <script>
                                document.getElementById("statsContainer").innerHTML = `
                                    <div class="stat-card stat-total">
                                        <div class="stat-value">' . $totalSanctions . '</div>
                                        <div class="stat-label">إجمالي الإستجوابات</div>
                                    </div>
                                    <div class="stat-card stat-expired">
                                        <div class="stat-value">' . $expiredSanctions . '</div>
                                        <div class="stat-label">منتهية الصلاحية</div>
                                    </div>
                                    <div class="stat-card stat-urgent">
                                        <div class="stat-value">' . $urgentSanctions . '</div>
                                        <div class="stat-label">عاجلة (1-10 أيام)</div>
                                    </div>
                                    <div class="stat-card stat-pending">
                                        <div class="stat-value">' . $todaySanctions . '</div>
                                        <div class="stat-label">تنتهي اليوم</div>
                                    </div>
                                    <div class="stat-card stat-closed">
                                        <div class="stat-value">' . $closedSanctions . '</div>
                                        <div class="stat-label">مغلقة</div>
                                    </div>
                                `;
                            </script>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"><i class="fas fa-edit me-2"></i>تعديل بيانات الإستجواب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editId" name="id">
                        <input type="hidden" id="editStatut" name="statut">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editMecano" class="form-label">الرقم الآلي</label>
                                    <input type="text" class="form-control form-control-custom" id="editMecano" name="mecano" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editNom" class="form-label">الإسم و اللقب</label>
                                    <input type="text" class="form-control form-control-custom" id="editNom" name="nom" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editGrade" class="form-label">الرتبة</label>
                                    <input type="text" class="form-control form-control-custom" id="editGrade" name="grade" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editReportNumber" class="form-label">رقم التقرير</label>
                                    <input type="text" class="form-control form-control-custom" id="editReportNumber" name="report_number">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editReportDate" class="form-label">تاريخ التقرير</label>
                                    <input type="date" class="form-control form-control-custom" id="editReportDate" name="report_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDateFaute" class="form-label">تاريخ المخالفة</label>
                                    <input type="date" class="form-control form-control-custom" id="editDateFaute" name="datefaute">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDatereception" class="form-label">تاريخ الإستلام</label>
                                    <input type="date" class="form-control form-control-custom" id="editDatereception" name="datereception" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDateQuestionnaire" class="form-label">تاريخ الإستجواب</label>
                                    <input type="date" class="form-control form-control-custom" id="editDateQuestionnaire" name="datequestionnaire" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editSanction" class="form-label">العقوبة</label>
                                    <select class="form-control form-control-custom" id="editSanction" name="sanction">
                                        <option value="إنذار">إنذار</option>
                                        <option value="توبيخ">توبيخ</option>
                                        <option value="تذكير للإمتثال">تذكير للإمتثال</option>
                                        <option value="حفظ">حفظ</option>
                                        <option value="طرد">طرد</option>
                                        <?php
                                        for ($i = 1; $i <= 60; $i++) {
                                            echo '<option value="إيقاف بـ ' . $i . ' يوم">إيقاف بـ ' . $i . ' يوم</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="editConseilDiscipline" name="conseil_discipline" value="1">
                                        <label class="form-check-label fw-bold" for="editConseilDiscipline">
                                            <i class="fas fa-gavel me-2 text-warning"></i>مجلس تأديب
                                        </label>
                                        <small class="form-text text-muted d-block">تحديد إذا كانت المخالفة تمت فيها إحالة إلى مجلس تأديب</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDateSanction" class="form-label">تاريخ العقوبة</label>
                                    <input type="date" class="form-control form-control-custom" id="editDateSanction" name="datesanction">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editDatesArret" class="form-label">تواريخ الإيقاف</label>
                                    <textarea class="form-control form-control-custom" id="editDatesArret" name="dates_arret" rows="2" placeholder="أدخل التواريخ مثل: 02-03-04/05/2025, 02/05/2025 و 03/05/2025..."></textarea>
                                    <small class="form-text text-muted">يمكن إدخال تواريخ متعددة مفصولة بفواصل أو شرطات</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="editFaute" class="form-label">المخالفة المرتكبة</label>
                            <textarea class="form-control form-control-custom" id="editFaute" name="faute" rows="4" required></textarea>
                        </div>

                        <!-- File Upload Section -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">استجواب</label>
                                    <div class="file-upload-container" onclick="document.getElementById('questionnaireFile').click()">
                                        <i class="fas fa-file-pdf"></i>
                                        <p>انقر لرفع ملف الإستجواب</p>
                                        <input type="file" id="questionnaireFile" name="questionnaire_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
                                    </div>
                                    <div id="questionnairePreview" class="uploaded-files"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">تقرير</label>
                                    <div class="file-upload-container" onclick="document.getElementById('reportFile').click()">
                                        <i class="fas fa-file-word"></i>
                                        <p>انقر لرفع ملف التقرير</p>
                                        <input type="file" id="reportFile" name="report_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
                                    </div>
                                    <div id="reportPreview" class="uploaded-files"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">قرار العقوبة</label>
                                    <div class="file-upload-container" onclick="document.getElementById('sanctionDecisionFile').click()">
                                        <i class="fas fa-file-contract"></i>
                                        <p>انقر لرفع قرار العقوبة</p>
                                        <input type="file" id="sanctionDecisionFile" name="sanction_decision_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="display: none;">
                                    </div>
                                    <div id="sanctionDecisionPreview" class="uploaded-files"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Close Questionnaire Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="editCloseQuestionnaire" name="close_questionnaire">
                                        <label class="form-check-label" for="editCloseQuestionnaire">
                                            <i class="fas fa-lock me-2"></i>إغلاق الملف (تم تطبيق العقوبة)
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">سيتم إغلاق الملف بعد تطبيق العقوبة النهائية</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>تأكيد الحذف
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>تحذير:</strong> هذه العملية لا يمكن التراجع عنها!
                    </div>
                    
                    <div class="mb-3">
                        <p>أنت على وشك حذف استجواب الموظف:</p>
                        <div class="card bg-light">
                            <div class="card-body">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th class="text-end">الرقم الآلي:</th>
                                        <td id="deleteMecano" class="fw-bold"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">الاسم:</th>
                                        <td id="deleteNom" class="fw-bold"></td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">العقوبة:</th>
                                        <td id="deleteSanction" class="fw-bold text-danger"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <form id="deleteForm">
                        <input type="hidden" id="deleteId" name="id">
                        
                        <div class="form-group mb-3">
                            <label for="deleteReason" class="form-label">
                                <i class="fas fa-comment-dots me-2"></i>سبب الحذف
                            </label>
                            <textarea class="form-control form-control-custom" id="deleteReason" 
                                      name="reason" rows="3" 
                                      placeholder="أدخل سبب حذف هذا الإستجواب..." 
                                      required></textarea>
                            <small class="form-text text-muted">سيتم حفظ هذا السبب في سجل الحذف.</small>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                            <label class="form-check-label" for="confirmDelete">
                                أنا أدرك أن هذه العملية لا يمكن التراجع عنها وأتخذ المسؤولية الكاملة
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                        <i class="fas fa-trash me-2"></i>حذف نهائي
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>تمت العملية بنجاح</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage">تم تحديث البيانات بنجاح.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">موافق</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fonction pour raccourcir le nom du fichier (uniquement pour le modal)
        function shortenFileName(fileName, maxLength = 25) {
            if (fileName.length <= maxLength) {
                return fileName;
            }
            
            const extension = fileName.split('.').pop();
            const nameWithoutExt = fileName.substring(0, fileName.length - extension.length - 1);
            const charsToKeep = maxLength - extension.length - 3; // -3 pour "..."
            
            if (charsToKeep <= 0) {
                return fileName.substring(0, maxLength - 3) + '...';
            }
            
            return nameWithoutExt.substring(0, charsToKeep) + '...' + extension;
        }

        // Filter table function
        function filterTable() {
            var filters = {};
            $('.column-filter').each(function() {
                if ($(this).val()) {
                    filters[$(this).data('column')] = $(this).val().toLowerCase();
                }
            });
            
            var statusFilter = $('#filterStatus').val();
            var sanctionFilter = $('#filterSanctionType').val();
            var conseilFilter = $('#filterConseilDiscipline').val();
            
            $('#myTable tbody tr').each(function() {
                var showRow = true;
                var cells = $(this).find('td');
                
                // Text filters
                for (var col in filters) {
                    if (filters.hasOwnProperty(col)) {
                        var cellText = cells.eq(col).text().toLowerCase();
                        if (cellText.indexOf(filters[col]) === -1) {
                            showRow = false;
                            break;
                        }
                    }
                }
                
                // Status filter
                if (showRow && statusFilter) {
                    var rowClass = $(this).attr('class') || '';
                    if (statusFilter === 'expired' && !rowClass.includes('status-expired')) {
                        showRow = false;
                    } else if (statusFilter === 'urgent' && !rowClass.includes('status-urgent')) {
                        showRow = false;
                    } else if (statusFilter === 'today' && !rowClass.includes('status-today')) {
                        showRow = false;
                    } else if (statusFilter === 'closed' && !rowClass.includes('status-closed')) {
                        showRow = false;
                    }
                }
                
                // Sanction type filter
                if (showRow && sanctionFilter) {
                    var sanctionText = cells.eq(9).text().trim();
                    
                    if (sanctionFilter === 'no_sanction') {
                        if (sanctionText !== '' && sanctionText !== 'غير محدد' && sanctionText !== 'null' && sanctionText !== 'undefined') {
                            showRow = false;
                        }
                    } else {
                        if (sanctionText !== sanctionFilter) {
                            showRow = false;
                        }
                    }
                }

                // Conseil discipline filter
                if (showRow && conseilFilter) {
                    var conseilCell = cells.eq(10).text().trim();
                    if (conseilFilter === '1' && !conseilCell.includes('مجلس تأديب')) {
                        showRow = false;
                    } else if (conseilFilter === '0' && conseilCell.includes('مجلس تأديب')) {
                        showRow = false;
                    }
                }
                
                $(this).toggle(showRow);
            });
        }
        
        $('.column-filter').on('keyup change', filterTable);
        $('#filterStatus').on('change', filterTable);
        $('#filterSanctionType').on('change', filterTable);
        $('#filterConseilDiscipline').on('change', filterTable);
        
        $('#clearFilters').on('click', function() {
            $('.column-filter').val('');
            $('#filterStatus').val('');
            $('#filterSanctionType').val('');
            $('#filterConseilDiscipline').val('');
            filterTable();
        });

        // Edit modal functionality
        $(document).ready(function() {
            // Initialize Bootstrap modals
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            
            // Handle edit button click
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                var mecano = $(this).data('mecano');
                var nom = $(this).data('nom');
                var grade = $(this).data('grade');
                var datefaute = $(this).data('datefaute');
                var datequestionnaire = $(this).data('datequestionnaire');
                var faute = $(this).data('faute');
                var sanction = $(this).data('sanction');
                var datesArret = $(this).data('dates-arret');
                var datereception = $(this).data('datereception');
                var datesanction = $(this).data('datesanction');
                var datestamp = $(this).data('datestamp');
                var reportNumber = $(this).data('report-number');
                var reportDate = $(this).data('report-date');
                var questionnaireFile = $(this).data('questionnaire-file');
                var reportFile = $(this).data('report-file');
                var sanctionDecisionFile = $(this).data('sanction-decision-file');
                var statut = $(this).data('statut');
                var conseilDiscipline = $(this).data('conseil-discipline');
                
                // Fill form with current data
                $('#editId').val(id);
                $('#editMecano').val(mecano);
                $('#editNom').val(nom);
                $('#editGrade').val(grade);
                $('#editDateFaute').val(datefaute);
                $('#editDateQuestionnaire').val(datequestionnaire);
                $('#editDatereception').val(datereception);
                $('#editFaute').val(faute);
                $('#editSanction').val(sanction);
                $('#editDatesArret').val(datesArret || '');
                $('#editDateSanction').val(datesanction);
                $('#editDatestamp').val(datestamp);
                $('#editReportNumber').val(reportNumber);
                $('#editReportDate').val(reportDate);
                $('#editStatut').val(statut);
                
                // Set conseil discipline checkbox
                if (conseilDiscipline == 1) {
                    $('#editConseilDiscipline').prop('checked', true);
                } else {
                    $('#editConseilDiscipline').prop('checked', false);
                }
                
                // Set close checkbox based on current status
                if (statut == 'closed') {
                    $('#editCloseQuestionnaire').prop('checked', true);
                    $('#editCloseQuestionnaire').prop('disabled', true);
                } else {
                    $('#editCloseQuestionnaire').prop('checked', false);
                    $('#editCloseQuestionnaire').prop('disabled', false);
                }
                
                // Clear previous file previews
                $('#questionnairePreview').empty();
                $('#reportPreview').empty();
                $('#sanctionDecisionPreview').empty();
                
                // Show existing files if they exist
                if (questionnaireFile) {
                    $('#questionnairePreview').append(createFilePreview(questionnaireFile, 'questionnaire', false));
                }
                if (reportFile) {
                    $('#reportPreview').append(createFilePreview(reportFile, 'report', false));
                }
                if (sanctionDecisionFile) {
                    $('#sanctionDecisionPreview').append(createFilePreview(sanctionDecisionFile, 'sanction_decision', false));
                }
                
                // Show modal
                editModal.show();
            });
            
            // Handle file input changes
            $('#questionnaireFile').on('change', function() {
                handleFilePreview(this, 'questionnairePreview', 'questionnaire');
            });
            
            $('#reportFile').on('change', function() {
                handleFilePreview(this, 'reportPreview', 'report');
            });
            
            $('#sanctionDecisionFile').on('change', function() {
                handleFilePreview(this, 'sanctionDecisionPreview', 'sanction_decision');
            });
            
            // Handle form submission
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                
                $.ajax({
                    url: 'update_sanction.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        editModal.hide();
                        $('#successMessage').text('تم تحديث البيانات بنجاح.');
                        successModal.show();
                        
                        // Reload page after 2 seconds to show updated data
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        editModal.hide();
                        $('#successMessage').text('حدث خطأ أثناء تحديث البيانات: ' + error);
                        successModal.show();
                    }
                });
            });
        });

        // Delete button functionality
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var mecano = $(this).data('mecano');
            var nom = $(this).data('nom');
            var sanction = $(this).data('sanction');
            
            $('#deleteId').val(id);
            $('#deleteMecano').text(mecano);
            $('#deleteNom').text(nom);
            $('#deleteSanction').text(sanction);
            
            // Reset form
            $('#deleteReason').val('');
            $('#confirmDelete').prop('checked', false);
            $('#confirmDeleteBtn').prop('disabled', true);
            
            // Show modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        });

        // Enable delete button when checkbox is checked
        $('#confirmDelete').on('change', function() {
            $('#confirmDeleteBtn').prop('disabled', !this.checked);
        });

        // Handle delete confirmation
        $('#confirmDeleteBtn').on('click', function() {
            if (!$('#confirmDelete').prop('checked')) {
                alert('يجب تأكيد الموافقة على الحذف أولاً');
                return;
            }
            
            if ($('#deleteReason').val().trim() === '') {
                alert('يجب إدخال سبب الحذف');
                return;
            }
            
            if (!confirm('هل أنت متأكد تماماً من حذف هذا الإستجواب؟ هذه العملية نهائية ولا يمكن التراجع عنها!')) {
                return;
            }
            
            var formData = {
                id: $('#deleteId').val(),
                reason: $('#deleteReason').val()
            };
            
            // Disable button and show loading
            var $deleteBtn = $(this);
            $deleteBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحذف...');
            
            $.ajax({
                url: 'delete_sanction.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        // Close modal
                        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();
                        
                        // Show success message
                        $('#successMessage').html('<i class="fas fa-check-circle me-2"></i>' + response.message);
                        $('#successModal').modal('show');
                        
                        // Reload page after 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        var errorMsg = response && response.message ? response.message : 'حدث خطأ غير معروف';
                        alert('خطأ: ' + errorMsg);
                        $deleteBtn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>حذف نهائي');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('حدث خطأ في الاتصال بالسيرفر: ' + error + '\nتفاصيل: ' + xhr.responseText.substring(0, 100));
                    $deleteBtn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>حذف نهائي');
                },
                complete: function() {
                    setTimeout(function() {
                        $deleteBtn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>حذف نهائي');
                    }, 5000);
                }
            });
        });

        // File download function
        function downloadFile(filename, type) {
            window.open('download_file.php?file=' + filename + '&type=' + type, '_blank');
        }

        // File preview functions
        function handleFilePreview(input, previewContainerId, fileType) {
            const file = input.files[0];
            if (file) {
                const previewContainer = $('#' + previewContainerId);
                previewContainer.empty();
                
                const filePreview = createFilePreview(file.name, fileType, true);
                previewContainer.append(filePreview);
                
                // Create image preview for image files
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $('<img>', {
                            src: e.target.result,
                            class: 'file-preview'
                        });
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
        
        function createFilePreview(fileName, fileType, isNew = false) {
            const fileExt = fileName.split('.').pop().toLowerCase();
            let iconClass = 'fas fa-file';
            const shortFileName = shortenFileName(fileName, 20);
            
            if (fileExt === 'pdf') iconClass = 'fas fa-file-pdf text-danger';
            else if (['doc', 'docx'].includes(fileExt)) iconClass = 'fas fa-file-word text-primary';
            else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) iconClass = 'fas fa-file-image text-success';
            
            const deleteButton = isNew ? 
                `<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile('${fileType}')" title="حذف الملف">
                    <i class="fas fa-times"></i>
                </button>` :
                `<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeExistingFile('${fileType}', '${fileName}')" title="حذف الملف">
                    <i class="fas fa-trash"></i>
                </button>`;
            
            return `
                <div class="file-item">
                    <div class="file-name" title="${fileName}">
                        <i class="${iconClass} me-2"></i>
                        ${shortFileName}
                    </div>
                    <div class="file-actions">
                        ${isNew ? '<span class="badge bg-success">جديد</span>' : '<span class="badge bg-info">موجود</span>'}
                        ${deleteButton}
                    </div>
                </div>
            `;
        }
        
        function removeFile(fileType) {
            if (confirm('هل أنت متأكد من رغبتك في حذف هذا الملف؟')) {
                if (fileType === 'questionnaire') {
                    $('#questionnaireFile').val('');
                    $('#questionnairePreview').empty();
                    $('#questionnairePreview').append('<input type="hidden" name="delete_questionnaire_file" value="1">');
                } else if (fileType === 'report') {
                    $('#reportFile').val('');
                    $('#reportPreview').empty();
                    $('#reportPreview').append('<input type="hidden" name="delete_report_file" value="1">');
                } else if (fileType === 'sanction_decision') {
                    $('#sanctionDecisionFile').val('');
                    $('#sanctionDecisionPreview').empty();
                    $('#sanctionDecisionPreview').append('<input type="hidden" name="delete_sanction_decision_file" value="1">');
                }
            }
        }

        function removeExistingFile(fileType, fileName) {
            if (confirm('هل أنت متأكد من رغبتك في حذف هذا الملف؟')) {
                if (fileType === 'questionnaire') {
                    $('#questionnairePreview').empty();
                    $('#questionnairePreview').append('<input type="hidden" name="delete_questionnaire_file" value="1">');
                    $('#questionnairePreview').append('<input type="hidden" name="existing_questionnaire_file" value="' + fileName + '">');
                } else if (fileType === 'report') {
                    $('#reportPreview').empty();
                    $('#reportPreview').append('<input type="hidden" name="delete_report_file" value="1">');
                    $('#reportPreview').append('<input type="hidden" name="existing_report_file" value="' + fileName + '">');
                } else if (fileType === 'sanction_decision') {
                    $('#sanctionDecisionPreview').empty();
                    $('#sanctionDecisionPreview').append('<input type="hidden" name="delete_sanction_decision_file" value="1">');
                    $('#sanctionDecisionPreview').append('<input type="hidden" name="existing_sanction_decision_file" value="' + fileName + '">');
                }
            }
        }

        // Close questionnaire function
        function closeQuestionnaire(id) {
            if (confirm('هل أنت متأكد من رغبتك في إغلاق هذا الملف؟ سيتم اعتباره مغلقاً ولا يمكن تعديله لاحقاً.')) {
                $.ajax({
                    url: 'close_questionnaire.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        $('#successMessage').text('تم إغلاق الملف بنجاح.');
                        $('#successModal').modal('show');
                        
                        // Reload page after 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        $('#successMessage').text('حدث خطأ أثناء إغلاق الملف: ' + error);
                        $('#successModal').modal('show');
                    }
                });
            }
        }

        // Add hover effects and tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('#myTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
            
            // Add tooltip for full fault descriptions
            $('.fault-box').each(function() {
                const fullText = $(this).attr('title');
                if (fullText) {
                    $(this).tooltip({
                        title: fullText,
                        placement: 'top',
                        trigger: 'hover'
                    });
                }
            });
            
            // Add tooltip for dates d'arrêt
            $('.dates-arret-box').each(function() {
                const fullText = $(this).attr('title');
                if (fullText) {
                    $(this).tooltip({
                        title: fullText,
                        placement: 'top',
                        trigger: 'hover'
                    });
                }
            });
        });

        // Export to Excel function
        function exportTableToExcel(tableID, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            
            // Specify file name
            filename = filename?filename+'.xls':'excel_data.xls';
            
            // Create download link element
            downloadLink = document.createElement("a");
            
            document.body.appendChild(downloadLink);
            
            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['\ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            
                // Setting the file name
                downloadLink.download = filename;
                
                //triggering the function
                downloadLink.click();
            }
        }
    </script>
</body>
</html>