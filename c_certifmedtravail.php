<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام متابعة الشهادات الطبية</title>
    <?php session_start(); ?>
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

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --medical-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --expired-color: #c0392b;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e8 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--medical-color) 100%);
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
            border-top: 4px solid var(--medical-color);
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
            background: linear-gradient(90deg, var(--medical-color) 0%, transparent 100%);
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
        .stat-expired .stat-value { color: var(--expired-color); }
        .stat-urgent .stat-value { color: var(--warning-color); }
        .stat-valid .stat-value { color: var(--medical-color); }
        
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
        
        .add-certificate-btn {
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .add-certificate-btn:hover {
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
            border-right: 4px solid var(--medical-color);
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
            border-color: var(--medical-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
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
            background-color: rgba(39, 174, 96, 0.05);
            transform: scale(1.01);
        }
        
        .status-expired {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--expired-color);
        }
        
        .status-urgent {
            background: linear-gradient(90deg, rgba(243, 156, 18, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--warning-color);
        }
        
        .status-today {
            background: linear-gradient(90deg, rgba(241, 196, 15, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid #f1c40f;
        }
        
        .badge-custom {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .badge-medical {
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
        }
        
        .badge-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
            color: white;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
            color: white;
        }
        
        .badge-expired {
            background: linear-gradient(135deg, var(--expired-color) 0%, #922b21 100%);
            color: white;
        }
        
        .mobile-message {
            display: none;
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
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
        
        .certificate-badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .observation-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            border-right: 3px solid var(--medical-color);
            max-width: 400px;
            margin: 0 auto;
            text-align: right;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .observation-box:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
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
            background: var(--medical-color);
            color: white;
        }

        .btn-export:hover {
            background: #229954;
            color: white;
        }
        
        .btn-print {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-print:hover {
            background: #2980b9;
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
        
        /* تنسيقات خاصة للطباعة */
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 12px;
                margin: 0;
            }
            
            .container-main {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            
            .header-section {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-bottom: 15px;
                box-shadow: none;
                border: 1px solid #ddd;
                padding: 15px;
            }
            
            .header-title {
                color: black !important;
                font-size: 18px;
                margin-bottom: 5px;
            }
            
            /* إخفاء جميع العناصر غير المرغوبة في الطباعة */
            .stats-section, .controls-section, .filter-section, .mobile-message, 
            .warning-message, .action-buttons, .modal, .btn,
            .header-subtitle, .no-print {
                display: none !important;
            }
            
            .table-container {
                box-shadow: none;
                border-radius: 0;
                border: 1px solid #ddd;
                margin-bottom: 0;
            }
            
            .custom-table {
                font-size: 10px;
                width: 100%;
                border: 1px solid #ddd;
            }
            
            .custom-table thead th {
                background-color: #f8f9fa !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border: 1px solid #ddd;
                padding: 8px 5px;
                font-weight: bold;
            }
            
            .custom-table tbody td {
                border: 1px solid #ddd;
                padding: 6px 4px;
                text-align: center;
                vertical-align: middle;
            }
            
            .status-expired, .status-urgent, .status-today {
                background: white !important;
                border-right: none !important;
            }
            
            .urgency-indicator {
                display: none;
            }
            
            /* إخفاء التنسيقات الخاصة وإظهار البيانات النصية فقط */
            .employee-id, .date-badge, .certificate-badge, .badge-custom, .observation-box {
                background: none !important;
                color: black !important;
                padding: 0 !important;
                border-radius: 0 !important;
                border: none !important;
                display: inline !important;
                font-size: 10px !important;
                box-shadow: none !important;
            }
            
            /* إخفاء أعمدة الإجراءات والتأثيرات */
            .custom-table th:nth-child(8),
            .custom-table td:nth-child(8),
            .custom-table th:nth-child(9),
            .custom-table td:nth-child(9) {
                display: none;
            }
            
            /* إضافة عنوان للصفحة المطبوعة */
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 15px;
                font-size: 16px;
                font-weight: bold;
                border-bottom: 2px solid #000;
                padding-bottom: 8px;
            }
            
            .print-info {
                display: block !important;
                text-align: center;
                margin-bottom: 10px;
                font-size: 12px;
            }
            
            /* ضمان ظهور جميع البيانات */
            .custom-table td {
                color: black !important;
                font-weight: normal;
            }
            
            .custom-table td strong {
                font-weight: bold;
            }
            
            /* إصلاح عرض الملاحظات */
            .observation-box {
                max-width: none !important;
                background: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            /* إخفاء أي نص إضافي قد يظهر */
            .header-section *:not(.print-header):not(.print-info) {
                display: none !important;
            }
        }
        
        .print-header, .print-info {
            display: none;
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
        
        .indicator-expired { background-color: var(--expired-color); }
        .indicator-urgent { background-color: var(--warning-color); }
        .indicator-today { background-color: #f1c40f; }
        
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
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
        }
        
        .medical-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }
        
        .medical-modal-content {
            background: white;
            border-radius: 15px;
            padding: 25px;
            width: 90%;
            max-width: 600px;
            box-shadow: var(--hover-shadow);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .warning-message {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: var(--card-shadow);
            border-right: 5px solid var(--expired-color);
        }
        
        @media (max-width: 1200px) {
            .custom-table {
                font-size: 0.85rem;
            }
            
            .custom-table thead th,
            .custom-table tbody td {
                padding: 10px 6px;
            }
            
            .observation-box {
                max-width: 300px;
                font-size: 0.8rem;
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
            
            .observation-box {
                max-width: 200px;
                font-size: 0.75rem;
            }
            
            .warning-message {
                font-size: 1rem;
                padding: 15px;
            }
        }
    </style>
</head>

<body onload="window.scrollTo(0,document.body.scrollHeight / 28)">
    <div class="container-main">
        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="editModalContent">
                    <!-- AJAX content will be loaded here -->
                </div>
            </div>
        </div>

        <div class="header-section">
            <h1 class="header-title">نظام متابعة الشهادات الطبية</h1>
            <div class="header-subtitle no-print">بجميع الوكالات و الورشات</div>
            <!-- سيتم تحديث هذا العنوان تلقائياً عند الطباعة -->
            <div class="print-header" id="printHeader">قائمة الشهادات الطبية</div>
            <div class="print-info" id="printInfo"></div>
           <?php
        include('SessionControl.php');
        require('connection.php');
        ?>
		</div>
		 <?php 
        include('menu.php'); 
              ?>
        
        <!-- Stats Section -->
        <div class="stats-section" id="statsContainer">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="add-certificate-btn" id="open-medical-modal">
                <i class="fas fa-file-medical"></i>
                <span>إضافة شهادة طبية</span>
            </button>
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
                    
                    <button id="printTable" class="btn-modern btn-print">
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
                        <input type="text" id="filterDepar" class="form-control filter-input column-filter" data-column="2" placeholder="البحث بالرتبة...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                        <input type="text" id="filterDateDebut" class="form-control filter-input" data-column="3" placeholder="البحث بتاريخ الشهادة ...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-file-medical"></i></span>
                        <input type="text" id="filterObservation" class="form-control filter-input" data-column="4" placeholder="البحث برقم الشهادة...">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-check"></i></span>
                        <input type="text" id="filterDateFin" class="form-control filter-input" data-column="5" placeholder="البحث بإنتهاء الصلاحية ...">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-building"></i></span>
                        <select id="filterAgence" class="form-control filter-input" data-column="7">
                            <option value="">جميع الوكالات</option>
                            <?php
                            include('DbConnexion.php');
                            $query = "SELECT DISTINCT depar FROM dep ORDER BY depar";
                            $res = mysqli_query($conn, $query);
                            if ($res) {
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $dep = htmlspecialchars($row['depar']);
                                    echo "<option value=\"$dep\">$dep</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-certificate"></i></span>
                        <select id="filterStatus" class="form-control filter-input">
                            <option value="">جميع الحالات</option>
                            <option value="valid">سارية المفعول</option>
                            <option value="expired">منتهية الصلاحية</option>
                            <option value="urgent">عاجلة (1-10 أيام)</option>
                            <option value="today">تنتهي اليوم</option>
                        </select>
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
                            <th>الرقم الآلي</th>
                            <th>الإسم و اللقب</th>
                            <th>الرتبة</th>
                            <th>تاريخ الشهادة</th>
                            <th>رقم الشهادة</th>
                            <th>إنتهاء الصلاحية</th>
                            <th>الملاحظات</th>
                            <th class="no-print">وحدة الإرتباط</th>
                            <?php if ($_SESSION['departement'] == "admin"): ?>
                                <th class="no-print">الإجراءات</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($_SESSION['congidGA'])) {
                               $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
                            include('DbConnexion.php');

                            // Initialize counters for stats
                            $totalCertificates = 0;
                            $expiredCertificates = 0;
                            $urgentCertificates = 0;
                            $todayCertificates = 0;
                            $validCertificates = 0;

                            if ($_SESSION['departement'] !== "admin") {
								$placeholders = implode(',', array_fill(0, count($departements), '?'));
                                $types = str_repeat('i', count($departements));
								
                                $query = "
                                    SELECT 
                                        certificats.mecano, 
                                        stuf.nom, 
                                        datecertificat, 
                                        numcertifcat, 
                                        DateFin, 
                                        Observation, 
                                        certificats.Etat, 
                                        dep.depar, 
                                        titres.libellet, 
                                        DATEDIFF(DateFin, CURRENT_DATE()) AS days_left, 
                                        certificats.id
                                    FROM certificats
                                    LEFT JOIN stuf ON stuf.mecano = certificats.mecano
                                    LEFT JOIN titres ON titres.id = stuf.titre
                                    LEFT JOIN dep ON stuf.dep = dep.id
                                    WHERE stuf.dep IN ($placeholders)
                                    AND contrastage IN (0,1,3)
                                    ORDER BY certificats.Etat desc, days_left asc 
                                ";
                                 $stmt = mysqli_prepare($connection, $query);
                                 mysqli_stmt_bind_param($stmt, $types, ...$departements);
                            } else {
                                $stmt = mysqli_prepare($conn, "
                                    SELECT 
                                        certificats.mecano, 
                                        stuf.nom, 
                                        datecertificat, 
                                        numcertifcat, 
                                        DateFin, 
                                        Observation, 
                                        certificats.Etat, 
                                        dep.depar, 
                                        titres.libellet, 
                                        DATEDIFF(DateFin, CURRENT_DATE()) AS days_left,  
                                        certificats.id
                                    FROM certificats
                                    LEFT JOIN stuf ON stuf.mecano = certificats.mecano
                                    LEFT JOIN titres ON titres.id = stuf.titre
                                    LEFT JOIN dep ON stuf.dep = dep.id
                                    WHERE contrastage IN (0,1,3)
                                    ORDER BY certificats.Etat desc, days_left asc 
                                ");
                            }

                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_result($stmt, $mecano, $nom, $datecertificat, $numcertifcat, $datefin, $observation, $etat, $depar, $titres, $days_diff, $id);

                            while (mysqli_stmt_fetch($stmt)) {
                                $totalCertificates++;
                                
                                // Determine status and count
                                $statusClass = '';
                                $urgencyIndicator = '';
                                $statusType = '';
                                
                                if (($datefin < date('Y-m-d')) && ($etat == 1)) {
                                    $statusClass = 'status-expired';
                                    $urgencyIndicator = 'indicator-expired';
                                    $statusType = 'expired';
                                    $expiredCertificates++;
                                } elseif (($days_diff >= 1) && ($days_diff <= 10) && ($etat == 1)) {
                                    $statusClass = 'status-urgent';
                                    $urgencyIndicator = 'indicator-urgent';
                                    $statusType = 'urgent';
                                    $urgentCertificates++;
                                } elseif (($days_diff == 0) && ($etat == 1)) {
                                    $statusClass = 'status-today';
                                    $urgencyIndicator = 'indicator-today';
                                    $statusType = 'today';
                                    $todayCertificates++;
                                } else {
                                    $statusType = 'valid';
                                    $validCertificates++;
                                }

                                echo '<tr class="' . $statusClass . ' fade-in" data-status="' . $statusType . '">';
                                
                                // Employee ID with indicator
                                echo '<td>';
                                echo '<span class="employee-id">' . htmlspecialchars($mecano) . '</span>';
                                if ($urgencyIndicator) {
                                    echo '<span class="urgency-indicator ' . $urgencyIndicator . '"></span>';
                                }
                                echo '</td>';
                                
                                // Name
                                echo '<td><strong>' . htmlspecialchars($nom) . '</strong></td>';
                                
                                // Title
                                echo '<td><span class="badge badge-custom badge-primary">' . htmlspecialchars($titres) . '</span></td>';
                                
                                // Certificate date
                                echo '<td><span class="date-badge">' . htmlspecialchars($datecertificat) . '</span></td>';
                                
                                // Certificate number
                                echo '<td><span class="certificate-badge">' . htmlspecialchars($numcertifcat) . '</span></td>';
                                
                                // Expiry date
                                echo '<td><span class="date-badge">' . htmlspecialchars($datefin) . '</span></td>';
                                
                                // Observations
                                $cleanObservation = htmlspecialchars($observation);
                                echo '<td>';
                                if (!empty(trim($cleanObservation))) {
                                    echo '<div class="observation-box" title="' . $cleanObservation . '">';
                                    echo (strlen($cleanObservation) > 60 ? substr($cleanObservation, 0, 60) . '...' : $cleanObservation);
                                    echo '</div>';
                                } else {
                                    echo '<span class="text-muted">لا توجد ملاحظات</span>';
                                }
                                echo '</td>';
                                
                                // Department
                                echo '<td class="no-print"><span class="badge badge-custom badge-medical">' . htmlspecialchars($depar) . '</span></td>';

                                // Admin actions
                                if ($_SESSION['departement'] == "admin") {
                                    echo '<td class="no-print">';
                                    echo '<a href="#" class="btn btn-sm btn-outline-primary edit-user-btn" data-mecano="' . htmlspecialchars($id) . '" data-type="4">';
                                    echo '<i class="fas fa-edit"></i>';
                                    echo '</a>';
                                    echo '</td>';
                                }

                                echo '</tr>';
                            }

                            mysqli_stmt_close($stmt);
                            mysqli_close($conn);
                            
                            // Update stats via JavaScript
                            echo '
                            <script>
                                document.getElementById("statsContainer").innerHTML = `
                                    <div class="stat-card stat-total">
                                        <div class="stat-value">' . $totalCertificates . '</div>
                                        <div class="stat-label">إجمالي الشهادات</div>
                                    </div>
                                    <div class="stat-card stat-valid">
                                        <div class="stat-value">' . $validCertificates . '</div>
                                        <div class="stat-label">سارية المفعول</div>
                                    </div>
                                    <div class="stat-card stat-expired">
                                        <div class="stat-value">' . $expiredCertificates . '</div>
                                        <div class="stat-label">منتهية الصلاحية</div>
                                    </div>
                                    <div class="stat-card stat-urgent">
                                        <div class="stat-value">' . $urgentCertificates . '</div>
                                        <div class="stat-label">عاجلة (1-10 أيام)</div>
                                    </div>
                                `;
                            </script>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="warning-message">
            <i class="fas fa-exclamation-triangle me-2"></i>
            يرجى إعادة عرض الأعوان اللذين انتهت صلوحية الشهادات الطبية الصادرة عن طبيب الشغل في شأنهم على أنظار طبيب الشغل
        </div>
    </div>

    <!-- Add Certificate Modal -->
    <div id="medicalModal" class="medical-modal">
        <div class="medical-modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إضافة شهادة طبية جديدة</h5>
                <button type="button" id="close-modal" class="btn-close btn-close-white"></button>
            </div>
            <form action="addmedical.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">الرقم الآلي</label>
                    <select name="statut" id="statut" class="form-control form-control-custom" onchange="getName(this.value)" required>
                        <option value="">اختر الرقم الآلي</option>
                        <?php
                        include('DbConnexion.php');
                        $query = "SELECT mecano FROM stuf WHERE contrastage IN (0,1,3)";
                        $res = mysqli_query($conn, $query);
                        if (!$res) {
                            echo "<option disabled>خطأ في قاعدة البيانات</option>";
                        } else {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $mec = htmlspecialchars($row['mecano']);
                                echo "<option value=\"$mec\">$mec</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">الإسم و اللقب</label>
                    <input type="text" class="form-control form-control-custom" id="nomprenom" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">تاريخ الشهادة الطبية</label>
                    <input type="date" class="form-control form-control-custom" id="datecertif" name="datecertif" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">رقم الشهادة الطبية</label>
                    <input type="text" class="form-control form-control-custom" id="numcertif" name="numcertif" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">تاريخ نهاية الصلاحية</label>
                    <input type="date" class="form-control form-control-custom" id="finvaliditecertif" name="finvaliditecertif" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">الملاحظات</label>
                    <textarea class="form-control form-control-custom" id="observations" name="observations" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" id="close-modal-btn" class="btn btn-secondary">إلغاء</button>
                    <button type="submit" class="btn btn-success">حفظ الشهادة</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        $(document).ready(function() {
            // Edit certificate modal
            $('.edit-user-btn').on('click', function(e) {
                e.preventDefault();
                const mecano = $(this).data('mecano');
                const type = $(this).data('type');

                $('#editModalContent').load('page.php?mecano=' + encodeURIComponent(mecano) + '&type=' + encodeURIComponent(type), function() {
                    $('#editModal').modal('show');
                });
            });
            
            // Add certificate modal
            $('#open-medical-modal').click(function(e) {
                e.preventDefault();
                $('#medicalModal').css('display', 'flex');
            });
            
            $('#close-modal, #close-modal-btn').click(function() {
                $('#medicalModal').hide();
            });
            
            // Close when clicking outside
            $(window).click(function(e) {
                if(e.target == $('#medicalModal')[0]) {
                    $('#medicalModal').hide();
                }
            });
            
            // Print functionality
            $('#printTable').click(function() {
                // تحديث عنوان الطباعة بناءً على الفلاتر المحددة
                updatePrintHeader();
                window.print();
            });
        });

        // تحديث عنوان الطباعة بناءً على الفلاتر
        function updatePrintHeader() {
            const selectedAgence = $('#filterAgence').val();
            const selectedStatus = $('#filterStatus').val();
            const currentDate = new Date().toLocaleDateString('Fr-Fr');
            
            let agenceText = selectedAgence ? ` - ${selectedAgence}` : ' - جميع الوكالات';
            let statusText = '';
            
            switch(selectedStatus) {
                case 'valid':
                    statusText = ' - الشهادات السارية المفعول';
                    break;
                case 'expired':
                    statusText = ' - الشهادات المنتهية الصلاحية';
                    break;
                case 'urgent':
                    statusText = ' - الشهادات العاجلة';
                    break;
                case 'today':
                    statusText = ' - الشهادات التي تنتهي اليوم';
                    break;
                default:
                    statusText = ' - جميع حالات الشهادات';
            }
            
            $('#printHeader').text(`قائمة الشهادات الطبية${agenceText}${statusText}`);
            $('#printInfo').text(`تاريخ الطباعة: ${currentDate}`);
        }

        // AJAX function to get employee name
        function getName(mecano) {
            if (!mecano) {
                $('#nomprenom').val('');
                return;
            }
            $('#nomprenom').val('جارٍ التحميل...');
            $.ajax({
                url: 'getName.php',
                type: 'POST',
                dataType: 'json',
                data: { mecano },
                success: function(data) {
                    $('#nomprenom').val(data.nomprenom || 'غير موجود');
                },
                error: function() {
                    $('#nomprenom').val('خطأ في الإتصال');
                }
            });
        }

        // Enhanced filter table function
        function filterTable() {
            var filters = {};
            $('.filter-input').each(function() {
                if ($(this).val()) {
                    var column = $(this).data('column');
                    if (column !== undefined) {
                        filters[column] = $(this).val().toLowerCase();
                    }
                }
            });
            
            // Special handling for status filter
            var statusFilter = $('#filterStatus').val();
            
            $('#myTable tbody tr').each(function() {
                var showRow = true;
                var cells = $(this).find('td');
                var rowStatus = $(this).data('status');
                
                // Apply column filters
                for (var col in filters) {
                    if (filters.hasOwnProperty(col)) {
                        var cellText = cells.eq(col).text().toLowerCase();
                        if (cellText.indexOf(filters[col]) === -1) {
                            showRow = false;
                            break;
                        }
                    }
                }
                
                // Apply status filter if specified
                if (showRow && statusFilter && rowStatus !== statusFilter) {
                    showRow = false;
                }
                
                $(this).toggle(showRow);
            });
            
            // Update stats after filtering
            updateFilteredStats();
        }
        
        // Update stats based on filtered results
        function updateFilteredStats() {
            var total = 0, valid = 0, expired = 0, urgent = 0;
            
            $('#myTable tbody tr:visible').each(function() {
                total++;
                var status = $(this).data('status');
                if (status === 'valid') valid++;
                if (status === 'expired') expired++;
                if (status === 'urgent') urgent++;
            });
            
            $('#statsContainer').html(`
                <div class="stat-card stat-total">
                    <div class="stat-value">${total}</div>
                    <div class="stat-label">إجمالي الشهادات</div>
                </div>
                <div class="stat-card stat-valid">
                    <div class="stat-value">${valid}</div>
                    <div class="stat-label">سارية المفعول</div>
                </div>
                <div class="stat-card stat-expired">
                    <div class="stat-value">${expired}</div>
                    <div class="stat-label">منتهية الصلاحية</div>
                </div>
                <div class="stat-card stat-urgent">
                    <div class="stat-value">${urgent}</div>
                    <div class="stat-label">عاجلة (1-10 أيام)</div>
                </div>
            `);
        }
        
        $('.filter-input').on('keyup change', filterTable);
        
        $('#clearFilters').on('click', function() {
            $('.filter-input').val('');
            filterTable();
        });

        // Add hover effects and tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('#myTable tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
            
            // Add tooltip for full observation text
            $('.observation-box').each(function() {
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
    </script>
</body>
</html>