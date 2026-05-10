<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير والإحصائيات - نظام إدارة الموارد البشرية</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    
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
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e3e9f7 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a2530 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .sidebar-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: white;
        }
        
        .sidebar-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-right: 3px solid transparent;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-right-color: var(--secondary-color);
        }
        
        .menu-item i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
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
        
        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .filter-group {
            margin-bottom: 15px;
        }
        
        .filter-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-color);
        }
        
        .form-control-custom {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        /* Report Cards */
        .report-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .report-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
            cursor: pointer;
        }
        
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .report-card.success { border-top-color: var(--success-color); }
        .report-card.warning { border-top-color: var(--warning-color); }
        .report-card.info { border-top-color: var(--info-color); }
        .report-card.danger { border-top-color: var(--accent-color); }
        
        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .report-icon.success { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .report-icon.warning { background: rgba(243, 156, 18, 0.1); color: var(--warning-color); }
        .report-icon.info { background: rgba(23, 162, 184, 0.1); color: var(--info-color); }
        .report-icon.danger { background: rgba(231, 76, 60, 0.1); color: var(--accent-color); }
        
        .report-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        
        .report-description {
            color: #6c757d;
            margin-bottom: 15px;
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            position: relative;
            min-height: 400px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--card-shadow);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .btn-modern {
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .btn-export {
            background: var(--success-color);
            color: white;
        }
        
        .btn-print {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-refresh {
            background: var(--warning-color);
            color: white;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
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
        
        /* Responsive */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                padding: 15px;
            }
            
            .menu-item {
                flex-shrink: 0;
                border-right: none;
                border-bottom: 3px solid transparent;
            }
            
            .menu-item:hover, .menu-item.active {
                border-right-color: transparent;
                border-bottom-color: var(--secondary-color);
            }
            
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .report-cards {
                grid-template-columns: 1fr;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Tab Navigation */
        .nav-tabs-custom {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 25px;
        }
        
        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 25px;
            border-radius: 10px 10px 0 0;
            margin-left: 5px;
        }
        
        .nav-tabs-custom .nav-link.active {
            color: var(--secondary-color);
            background: white;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        /* Date Range Picker */
        .date-range-picker {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .date-range-separator {
            color: #6c757d;
            font-weight: 600;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        /* تحسين مظهر بطاقات المغادرين */
        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        /* تحسين مظهر الجدول */
        .employee-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
        }

        .employee-table td {
            text-align: center;
            vertical-align: middle;
        }

        /* Badges for departure types */
        .badge-past {
            background-color: #95a5a6 !important;
        }

        .badge-future {
            background-color: #e67e22 !important;
        }

        .employee-table {
            font-size: 0.9rem;
        }

        .employee-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* Styles supplémentaires pour les nouveaux graphiques */
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            justify-content: center;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
        }
        
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        /* Error message */
        .chart-error {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            color: #6c757d;
            font-style: italic;
        }

        /* أنماط جديدة للتبويبات الفارغة */
        .tab-empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .tab-empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .tab-empty-state h4 {
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .tab-empty-state p {
            max-width: 500px;
            margin: 0 auto 25px;
            line-height: 1.6;
        }
        
        .placeholder-content {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .placeholder-icon {
            font-size: 3rem;
            color: #adb5bd;
            margin-bottom: 15px;
        }
        
        /* تحسينات للتبويبات */
        .tab-pane {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-menu">
                <a href="stat.php" class="menu-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>التقارير والإحصائيات</span>
                </a>
                <a href="statistics.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>إحصائيات السلامة</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header-section">
                <h1 class="header-title">التقارير والإحصائيات</h1>
                <div class="header-subtitle">تحليلات شاملة وبيانات إحصائية عن القوى العاملة</div>
            </div>
            
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4 class="mb-3">فلترة البيانات</h4>
                    </div>
                    <div class="col-md-4 text-left">
                        <button class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-trash-alt"></i> مسح الفلاتر
                        </button>
                    </div>
                </div>
                
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">الفترة الزمنية</label>
                        <select class="form-control form-control-custom" id="timePeriod">
                            <option value="month">هذا الشهر</option>
                            <option value="last_month">الشهر الماضي</option>
                            <option value="quarter">هذا الربع</option>
                            <option value="year">هذا العام</option>
                            <option value="custom">مخصص</option>
                        </select>
                    </div>
                    
                    <div class="filter-group" id="customDateRange" style="display: none;">
                        <label class="filter-label">الفترة المخصصة</label>
                        <div class="date-range-picker">
                            <input type="text" class="form-control form-control-custom datepicker" id="startDate" placeholder="تاريخ البداية">
                            <span class="date-range-separator">إلى</span>
                            <input type="text" class="form-control form-control-custom datepicker" id="endDate" placeholder="تاريخ النهاية">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">نوع الموظفين</label>
                        <select class="form-control form-control-custom" id="employeeType">
                            <option value="all">جميع الموظفين</option>
                            <option value="0">دائمين</option>
                            <option value="1">متدربين</option>
                            <option value="3">ملحقين</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">الإدارة</label>
                        <select class="form-control form-control-custom" id="departmentFilter">
                            <option value="all">جميع الإدارات</option>
                            <!-- سيتم تعبئة هذا من قاعدة البيانات -->
                        </select>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-primary btn-modern" onclick="loadReportsData()">
                        <i class="fas fa-filter"></i>
                        <span>تطبيق الفلاتر</span>
                    </button>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-modern btn-export" onclick="exportReports()">
                    <i class="fas fa-file-excel"></i>
                    <span>تصدير إلى Excel</span>
                </button>
                
                <button class="btn-modern btn-print" onclick="printReports()">
                    <i class="fas fa-print"></i>
                    <span>طباعة التقارير</span>
                </button>
                
                <button class="btn-modern btn-refresh" onclick="loadReportsData()">
                    <i class="fas fa-sync-alt"></i>
                    <span>تحديث البيانات</span>
                </button>
            </div>
            
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs nav-tabs-custom" id="reportsTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">نظرة عامة</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#departures">المغادرين</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#overtime">ساعات إضافية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#leaves">الإجازات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#demographic">ديموغرافيا</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#performance">الأداء</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#financial">مالي</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#health">الصحة المهنية</a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview">
                    <!-- Report Cards -->
                    <div class="report-cards">
                        <div class="report-card success" onclick="generateReport('employees')">
                            <div class="report-icon success">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="report-title">تقرير الموظفين</div>
                            <div class="report-description">بيانات شاملة عن جميع الموظفين وتوزيعهم حسب الإدارات والرتب</div>
                            <div class="text-muted small">إجمالي الموظفين: <span id="totalEmployeesCount">0</span></div>
                        </div>
                        
                        <div class="report-card warning" onclick="generateReport('hiring')">
                            <div class="report-icon warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="report-title">تقرير التعيينات</div>
                            <div class="report-description">تحليل عمليات التعيين والإضافات الجديدة خلال الفترة المحددة</div>
                            <div class="text-muted small">التعيينات هذا الشهر: <span id="monthlyHires">0</span></div>
                        </div>
                        
                        <div class="report-card info" onclick="generateReport('turnover')">
                            <div class="report-icon info">
                                <i class="fas fa-user-minus"></i>
                            </div>
                            <div class="report-title">تقرير الدوران الوظيفي</div>
                            <div class="report-description">معدلات ترك الخدمة والاستقالات ونسب الاحتفظ بالموظفين</div>
                            <div class="text-muted small">معدل الدوران: <span id="turnoverRate">0%</span></div>
                        </div>
                        
                        <div class="report-card danger" onclick="generateReport('attendance')">
                            <div class="report-icon danger">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div class="report-title">تقرير الحضور</div>
                            <div class="report-description">إحصائيات الحضور والانصراف والتأخيرات والغياب</div>
                            <div class="text-muted small">متوسط الحضور: <span id="attendanceRate">0%</span></div>
                        </div>
                    </div>
                    
                    <!-- Charts Section -->
                    <div class="charts-section">
                        <div class="chart-container">
                            <div class="chart-header">
                                <div class="chart-title">توزيع الموظفين حسب الإدارات</div>
                                <div class="chart-actions">
                                    <select class="form-select form-select-sm" onchange="updateDepartmentChart(this.value)">
                                        <option value="count">حسب العدد</option>
                                        <option value="percentage">حسب النسبة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="loading-spinner" id="departmentChartLoading">
                                <div class="spinner"></div>
                            </div>
                            <div class="chart-error" id="departmentChartError" style="display: none;">
                                فشل في تحميل البيانات
                            </div>
                            <canvas id="departmentChart" height="300" style="display: none;"></canvas>
                        </div>
                        
                        <div class="chart-container">
                            <div class="chart-header">
                                <div class="chart-title">تطوّر عدد الأعوان</div>
                            </div>
                            <div class="loading-spinner" id="growthChartLoading">
                                <div class="spinner"></div>
                            </div>
                            <div class="chart-error" id="growthChartError" style="display: none;">
                                فشل في تحميل البيانات
                            </div>
                            <canvas id="growthChart" height="300" style="display: none;"></canvas>
                        </div>
                    </div>
                    
                    <!-- Statistics Grid -->
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value text-primary" id="totalEmployees">0</div>
                            <div class="stat-label">إجماليّ الأعوان</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value text-success" id="permanentEmployees">0</div>
                            <div class="stat-label">مرسّمون</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value text-warning" id="traineeEmployees">0</div>
                            <div class="stat-label">متربصون</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value text-info" id="attachedEmployees">0</div>
                            <div class="stat-label">ملحقون</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value text-danger" id="avgAge">0</div>
                            <div class="stat-label">متوسط العمر</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value text-secondary" id="avgSeniority">0</div>
                            <div class="stat-label">متوسط الأقدمية</div>
                        </div>
                    </div>
                </div>
                
                <!-- Départs Tab -->
                <div class="tab-pane fade" id="departures">
                    <!-- فلترة المغادرين -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">فلترة بيانات المغادرين</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">السنة</label>
                                        <select class="form-control" id="departuresYear">
                                            <option value="">اختر السنة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">مقارنة مع سنة</label>
                                        <select class="form-control" id="departuresCompareYear">
                                            <option value="">بدون مقارنة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الإدارة</label>
                                        <select class="form-control" id="departuresDepartment">
                                            <option value="all">جميع الإدارات</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" onclick="loadDeparturesData()">
                                    <i class="fas fa-filter"></i> تطبيق الفلاتر
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات المغادرين -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-primary" id="totalDepartures">0</div>
                                    <div class="stat-label">إجمالي المغادرين</div>
                                    <i class="fas fa-user-minus fa-2x mt-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-success" id="pastDepartures">0</div>
                                    <div class="stat-label">مغادرين سابقين</div>
                                    <i class="fas fa-history fa-2x mt-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-warning" id="futureDepartures">0</div>
                                    <div class="stat-label">مغادرين مستقبليين</div>
                                    <i class="fas fa-calendar-plus fa-2x mt-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-info" id="departuresChange">0%</div>
                                    <div class="stat-label">نسبة التغير</div>
                                    <i class="fas fa-chart-line fa-2x mt-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مخططات المغادرين -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <ul class="nav nav-pills card-header-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="pill" href="#departuresYearChartTab">حسب السنوات</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#departuresDepartmentChartTab">حسب الإدارات</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#departuresComparisonTab">المقارنة السنوية</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#departuresEmployeesTab">قائمة الموظفين</a>
                                </li>
                            </ul>
                            <button class="btn btn-sm btn-success" onclick="exportDeparturesReport()">
                                <i class="fas fa-file-excel me-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- مخطط السنوات -->
                                <div class="tab-pane fade show active" id="departuresYearChartTab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center mb-3">توزيع المغادرين حسب السنوات</h5>
                                            <div class="chart-container">
                                                <canvas id="departuresByYearChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- مخطط الإدارات -->
                                <div class="tab-pane fade" id="departuresDepartmentChartTab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">توزيع المغادرين حسب الإدارات</h5>
                                            <div class="chart-container">
                                                <canvas id="departuresByDepartmentChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">المغادرين حسب الرتب</h5>
                                            <div class="chart-container">
                                                <canvas id="departuresByGradeChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- مخطط المقارنة -->
                                <div class="tab-pane fade" id="departuresComparisonTab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center mb-3">مقارنة السنوات</h5>
                                            <div class="chart-container">
                                                <canvas id="departuresComparisonChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- قائمة الموظفين -->
                                <div class="tab-pane fade" id="departuresEmployeesTab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center mb-3">قائمة الموظفين المغادرين والمستقبليين</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover employee-table" id="departuresEmployeesTable">
                                                    <thead>
                                                        <tr>
                                                            <th>الرقم</th>
                                                            <th>الاسم</th>
                                                            <th>اللقب</th>
                                                            <th>تاريخ الميلاد</th>
                                                            <th>تاريخ المغادرة</th>
                                                            <th>نوع المغادرة</th>
                                                            <th>الإدارة</th>
                                                            <th>الرتبة</th>
                                                            <th>العمر</th>
                                                            <th>الحالة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- سيتم تعبئته بالبيانات -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Overtime Tab -->
                <div class="tab-pane fade" id="overtime">
                    <!-- فلترة ساعات العمل الإضافية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">فلترة بيانات ساعات العمل الإضافية</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">السنة</label>
                                        <select class="form-control" id="overtimeYear">
                                            <option value="">اختر السنة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الشهر</label>
                                        <select class="form-control" id="overtimeMonth">
                                            <option value="all">جميع الأشهر</option>
                                            <option value="1">يناير</option>
                                            <option value="2">فبراير</option>
                                            <option value="3">مارس</option>
                                            <option value="4">أبريل</option>
                                            <option value="5">مايو</option>
                                            <option value="6">يونيو</option>
                                            <option value="7">يوليو</option>
                                            <option value="8">أغسطس</option>
                                            <option value="9">سبتمبر</option>
                                            <option value="10">أكتوبر</option>
                                            <option value="11">نوفمبر</option>
                                            <option value="12">ديسمبر</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الإدارة</label>
                                        <select class="form-control" id="overtimeDepartment">
                                            <option value="all">جميع الإدارات</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" onclick="loadOvertimeData()">
                                    <i class="fas fa-filter"></i> تطبيق الفلاتر
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات ساعات العمل الإضافية -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-primary" id="totalOvertimeHours">0</div>
                                    <div class="stat-label">إجمالي الساعات الإضافية</div>
                                    <i class="fas fa-clock fa-2x mt-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-success" id="totalOvertimeAmount">0</div>
                                    <div class="stat-label">إجمالي قيمة الساعات الإضافية</div>
                                    <i class="fas fa-money-bill-wave fa-2x mt-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-warning" id="avgOvertimePerEmployee">0</div>
                                    <div class="stat-label">متوسط الساعات للموظف</div>
                                    <i class="fas fa-user-clock fa-2x mt-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-info" id="overtimeEmployeesCount">0</div>
                                    <div class="stat-label">عدد الموظفين</div>
                                    <i class="fas fa-users fa-2x mt-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مخططات ساعات العمل الإضافية -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <ul class="nav nav-pills card-header-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="pill" href="#overtimeTypeChartTab">حسب النوع</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#overtimeDepartmentChartTab">حسب الإدارات</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#overtimeGradeChartTab">حسب الرتب</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#overtimeMonthlyChartTab">حسب الأشهر</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#overtimeEmployeesTab">قائمة الموظفين</a>
                                </li>
                            </ul>
                            <button class="btn btn-sm btn-success" onclick="exportOvertimeReport()">
                                <i class="fas fa-file-excel me-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- مخطط حسب النوع -->
                                <div class="tab-pane fade show active" id="overtimeTypeChartTab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">توزيع الساعات حسب النوع</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeByTypeChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">توزيع القيمة حسب النوع</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeAmountByTypeChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- مخطط حسب الإدارات -->
                                <div class="tab-pane fade" id="overtimeDepartmentChartTab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">الساعات حسب الإدارات</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeByDepartmentChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">القيمة حسب الإدارات</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeAmountByDepartmentChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- مخطط حسب الرتب -->
                                <div class="tab-pane fade" id="overtimeGradeChartTab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">الساعات حسب الرتب</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeByGradeChart"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-center mb-3">القيمة حسب الرتب</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeAmountByGradeChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- مخطط حسب الأشهر -->
                                <div class="tab-pane fade" id="overtimeMonthlyChartTab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center mb-3">تطور الساعات الإضافية خلال السنة</h5>
                                            <div class="chart-container">
                                                <canvas id="overtimeByMonthChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- قائمة الموظفين -->
                                <div class="tab-pane fade" id="overtimeEmployeesTab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="text-center mb-3">قائمة الموظفين مع ساعات العمل الإضافية</h5>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover employee-table" id="overtimeEmployeesTable">
                                                    <thead>
                                                        <tr>
                                                            <th>الرقم</th>
                                                            <th>الاسم</th>
                                                            <th>اللقب</th>
                                                            <th>الإدارة</th>
                                                            <th>الرتبة</th>
                                                            <th>HS125 (عدد)</th>
                                                            <th>HS125 (قيمة)</th>
                                                            <th>HS150 (عدد)</th>
                                                            <th>HS150 (قيمة)</th>
                                                            <th>HS175 (عدد)</th>
                                                            <th>HS175 (قيمة)</th>
                                                            <th>إجمالي الساعات</th>
                                                            <th>إجمالي القيمة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- سيتم تعبئته بالبيانات -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Leaves Tab -->
                <div class="tab-pane fade" id="leaves">
                    <!-- فلترة الإجازات -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">فلترة بيانات الإجازات</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">السنة</label>
                                        <select class="form-control" id="leavesYear">
                                            <option value="">اختر السنة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">مقارنة مع سنة</label>
                                        <select class="form-control" id="leavesCompareYear">
                                            <option value="">بدون مقارنة</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الإدارة</label>
                                        <select class="form-control" id="leavesDepartment">
                                            <option value="all">جميع الإدارات</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" onclick="loadLeavesData()">
                                    <i class="fas fa-filter"></i> تطبيق الفلاتر
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الإجازات -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-primary" id="totalLeaves">0</div>
                                    <div class="stat-label">إجمالي الإجازات</div>
                                    <i class="fas fa-calendar-alt fa-2x mt-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-success" id="totalLeavesEmployees">0</div>
                                    <div class="stat-label">عدد الموظفين</div>
                                    <i class="fas fa-users fa-2x mt-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-warning" id="totalLeavesDays">0</div>
                                    <div class="stat-label">إجمالي أيام الإجازة</div>
                                    <i class="fas fa-calendar-day fa-2x mt-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-info" id="avgLeavesDays">0</div>
                                    <div class="stat-label">متوسط الأيام للموظف</div>
                                    <i class="fas fa-user-clock fa-2x mt-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مخططات الإجازات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">الإجازات حسب الأشهر</div>
                                </div>
                                <div class="loading-spinner" id="leavesByMonthChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="leavesByMonthChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="leavesByMonthChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">أيام الإجازة حسب الأشهر</div>
                                </div>
                                <div class="loading-spinner" id="leavesDaysByMonthChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="leavesDaysByMonthChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="leavesDaysByMonthChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">الإجازات حسب الإدارات</div>
                                </div>
                                <div class="loading-spinner" id="leavesByDepartmentChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="leavesByDepartmentChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="leavesByDepartmentChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">أيام الإجازة حسب الإدارات</div>
                                </div>
                                <div class="loading-spinner" id="leavesDaysByDepartmentChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="leavesDaysByDepartmentChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="leavesDaysByDepartmentChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- جدول الإجازات الشهري -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">الإجازات الشهرية</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="monthlyLeavesTable">
                                    <thead>
                                        <tr>
                                            <th>الشهر</th>
                                            <th>عدد الإجازات</th>
                                            <th>إجمالي الأيام</th>
                                            <th>متوسط الأيام</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- سيتم تعبئته بالبيانات -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تبويب الديموغرافيا -->
                <div class="tab-pane fade" id="demographic">
                    <!-- فلترة الديموغرافيا -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">فلترة بيانات الديموغرافيا</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">نوع الموظفين</label>
                                        <select class="form-control" id="demographicEmployeeType">
                                            <option value="all">جميع الموظفين</option>
                                            <option value="0">دائمين</option>
                                            <option value="1">متدربين</option>
                                            <option value="3">ملحقين</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الإدارة</label>
                                        <select class="form-control" id="demographicDepartment">
                                            <option value="all">جميع الإدارات</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">الرتبة</label>
                                        <select class="form-control" id="demographicGrade">
                                            <option value="all">جميع الرتب</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" onclick="loadDemographicData()">
                                    <i class="fas fa-filter"></i> تطبيق الفلاتر
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات الديموغرافيا -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-primary" id="demographicTotal">0</div>
                                    <div class="stat-label">إجمالي الموظفين</div>
                                    <i class="fas fa-users fa-2x mt-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-success" id="demographicMale">0</div>
                                    <div class="stat-label">ذكور</div>
                                    <i class="fas fa-male fa-2x mt-2 text-success"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-warning" id="demographicFemale">0</div>
                                    <div class="stat-label">إناث</div>
                                    <i class="fas fa-female fa-2x mt-2 text-warning"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card h-100">
                                <div class="card-body text-center">
                                    <div class="stat-value text-info" id="demographicAvgAge">0</div>
                                    <div class="stat-label">متوسط العمر</div>
                                    <i class="fas fa-birthday-cake fa-2x mt-2 text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مخططات الديموغرافيا -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">التوزيع حسب الجنس</div>
                                </div>
                                <div class="loading-spinner" id="genderChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="genderChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="genderChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">التوزيع حسب الفئة العمرية</div>
                                </div>
                                <div class="loading-spinner" id="ageChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="ageChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="ageChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">التوزيع حسب الحالة الاجتماعية</div>
                                </div>
                                <div class="loading-spinner" id="maritalChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="maritalChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="maritalChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <div class="chart-header">
                                    <div class="chart-title">التوزيع حسب المؤهل العلمي</div>
                                </div>
                                <div class="loading-spinner" id="educationChartLoading">
                                    <div class="spinner"></div>
                                </div>
                                <div class="chart-error" id="educationChartError" style="display: none;">
                                    فشل في تحميل البيانات
                                </div>
                                <canvas id="educationChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- جدول التوزيع الجغرافي -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">التوزيع الجغرافي</h5>
                            <button class="btn btn-sm btn-success" onclick="exportDemographicReport()">
                                <i class="fas fa-file-excel me-1"></i> تصدير إلى Excel
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <div class="chart-header">
                                            <div class="chart-title">التوزيع حسب الولايات</div>
                                        </div>
                                        <div class="loading-spinner" id="regionChartLoading">
                                            <div class="spinner"></div>
                                        </div>
                                        <div class="chart-error" id="regionChartError" style="display: none;">
                                            فشل في تحميل البيانات
                                        </div>
                                        <canvas id="regionChart" height="300" style="display: none;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <div class="chart-header">
                                            <div class="chart-title">التوزيع حسب نوع السكن</div>
                                        </div>
                                        <div class="loading-spinner" id="housingChartLoading">
                                            <div class="spinner"></div>
                                        </div>
                                        <div class="chart-error" id="housingChartError" style="display: none;">
                                            فشل في تحميل البيانات
                                        </div>
                                        <canvas id="housingChart" height="300" style="display: none;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول تحليل الأقدمية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">تحليل الأقدمية</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <div class="chart-header">
                                            <div class="chart-title">التوزيع حسب سنوات الخبرة</div>
                                        </div>
                                        <div class="loading-spinner" id="seniorityChartLoading">
                                            <div class="spinner"></div>
                                        </div>
                                        <div class="chart-error" id="seniorityChartError" style="display: none;">
                                            فشل في تحميل البيانات
                                        </div>
                                        <canvas id="seniorityChart" height="300" style="display: none;"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="chart-container">
                                        <div class="chart-header">
                                            <div class="chart-title">التوزيع حسب سنوات الخدمة في المؤسسة</div>
                                        </div>
                                        <div class="loading-spinner" id="serviceYearsChartLoading">
                                            <div class="spinner"></div>
                                        </div>
                                        <div class="chart-error" id="serviceYearsChartError" style="display: none;">
                                            فشل في تحميل البيانات
                                        </div>
                                        <canvas id="serviceYearsChart" height="300" style="display: none;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- تبويبات أخرى -->
                <div class="tab-pane fade" id="performance">
                    <div class="tab-empty-state">
                        <i class="fas fa-chart-bar"></i>
                        <h4>الأداء</h4>
                        <p>هذا القسم مخصص لعرض تقارير ومؤشرات أداء الموظفين. سيتم تفعيله قريباً.</p>
                        <button class="btn btn-primary" disabled>
                            <i class="fas fa-cog"></i> قيد التطوير
                        </button>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="financial">
                    <div class="tab-empty-state">
                        <i class="fas fa-money-bill-wave"></i>
                        <h4>التقارير المالية</h4>
                        <p>هذا القسم مخصص لعرض البيانات والتقارير المالية المتعلقة بالموارد البشرية. سيتم تفعيله قريباً.</p>
                        <button class="btn btn-primary" disabled>
                            <i class="fas fa-cog"></i> قيد التطوير
                        </button>
                    </div>
                </div>
                
                <div class="tab-pane fade" id="health">
                    <div class="tab-empty-state">
                        <i class="fas fa-heartbeat"></i>
                        <h4>الصحة المهنية</h4>
                        <p>هذا القسم مخصص لعرض تقارير الصحة المهنية والحوادث. سيتم تفعيله قريباً.</p>
                        <button class="btn btn-primary" disabled>
                            <i class="fas fa-cog"></i> قيد التطوير
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        // المتغيرات العامة
        let departuresChartInstances = {};
        let overtimeChartInstances = {};
        let leavesChartInstances = {};
        let demographicChartInstances = {};

        // تهيئة الصفحة
        $(document).ready(function() {
            // تهيئة منتقي التاريخ
            $('.datepicker').datepicker({
                language: 'ar',
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            
            // التحكم في عرض الفترة المخصصة
            $('#timePeriod').change(function() {
                if ($(this).val() === 'custom') {
                    $('#customDateRange').show();
                } else {
                    $('#customDateRange').hide();
                }
            });
            
            // تحميل البيانات الأولية
            loadDepartments();
            loadReportsData();
            loadDeparturesAvailableYears();
            loadOvertimeAvailableYears();
            loadLeavesAvailableYears();
            loadDemographicData();
            
            // إضافة مستمعي الأحداث للفلاتر
            $('#departuresYear, #departuresCompareYear, #departuresDepartment').change(function() {
                loadDeparturesData();
            });
            
            $('#overtimeYear, #overtimeMonth, #overtimeDepartment').change(function() {
                loadOvertimeData();
            });
            
            $('#leavesYear, #leavesCompareYear, #leavesDepartment').change(function() {
                loadLeavesData();
            });
            
            $('#demographicEmployeeType, #demographicDepartment, #demographicGrade').change(function() {
                loadDemographicData();
            });
        });
        
        // ========== دوال الديموغرافيا ==========
        
        // دالة لتحميل بيانات الديموغرافيا
        function loadDemographicData() {
            const employeeType = $('#demographicEmployeeType').val();
            const department = $('#demographicDepartment').val();
            const grade = $('#demographicGrade').val();
            
            const filters = {
                employeeType: employeeType || 'all',
                department: department || 'all',
                grade: grade || 'all'
            };
            
            console.log('Loading demographic data with filters:', filters);
            loadDemographicCharts(filters);
        }

        // دالة لتحميل جميع مخططات الديموغرافيا
        function loadDemographicCharts(filters) {
            loadDemographicSummary(filters);
            loadGenderChart(filters);
            loadAgeChart(filters);
            loadMaritalChart(filters);
            loadEducationChart(filters);
            loadRegionChart(filters);
            loadHousingChart(filters);
            loadSeniorityChart(filters);
            loadServiceYearsChart(filters);
        }

        // دالة لتحميل إحصائيات الديموغرافيا
        function loadDemographicSummary(filters) {
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, action: 'summary'},
                dataType: 'json',
                success: function(data) {
                    console.log('Demographic summary data received:', data);
                    
                    if (data.success) {
                        const stats = data.stats;
                        
                        // تحديث الإحصائيات الأساسية
                        $('#demographicTotal').text(stats.totalEmployees || 0);
                        $('#demographicMale').text(stats.maleCount || 0);
                        $('#demographicFemale').text(stats.femaleCount || 0);
                        $('#demographicAvgAge').text((stats.avgAge || 0).toFixed(1) + ' سنة');
                    } else {
                        console.error('Error in demographic data:', data.message);
                        setDefaultDemographicValues();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading demographic summary:', error);
                    setDefaultDemographicValues();
                }
            });
        }

        // دالة لتعيين القيم الافتراضية للديموغرافيا
        function setDefaultDemographicValues() {
            $('#demographicTotal').text('0');
            $('#demographicMale').text('0');
            $('#demographicFemale').text('0');
            $('#demographicAvgAge').text('0');
        }

        // دالة لتحميل مخطط التوزيع حسب الجنس
        function loadGenderChart(filters) {
            $('#genderChartLoading').show();
            $('#genderChart').hide();
            $('#genderChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'gender'},
                dataType: 'json',
                success: function(data) {
                    $('#genderChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#genderChart').show();
                        
                        const ctx = document.getElementById('genderChart').getContext('2d');
                        
                        if (demographicChartInstances.gender) {
                            demographicChartInstances.gender.destroy();
                        }
                        
                        demographicChartInstances.gender = new Chart(ctx, {
                            type: 'pie',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#genderChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading gender chart:', error);
                    $('#genderChartLoading').hide();
                    $('#genderChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب الفئة العمرية
        function loadAgeChart(filters) {
            $('#ageChartLoading').show();
            $('#ageChart').hide();
            $('#ageChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'age'},
                dataType: 'json',
                success: function(data) {
                    $('#ageChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#ageChart').show();
                        
                        const ctx = document.getElementById('ageChart').getContext('2d');
                        
                        if (demographicChartInstances.age) {
                            demographicChartInstances.age.destroy();
                        }
                        
                        demographicChartInstances.age = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الموظفين'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'الفئة العمرية'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#ageChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading age chart:', error);
                    $('#ageChartLoading').hide();
                    $('#ageChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب الحالة الاجتماعية
        function loadMaritalChart(filters) {
            $('#maritalChartLoading').show();
            $('#maritalChart').hide();
            $('#maritalChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'marital'},
                dataType: 'json',
                success: function(data) {
                    $('#maritalChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#maritalChart').show();
                        
                        const ctx = document.getElementById('maritalChart').getContext('2d');
                        
                        if (demographicChartInstances.marital) {
                            demographicChartInstances.marital.destroy();
                        }
                        
                        demographicChartInstances.marital = new Chart(ctx, {
                            type: 'doughnut',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#maritalChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading marital chart:', error);
                    $('#maritalChartLoading').hide();
                    $('#maritalChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب المؤهل العلمي
        function loadEducationChart(filters) {
            $('#educationChartLoading').show();
            $('#educationChart').hide();
            $('#educationChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'education'},
                dataType: 'json',
                success: function(data) {
                    $('#educationChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#educationChart').show();
                        
                        const ctx = document.getElementById('educationChart').getContext('2d');
                        
                        if (demographicChartInstances.education) {
                            demographicChartInstances.education.destroy();
                        }
                        
                        demographicChartInstances.education = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الموظفين'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#educationChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading education chart:', error);
                    $('#educationChartLoading').hide();
                    $('#educationChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب الولايات
        function loadRegionChart(filters) {
            $('#regionChartLoading').show();
            $('#regionChart').hide();
            $('#regionChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'region'},
                dataType: 'json',
                success: function(data) {
                    $('#regionChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#regionChart').show();
                        
                        const ctx = document.getElementById('regionChart').getContext('2d');
                        
                        if (demographicChartInstances.region) {
                            demographicChartInstances.region.destroy();
                        }
                        
                        demographicChartInstances.region = new Chart(ctx, {
                            type: 'pie',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#regionChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading region chart:', error);
                    $('#regionChartLoading').hide();
                    $('#regionChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب نوع السكن
        function loadHousingChart(filters) {
            $('#housingChartLoading').show();
            $('#housingChart').hide();
            $('#housingChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'housing'},
                dataType: 'json',
                success: function(data) {
                    $('#housingChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#housingChart').show();
                        
                        const ctx = document.getElementById('housingChart').getContext('2d');
                        
                        if (demographicChartInstances.housing) {
                            demographicChartInstances.housing.destroy();
                        }
                        
                        demographicChartInstances.housing = new Chart(ctx, {
                            type: 'doughnut',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#housingChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading housing chart:', error);
                    $('#housingChartLoading').hide();
                    $('#housingChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب سنوات الخبرة
        function loadSeniorityChart(filters) {
            $('#seniorityChartLoading').show();
            $('#seniorityChart').hide();
            $('#seniorityChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'seniority'},
                dataType: 'json',
                success: function(data) {
                    $('#seniorityChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#seniorityChart').show();
                        
                        const ctx = document.getElementById('seniorityChart').getContext('2d');
                        
                        if (demographicChartInstances.seniority) {
                            demographicChartInstances.seniority.destroy();
                        }
                        
                        demographicChartInstances.seniority = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الموظفين'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'سنوات الخبرة'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#seniorityChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading seniority chart:', error);
                    $('#seniorityChartLoading').hide();
                    $('#seniorityChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط التوزيع حسب سنوات الخدمة
        function loadServiceYearsChart(filters) {
            $('#serviceYearsChartLoading').show();
            $('#serviceYearsChart').hide();
            $('#serviceYearsChartError').hide();
            
            $.ajax({
                url: 'get_demographic_stats.php',
                type: 'POST',
                data: {...filters, chart: 'service_years'},
                dataType: 'json',
                success: function(data) {
                    $('#serviceYearsChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#serviceYearsChart').show();
                        
                        const ctx = document.getElementById('serviceYearsChart').getContext('2d');
                        
                        if (demographicChartInstances.serviceYears) {
                            demographicChartInstances.serviceYears.destroy();
                        }
                        
                        demographicChartInstances.serviceYears = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الموظفين'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'سنوات الخدمة'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#serviceYearsChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading service years chart:', error);
                    $('#serviceYearsChartLoading').hide();
                    $('#serviceYearsChartError').show();
                }
            });
        }

        // دالة لتصدير تقرير الديموغرافيا
        function exportDemographicReport() {
            alert('سيتم تصدير تقرير الديموغرافيا إلى Excel');
        }

        // ========== دوال المغادرين ==========
        
        // دالة لتحميل السنوات المتاحة للمغادرين
        function loadDeparturesAvailableYears() {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: { action: 'get_years' },
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.availableYears) {
                        const years = data.stats.availableYears;
                        const currentYearSelect = $('#departuresYear');
                        const compareYearSelect = $('#departuresCompareYear');
                        
                        // تفريغ الخيارات الحالية
                        currentYearSelect.empty();
                        compareYearSelect.empty();
                        compareYearSelect.append('<option value="">بدون مقارنة</option>');
                        
                        // إضافة السنوات المتاحة
                        years.forEach(year => {
                            currentYearSelect.append(new Option(year, year));
                            compareYearSelect.append(new Option(year, year));
                        });
                        
                        // تعيين السنة الحالية كافتراضي
                        const currentYear = new Date().getFullYear();
                        if (years.includes(currentYear)) {
                            currentYearSelect.val(currentYear);
                        } else if (years.length > 0) {
                            currentYearSelect.val(years[0]);
                        }
                        
                        // تحميل بيانات المغادرين بعد تحميل السنوات
                        setTimeout(() => {
                            loadDeparturesData();
                        }, 100);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures available years:', error);
                    loadDeparturesDefaultYears();
                }
            });
        }

        // دالة لتحميل السنوات الافتراضية للمغادرين
        function loadDeparturesDefaultYears() {
            const currentYear = new Date().getFullYear();
            const currentYearSelect = $('#departuresYear');
            const compareYearSelect = $('#departuresCompareYear');
            
            currentYearSelect.empty();
            compareYearSelect.empty();
            compareYearSelect.append('<option value="">بدون مقارنة</option>');
            
            // إضافة السنوات الخمس الأخيرة
            for (let year = currentYear; year >= currentYear - 5; year--) {
                currentYearSelect.append(new Option(year, year));
                compareYearSelect.append(new Option(year, year));
            }
            
            currentYearSelect.val(currentYear);
            
            // تحميل بيانات المغادرين
            setTimeout(() => {
                loadDeparturesData();
            }, 100);
        }

        // دالة لتحميل بيانات المغادرين
        function loadDeparturesData() {
            const year = $('#departuresYear').val();
            const compareYear = $('#departuresCompareYear').val();
            const department = $('#departuresDepartment').val();
            
            // التحقق من وجود سنة محددة
            if (!year) {
                alert('يرجى اختيار سنة');
                return;
            }
            
            const filters = {
                year: year,
                compareYear: compareYear || '',
                department: department || 'all'
            };
            
            console.log('Loading departures data with filters:', filters);
            loadDeparturesCharts(filters);
        }

        // دالة لتحميل مخططات المغادرين
        function loadDeparturesCharts(filters) {
            console.log('Loading departures charts with filters:', filters);
            
            // تحميل البيانات الأساسية أولاً
            loadDeparturesSummary(filters);
            
            // ثم تحميل المخططات الأخرى
            setTimeout(() => {
                loadDeparturesByYearChart(filters);
                loadDeparturesByDepartmentChart(filters);
                loadDeparturesByGradeChart(filters);
                loadDeparturesComparison(filters);
                loadDeparturesEmployeesTable(filters);
            }, 500);
        }

        // دالة لتحميل إحصائيات المغادرين
        function loadDeparturesSummary(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    console.log('Departures data received:', data);
                    
                    if (data.success) {
                        // تحديث الإحصائيات الأساسية
                        $('#totalDepartures').text(data.stats.totalRetirements || 0);
                        
                        // حساب المغادرين السابقين والمستقبليين
                        let pastDepartures = 0;
                        let futureDepartures = 0;
                        
                        if (data.stats.byYear) {
                            data.stats.byYear.forEach(item => {
                                if (item.type === 'past') {
                                    pastDepartures += item.count;
                                } else if (item.type === 'future') {
                                    futureDepartures += item.count;
                                }
                            });
                        }
                        
                        $('#pastDepartures').text(pastDepartures);
                        $('#futureDepartures').text(futureDepartures);
                        
                        // تحديث نسبة التغير إذا كانت المقارنة مفعلة
                        if (data.stats.yearComparison && data.stats.yearComparison.change_percent !== undefined) {
                            const changePercent = data.stats.yearComparison.change_percent;
                            $('#departuresChange').text(changePercent + '%');
                            
                            // تغيير اللون حسب القيمة
                            if (changePercent > 0) {
                                $('#departuresChange').removeClass('text-danger').addClass('text-success');
                            } else if (changePercent < 0) {
                                $('#departuresChange').removeClass('text-success').addClass('text-danger');
                            }
                        } else {
                            $('#departuresChange').text('0%');
                        }
                    } else {
                        console.error('Error in departures data:', data.message);
                        setDefaultDeparturesValues();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures summary:', error);
                    console.log('Response:', xhr.responseText);
                    
                    // تعيين القيم الافتراضية في حالة الخطأ
                    setDefaultDeparturesValues();
                }
            });
        }

        // دالة لتعيين القيم الافتراضية للمغادرين
        function setDefaultDeparturesValues() {
            $('#totalDepartures').text('0');
            $('#pastDepartures').text('0');
            $('#futureDepartures').text('0');
            $('#departuresChange').text('0%');
        }

        // دالة لتحميل مخطط المغادرين حسب السنة
        function loadDeparturesByYearChart(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const byYear = data.stats.byYear || [];
                        
                        // فصل البيانات حسب النوع للتلوين
                        const years = byYear.map(item => item.year);
                        const counts = byYear.map(item => item.count);
                        const backgroundColors = byYear.map(item => 
                            item.type === 'past' ? 'rgba(46, 204, 113, 0.7)' : 'rgba(230, 126, 34, 0.7)'
                        );
                        const borderColors = byYear.map(item => 
                            item.type === 'past' ? 'rgba(46, 204, 113, 1)' : 'rgba(230, 126, 34, 1)'
                        );
                        
                        const ctx = document.getElementById('departuresByYearChart').getContext('2d');
                        
                        if (departuresChartInstances.byYear) {
                            departuresChartInstances.byYear.destroy();
                        }
                        
                        departuresChartInstances.byYear = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'عدد المغادرين',
                                    data: counts,
                                    backgroundColor: backgroundColors,
                                    borderColor: borderColors,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const index = context.dataIndex;
                                                const type = byYear[index].type === 'past' ? 'سابق' : 'مستقبلي';
                                                return `عدد المغادرين: ${context.parsed.y} (${type})`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد المغادرين'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'السنة'
                                        }
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures by year chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط المغادرين حسب الإدارة
        function loadDeparturesByDepartmentChart(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const byDepartment = data.stats.byDepartment || [];
                        
                        const departments = byDepartment.map(item => item.department);
                        const counts = byDepartment.map(item => item.count);
                        
                        const ctx = document.getElementById('departuresByDepartmentChart').getContext('2d');
                        
                        if (departuresChartInstances.byDepartment) {
                            departuresChartInstances.byDepartment.destroy();
                        }
                        
                        departuresChartInstances.byDepartment = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: departments,
                                datasets: [{
                                    data: counts,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)',
                                        'rgba(241, 196, 15, 0.7)',
                                        'rgba(230, 126, 34, 0.7)',
                                        'rgba(231, 76, 60, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures by department chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط المغادرين حسب الرتب
        function loadDeparturesByGradeChart(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const byGrade = data.stats.byGrade || [];
                        
                        const grades = byGrade.map(item => item.grade);
                        const counts = byGrade.map(item => item.count);
                        
                        const ctx = document.getElementById('departuresByGradeChart').getContext('2d');
                        
                        if (departuresChartInstances.byGrade) {
                            departuresChartInstances.byGrade.destroy();
                        }
                        
                        departuresChartInstances.byGrade = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: grades,
                                datasets: [{
                                    data: counts,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)',
                                        'rgba(241, 196, 15, 0.7)',
                                        'rgba(230, 126, 34, 0.7)',
                                        'rgba(231, 76, 60, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures by grade chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط المقارنة
        function loadDeparturesComparison(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const comparison = data.stats.yearComparison;
                        
                        if (comparison.current && comparison.compare) {
                            const currentData = comparison.current;
                            const compareData = comparison.compare;
                            
                            const ctx = document.getElementById('departuresComparisonChart').getContext('2d');
                            
                            if (departuresChartInstances.comparison) {
                                departuresChartInstances.comparison.destroy();
                            }
                            
                            departuresChartInstances.comparison = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['عدد المغادرين'],
                                    datasets: [
                                        {
                                            label: `السنة ${currentData.year}`,
                                            data: [currentData.total_retirements || 0],
                                            backgroundColor: 'rgba(52, 152, 219, 0.7)'
                                        },
                                        {
                                            label: `السنة ${compareData.year}`,
                                            data: [compareData.total_retirements || 0],
                                            backgroundColor: 'rgba(231, 76, 60, 0.7)'
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            position: 'bottom'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures comparison chart:', error);
                }
            });
        }

        // دالة مساعدة لتنسيق التاريخ
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-TN');
        }

        // دالة لتحميل جدول الموظفين المغادرين
        function loadDeparturesEmployeesTable(filters) {
            $.ajax({
                url: 'get_retirement_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const employeesList = data.stats.employeesList || [];
                        
                        let tableBody = '';
                        
                        if (employeesList.length === 0) {
                            tableBody = '<tr><td colspan="10" class="text-center">لا توجد بيانات</td></tr>';
                        } else {
                            employeesList.forEach((employee, index) => {
                                const status = employee.type === 'past' ? 
                                    '<span class="badge bg-success">سابق</span>' : 
                                    '<span class="badge bg-warning">مستقبلي</span>';
                                
                                const age = employee.type === 'past' ? 
                                    (employee.age_retirement || 'غير محدد') :
                                    (employee.current_age || 'غير محدد');
                                
                                // تنسيق التواريخ
                                const birthDate = employee.daten ? formatDate(employee.daten) : '';
                                const departureDate = employee.datedepart ? formatDate(employee.datedepart) : '';
                                
                                tableBody += `
                                    <tr>
                                        <td>${employee.mecano || ''}</td>
                                        <td>${employee.nom || ''}</td>
                                        <td>${employee.prenom || ''}</td>
                                        <td>${birthDate}</td>
                                        <td>${departureDate}</td>
                                        <td>${employee.observation || ''}</td>
                                        <td>${employee.departement || ''}</td>
                                        <td>${employee.grade || ''}</td>
                                        <td>${age}</td>
                                        <td>${status}</td>
                                    </tr>
                                `;
                            });
                        }
                        
                        $('#departuresEmployeesTable tbody').html(tableBody);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departures employees table:', error);
                    $('#departuresEmployeesTable tbody').html('<tr><td colspan="10" class="text-center text-danger">حدث خطأ في تحميل البيانات</td></tr>');
                }
            });
        }

        // دالة لتصدير تقرير المغادرين
        function exportDeparturesReport() {
            alert('سيتم تصدير تقرير المغادرين إلى Excel');
        }

        // ========== دوال ساعات العمل الإضافية ==========
        
        // دالة لتحميل السنوات المتاحة لساعات العمل الإضافية
        function loadOvertimeAvailableYears() {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: { action: 'get_years' },
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.availableYears) {
                        const years = data.stats.availableYears;
                        const overtimeYearSelect = $('#overtimeYear');
                        
                        // تفريغ الخيارات الحالية
                        overtimeYearSelect.empty();
                        
                        // إضافة السنوات المتاحة
                        years.forEach(year => {
                            overtimeYearSelect.append(new Option(year, year));
                        });
                        
                        // تعيين السنة الحالية كافتراضي
                        const currentYear = new Date().getFullYear();
                        if (years.includes(currentYear)) {
                            overtimeYearSelect.val(currentYear);
                        } else if (years.length > 0) {
                            overtimeYearSelect.val(years[0]);
                        }
                        
                        // تحميل بيانات ساعات العمل الإضافية بعد تحميل السنوات
                        setTimeout(() => {
                            loadOvertimeData();
                        }, 100);
                    } else {
                        // استخدام سنوات افتراضية إذا لم توجد بيانات
                        loadOvertimeDefaultYears();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime available years:', error);
                    // استخدام سنوات افتراضية في حالة الخطأ
                    loadOvertimeDefaultYears();
                }
            });
        }

        // دالة لتحميل السنوات الافتراضية لساعات العمل الإضافية
        function loadOvertimeDefaultYears() {
            const currentYear = new Date().getFullYear();
            const overtimeYearSelect = $('#overtimeYear');
            
            overtimeYearSelect.empty();
            
            // إضافة السنوات الخمس الأخيرة
            for (let year = currentYear; year >= currentYear - 5; year--) {
                overtimeYearSelect.append(new Option(year, year));
            }
            
            overtimeYearSelect.val(currentYear);
            
            // تحميل بيانات ساعات العمل الإضافية
            setTimeout(() => {
                loadOvertimeData();
            }, 100);
        }

        // دالة لتحميل بيانات ساعات العمل الإضافية
        function loadOvertimeData() {
            const year = $('#overtimeYear').val();
            const month = $('#overtimeMonth').val();
            const department = $('#overtimeDepartment').val();
            
            // التحقق من وجود سنة محددة
            if (!year) {
                alert('يرجى اختيار سنة');
                return;
            }
            
            const filters = {
                year: year,
                month: month || 'all',
                department: department || 'all'
            };
            
            console.log('Loading overtime data with filters:', filters);
            loadOvertimeCharts(filters);
        }

        // دالة لتحميل جميع مخططات ساعات العمل الإضافية
        function loadOvertimeCharts(filters) {
            loadOvertimeSummary(filters);
            loadOvertimeByTypeChart(filters);
            loadOvertimeAmountByTypeChart(filters);
            loadOvertimeByDepartmentChart(filters);
            loadOvertimeAmountByDepartmentChart(filters);
            loadOvertimeByGradeChart(filters);
            loadOvertimeAmountByGradeChart(filters);
            loadOvertimeByMonthChart(filters);
            loadOvertimeEmployeesTable(filters);
        }

        // دالة لتحميل إحصائيات ساعات العمل الإضافية
        function loadOvertimeSummary(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    console.log('Overtime data received:', data);
                    
                    if (data.success) {
                        const stats = data.stats.totalStats;
                        
                        // تحديث الإحصائيات
                        $('#totalOvertimeHours').text(stats.total_hours || 0);
                        $('#totalOvertimeAmount').text(formatCurrency(stats.total_amount || 0));
                        $('#avgOvertimePerEmployee').text((stats.avg_hours_per_employee || 0).toFixed(1));
                        $('#overtimeEmployeesCount').text(stats.total_employees || 0);
                    } else {
                        console.error('Error in overtime data:', data.message);
                        setDefaultOvertimeValues();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime summary:', error);
                    setDefaultOvertimeValues();
                }
            });
        }

        // دالة لتعيين القيم الافتراضية لساعات العمل الإضافية
        function setDefaultOvertimeValues() {
            $('#totalOvertimeHours').text('0');
            $('#totalOvertimeAmount').text('0');
            $('#avgOvertimePerEmployee').text('0');
            $('#overtimeEmployeesCount').text('0');
        }

        // دالة لتحميل مخطط الساعات حسب النوع
        function loadOvertimeByTypeChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byType) {
                        const byType = data.stats.byType;
                        const ctx = document.getElementById('overtimeByTypeChart').getContext('2d');
                        
                        if (overtimeChartInstances.byType) {
                            overtimeChartInstances.byType.destroy();
                        }
                        
                        const types = ['HS125', 'HS150', 'HS175'];
                        const hours = [
                            byType.hs125_hours || 0,
                            byType.hs150_hours || 0,
                            byType.hs175_hours || 0
                        ];
                        
                        overtimeChartInstances.byType = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: types,
                                datasets: [{
                                    data: hours,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime by type chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط القيمة حسب النوع
        function loadOvertimeAmountByTypeChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byType) {
                        const byType = data.stats.byType;
                        const ctx = document.getElementById('overtimeAmountByTypeChart').getContext('2d');
                        
                        if (overtimeChartInstances.amountByType) {
                            overtimeChartInstances.amountByType.destroy();
                        }
                        
                        const types = ['HS125', 'HS150', 'HS175'];
                        const amounts = [
                            byType.hs125_amount || 0,
                            byType.hs150_amount || 0,
                            byType.hs175_amount || 0
                        ];
                        
                        overtimeChartInstances.amountByType = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: types,
                                datasets: [{
                                    data: amounts,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime amount by type chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط الساعات حسب الإدارات
        function loadOvertimeByDepartmentChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byDepartment) {
                        const byDepartment = data.stats.byDepartment;
                        const ctx = document.getElementById('overtimeByDepartmentChart').getContext('2d');
                        
                        if (overtimeChartInstances.byDepartment) {
                            overtimeChartInstances.byDepartment.destroy();
                        }
                        
                        const departments = byDepartment.map(dept => dept.department);
                        const hours = byDepartment.map(dept => dept.total_hours || 0);
                        
                        overtimeChartInstances.byDepartment = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: departments,
                                datasets: [{
                                    label: 'إجمالي الساعات',
                                    data: hours,
                                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                                    borderColor: 'rgba(52, 152, 219, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الساعات'
                                        }
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime by department chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط القيمة حسب الإدارات
        function loadOvertimeAmountByDepartmentChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byDepartment) {
                        const byDepartment = data.stats.byDepartment;
                        const ctx = document.getElementById('overtimeAmountByDepartmentChart').getContext('2d');
                        
                        if (overtimeChartInstances.amountByDepartment) {
                            overtimeChartInstances.amountByDepartment.destroy();
                        }
                        
                        const departments = byDepartment.map(dept => dept.department);
                        const amounts = byDepartment.map(dept => dept.total_amount || 0);
                        
                        overtimeChartInstances.amountByDepartment = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: departments,
                                datasets: [{
                                    label: 'إجمالي القيمة',
                                    data: amounts,
                                    backgroundColor: 'rgba(46, 204, 113, 0.7)',
                                    borderColor: 'rgba(46, 204, 113, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                indexAxis: 'y',
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'القيمة (دينار)'
                                        }
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime amount by department chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط الساعات حسب الرتب
        function loadOvertimeByGradeChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byGrade) {
                        const byGrade = data.stats.byGrade;
                        const ctx = document.getElementById('overtimeByGradeChart').getContext('2d');
                        
                        if (overtimeChartInstances.byGrade) {
                            overtimeChartInstances.byGrade.destroy();
                        }
                        
                        const grades = byGrade.map(grade => grade.grade);
                        const hours = byGrade.map(grade => grade.total_hours || 0);
                        
                        overtimeChartInstances.byGrade = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: grades,
                                datasets: [{
                                    data: hours,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)',
                                        'rgba(241, 196, 15, 0.7)',
                                        'rgba(230, 126, 34, 0.7)',
                                        'rgba(231, 76, 60, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime by grade chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط القيمة حسب الرتب
        function loadOvertimeAmountByGradeChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byGrade) {
                        const byGrade = data.stats.byGrade;
                        const ctx = document.getElementById('overtimeAmountByGradeChart').getContext('2d');
                        
                        if (overtimeChartInstances.amountByGrade) {
                            overtimeChartInstances.amountByGrade.destroy();
                        }
                        
                        const grades = byGrade.map(grade => grade.grade);
                        const amounts = byGrade.map(grade => grade.total_amount || 0);
                        
                        overtimeChartInstances.amountByGrade = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: grades,
                                datasets: [{
                                    data: amounts,
                                    backgroundColor: [
                                        'rgba(52, 152, 219, 0.7)',
                                        'rgba(46, 204, 113, 0.7)',
                                        'rgba(155, 89, 182, 0.7)',
                                        'rgba(241, 196, 15, 0.7)',
                                        'rgba(230, 126, 34, 0.7)',
                                        'rgba(231, 76, 60, 0.7)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime amount by grade chart:', error);
                }
            });
        }

        // دالة لتحميل مخطط الساعات حسب الأشهر
        function loadOvertimeByMonthChart(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.byMonth) {
                        const byMonth = data.stats.byMonth;
                        const ctx = document.getElementById('overtimeByMonthChart').getContext('2d');
                        
                        if (overtimeChartInstances.byMonth) {
                            overtimeChartInstances.byMonth.destroy();
                        }
                        
                        const months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'ماي', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
                        const hours = byMonth.map(month => month.total_hours || 0);
                        const amounts = byMonth.map(month => month.total_amount || 0);
                        
                        overtimeChartInstances.byMonth = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: months,
                                datasets: [
                                    {
                                        label: 'إجمالي الساعات',
                                        data: hours,
                                        backgroundColor: 'rgba(52, 152, 219, 0.2)',
                                        borderColor: 'rgba(52, 152, 219, 1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'إجمالي القيمة',
                                        data: amounts,
                                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                                        borderColor: 'rgba(46, 204, 113, 1)',
                                        borderWidth: 2,
                                        tension: 0.3,
                                        fill: true,
                                        yAxisID: 'y1'
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الساعات'
                                        }
                                    },
                                    y1: {
                                        beginAtZero: true,
                                        position: 'right',
                                        title: {
                                            display: true,
                                            text: 'القيمة (دينار)'
                                        },
                                        grid: {
                                            drawOnChartArea: false
                                        }
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime by month chart:', error);
                }
            });
        }

        // دالة لتحميل جدول الموظفين مع ساعات العمل الإضافية
        function loadOvertimeEmployeesTable(filters) {
            $.ajax({
                url: 'get_overtime_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const employeesList = data.stats.employeesList || [];
                        
                        let tableBody = '';
                        
                        if (employeesList.length === 0) {
                            tableBody = '<tr><td colspan="13" class="text-center">لا توجد بيانات</td></tr>';
                        } else {
                            employeesList.forEach((employee, index) => {
                                const totalHours = (parseFloat(employee.HS125_Nombre || 0) + 
                                                  parseFloat(employee.HS150_Nombre || 0) + 
                                                  parseFloat(employee.HS175_Nombre || 0));
                                                  
                                const totalAmount = (parseFloat(employee.HS125 || 0) + 
                                                   parseFloat(employee.HS150 || 0) + 
                                                   parseFloat(employee.HS175 || 0));
                                
                                tableBody += `
                                    <tr>
                                        <td>${employee.MatriculeSalarie || ''}</td>
                                        <td>${employee.Nom || ''}</td>
                                        <td>${employee.Prenom || ''}</td>
                                        <td>${employee.Intitule_Service || ''}</td>
                                        <td>${employee.Grade || ''}</td>
                                        <td>${employee.HS125_Nombre || '0'}</td>
                                        <td>${formatCurrency(employee.HS125 || 0)}</td>
                                        <td>${employee.HS150_Nombre || '0'}</td>
                                        <td>${formatCurrency(employee.HS150 || 0)}</td>
                                        <td>${employee.HS175_Nombre || '0'}</td>
                                        <td>${formatCurrency(employee.HS175 || 0)}</td>
                                        <td>${totalHours.toFixed(1)}</td>
                                        <td>${formatCurrency(totalAmount)}</td>
                                    </tr>
                                `;
                            });
                        }
                        
                        $('#overtimeEmployeesTable tbody').html(tableBody);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading overtime employees table:', error);
                    $('#overtimeEmployeesTable tbody').html('<tr><td colspan="13" class="text-center text-danger">حدث خطأ في تحميل البيانات</td></tr>');
                }
            });
        }

        // دالة لتصدير تقرير ساعات العمل الإضافية
        function exportOvertimeReport() {
            alert('سيتم تصدير تقرير ساعات العمل الإضافية إلى Excel');
        }

        // ========== دوال الإجازات ==========
        
        // دالة لتحميل السنوات المتاحة للإجازات
        function loadLeavesAvailableYears() {
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: { action: 'get_years' },
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.stats.availableYears) {
                        const years = data.stats.availableYears;
                        const currentYearSelect = $('#leavesYear');
                        const compareYearSelect = $('#leavesCompareYear');
                        
                        // تفريغ الخيارات الحالية
                        currentYearSelect.empty();
                        compareYearSelect.empty();
                        compareYearSelect.append('<option value="">بدون مقارنة</option>');
                        
                        // إضافة السنوات المتاحة
                        years.forEach(year => {
                            currentYearSelect.append(new Option(year, year));
                            compareYearSelect.append(new Option(year, year));
                        });
                        
                        // تعيين السنة الحالية كافتراضي
                        const currentYear = new Date().getFullYear();
                        if (years.includes(currentYear)) {
                            currentYearSelect.val(currentYear);
                        } else if (years.length > 0) {
                            currentYearSelect.val(years[0]);
                        }
                        
                        // تحميل بيانات الإجازات بعد تحميل السنوات
                        setTimeout(() => {
                            loadLeavesData();
                        }, 100);
                    } else {
                        // استخدام سنوات افتراضية إذا لم توجد بيانات
                        loadLeavesDefaultYears();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves available years:', error);
                    // استخدام سنوات افتراضية في حالة الخطأ
                    loadLeavesDefaultYears();
                }
            });
        }

        // دالة لتحميل السنوات الافتراضية للإجازات
        function loadLeavesDefaultYears() {
            const currentYear = new Date().getFullYear();
            const currentYearSelect = $('#leavesYear');
            const compareYearSelect = $('#leavesCompareYear');
            
            currentYearSelect.empty();
            compareYearSelect.empty();
            compareYearSelect.append('<option value="">بدون مقارنة</option>');
            
            // إضافة السنوات الخمس الأخيرة
            for (let year = currentYear; year >= currentYear - 5; year--) {
                currentYearSelect.append(new Option(year, year));
                compareYearSelect.append(new Option(year, year));
            }
            
            currentYearSelect.val(currentYear);
            
            // تحميل بيانات الإجازات
            setTimeout(() => {
                loadLeavesData();
            }, 100);
        }

        // دالة لتحميل بيانات الإجازات
        function loadLeavesData() {
            const year = $('#leavesYear').val();
            const compareYear = $('#leavesCompareYear').val();
            const department = $('#leavesDepartment').val();
            
            // التحقق من وجود سنة محددة
            if (!year) {
                alert('يرجى اختيار سنة');
                return;
            }
            
            const filters = {
                year: year,
                compareYear: compareYear || '',
                department: department || 'all'
            };
            
            console.log('Loading leaves data with filters:', filters);
            loadLeavesCharts(filters);
        }

        // دالة لتحميل مخططات الإجازات
        function loadLeavesCharts(filters) {
            loadLeavesSummary(filters);
            loadLeavesByMonthChart(filters);
            loadLeavesDaysByMonthChart(filters);
            loadLeavesByDepartmentChart(filters);
            loadLeavesDaysByDepartmentChart(filters);
            loadMonthlyLeavesTable(filters);
        }

        // دالة لتحميل إحصائيات الإجازات
        function loadLeavesSummary(filters) {
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    console.log('Leaves summary data received:', data);
                    
                    if (data.success) {
                        const stats = data.stats.totalStats;
                        
                        // تحديث الإحصائيات الأساسية
                        $('#totalLeaves').text(stats.total_leaves || 0);
                        $('#totalLeavesEmployees').text(stats.total_employees || 0);
                        $('#totalLeavesDays').text(stats.total_days || 0);
                        $('#avgLeavesDays').text((stats.avg_days || 0).toFixed(1));
                    } else {
                        console.error('Error in leaves data:', data.message);
                        setDefaultLeavesValues();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves summary:', error);
                    setDefaultLeavesValues();
                }
            });
        }

        // دالة لتعيين القيم الافتراضية للإجازات
        function setDefaultLeavesValues() {
            $('#totalLeaves').text('0');
            $('#totalLeavesEmployees').text('0');
            $('#totalLeavesDays').text('0');
            $('#avgLeavesDays').text('0');
        }

        // دالة لتحميل مخطط الإجازات حسب الأشهر
        function loadLeavesByMonthChart(filters) {
            $('#leavesByMonthChartLoading').show();
            $('#leavesByMonthChart').hide();
            $('#leavesByMonthChartError').hide();
            
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: {...filters, chart: 'by_month'},
                dataType: 'json',
                success: function(data) {
                    $('#leavesByMonthChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#leavesByMonthChart').show();
                        
                        const ctx = document.getElementById('leavesByMonthChart').getContext('2d');
                        
                        if (window.leavesByMonthChartInstance) {
                            window.leavesByMonthChartInstance.destroy();
                        }
                        
                        window.leavesByMonthChartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الإجازات'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'الأشهر'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#leavesByMonthChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves by month chart:', error);
                    $('#leavesByMonthChartLoading').hide();
                    $('#leavesByMonthChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط أيام الإجازة حسب الأشهر
        function loadLeavesDaysByMonthChart(filters) {
            $('#leavesDaysByMonthChartLoading').show();
            $('#leavesDaysByMonthChart').hide();
            $('#leavesDaysByMonthChartError').hide();
            
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: {...filters, chart: 'days_by_month'},
                dataType: 'json',
                success: function(data) {
                    $('#leavesDaysByMonthChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#leavesDaysByMonthChart').show();
                        
                        const ctx = document.getElementById('leavesDaysByMonthChart').getContext('2d');
                        
                        if (window.leavesDaysByMonthChartInstance) {
                            window.leavesDaysByMonthChartInstance.destroy();
                        }
                        
                        window.leavesDaysByMonthChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'أيام الإجازة'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'الأشهر'
                                        }
                                    }
                                }
                            }
                        });
                    } else {
                        $('#leavesDaysByMonthChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves days by month chart:', error);
                    $('#leavesDaysByMonthChartLoading').hide();
                    $('#leavesDaysByMonthChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط الإجازات حسب الإدارات
        function loadLeavesByDepartmentChart(filters) {
            $('#leavesByDepartmentChartLoading').show();
            $('#leavesByDepartmentChart').hide();
            $('#leavesByDepartmentChartError').hide();
            
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: {...filters, chart: 'by_department'},
                dataType: 'json',
                success: function(data) {
                    $('#leavesByDepartmentChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#leavesByDepartmentChart').show();
                        
                        const ctx = document.getElementById('leavesByDepartmentChart').getContext('2d');
                        
                        if (window.leavesByDepartmentChartInstance) {
                            window.leavesByDepartmentChartInstance.destroy();
                        }
                        
                        window.leavesByDepartmentChartInstance = new Chart(ctx, {
                            type: 'pie',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#leavesByDepartmentChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves by department chart:', error);
                    $('#leavesByDepartmentChartLoading').hide();
                    $('#leavesByDepartmentChartError').show();
                }
            });
        }

        // دالة لتحميل مخطط أيام الإجازة حسب الإدارات
        function loadLeavesDaysByDepartmentChart(filters) {
            $('#leavesDaysByDepartmentChartLoading').show();
            $('#leavesDaysByDepartmentChart').hide();
            $('#leavesDaysByDepartmentChartError').hide();
            
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: {...filters, chart: 'days_by_department'},
                dataType: 'json',
                success: function(data) {
                    $('#leavesDaysByDepartmentChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#leavesDaysByDepartmentChart').show();
                        
                        const ctx = document.getElementById('leavesDaysByDepartmentChart').getContext('2d');
                        
                        if (window.leavesDaysByDepartmentChartInstance) {
                            window.leavesDaysByDepartmentChartInstance.destroy();
                        }
                        
                        window.leavesDaysByDepartmentChartInstance = new Chart(ctx, {
                            type: 'doughnut',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } else {
                        $('#leavesDaysByDepartmentChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading leaves days by department chart:', error);
                    $('#leavesDaysByDepartmentChartLoading').hide();
                    $('#leavesDaysByDepartmentChartError').show();
                }
            });
        }

        // دالة لتحميل جدول الإجازات الشهري
        function loadMonthlyLeavesTable(filters) {
            $.ajax({
                url: 'get_leaves_stats.php',
                type: 'POST',
                data: {...filters, action: 'monthly_table'},
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.monthlyData) {
                        const monthlyData = data.monthlyData;
                        const months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'ماي', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
                        
                        let tableBody = '';
                        let totalLeaves = 0;
                        let totalDays = 0;
                        
                        monthlyData.forEach((month, index) => {
                            const count = month.count || 0;
                            const days = month.total_days || 0;
                            const avg = month.avg_days || 0;
                            
                            totalLeaves += count;
                            totalDays += days;
                            
                            tableBody += `
                                <tr>
                                    <td>${months[index]}</td>
                                    <td>${count}</td>
                                    <td>${days}</td>
                                    <td>${avg.toFixed(1)}</td>
                                </tr>
                            `;
                        });
                        
                        // إضافة المجموع
                        tableBody += `
                            <tr class="table-info fw-bold">
                                <td>المجموع</td>
                                <td>${totalLeaves}</td>
                                <td>${totalDays}</td>
                                <td>${(totalDays / (totalLeaves || 1)).toFixed(1)}</td>
                            </tr>
                        `;
                        
                        $('#monthlyLeavesTable tbody').html(tableBody);
                    } else {
                        $('#monthlyLeavesTable tbody').html('<tr><td colspan="4" class="text-center">لا توجد بيانات</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading monthly leaves table:', error);
                    $('#monthlyLeavesTable tbody').html('<tr><td colspan="4" class="text-center text-danger">حدث خطأ في تحميل البيانات</td></tr>');
                }
            });
        }

        // دالة لتصدير تقرير الإجازات
        function exportLeavesReport() {
            alert('سيتم تصدير تقرير الإجازات إلى Excel');
        }

        // ========== دوال التقارير العامة ==========
        
        // دالة لتحميل قائمة الإدارات
        function loadDepartments() {
            $.ajax({
                url: 'get_departments.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        const select = $('#departmentFilter');
                        const departuresSelect = $('#departuresDepartment');
                        const overtimeSelect = $('#overtimeDepartment');
                        const leavesSelect = $('#leavesDepartment');
                        const demographicSelect = $('#demographicDepartment');
                        
                        select.empty().append('<option value="all">جميع الإدارات</option>');
                        departuresSelect.empty().append('<option value="all">جميع الإدارات</option>');
                        overtimeSelect.empty().append('<option value="all">جميع الإدارات</option>');
                        leavesSelect.empty().append('<option value="all">جميع الإدارات</option>');
                        demographicSelect.empty().append('<option value="all">جميع الإدارات</option>');
                        
                        data.departments.forEach(dept => {
                            select.append(new Option(dept.name, dept.id));
                            departuresSelect.append(new Option(dept.name, dept.id));
                            overtimeSelect.append(new Option(dept.name, dept.id));
                            leavesSelect.append(new Option(dept.name, dept.id));
                            demographicSelect.append(new Option(dept.name, dept.id));
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading departments:', error);
                }
            });
        }

        // دالة رئيسية لتحميل بيانات التقارير
        function loadReportsData() {
            // جمع معاملات الفلترة
            const filters = {
                timePeriod: $('#timePeriod').val(),
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                employeeType: $('#employeeType').val(),
                department: $('#departmentFilter').val()
            };
            
            // تحميل الإحصائيات الأساسية
            loadBasicStats(filters);
            
            // تحميل المخططات
            loadCharts(filters);
        }
        
        // دالة لتحميل الإحصائيات الأساسية
        function loadBasicStats(filters) {
            $.ajax({
                url: 'get_reports_stats.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // تحديث الإحصائيات
                        $('#totalEmployees').text(data.stats.totalEmployees);
                        $('#permanentEmployees').text(data.stats.permanentEmployees);
                        $('#traineeEmployees').text(data.stats.traineeEmployees);
                        $('#attachedEmployees').text(data.stats.attachedEmployees);
                        $('#avgAge').text(data.stats.avgAge + ' سنة');
                        $('#avgSeniority').text(data.stats.avgSeniority + ' سنة');
                        
                        // تحديث بطاقات التقارير
                        $('#totalEmployeesCount').text(data.stats.totalEmployees);
                        $('#monthlyHires').text(data.stats.monthlyHires || 0);
                        $('#turnoverRate').text(data.stats.turnoverRate || '0%');
                        $('#attendanceRate').text(data.stats.attendanceRate || '0%');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading stats:', error);
                }
            });
        }
        
        // دالة لتحميل جميع المخططات
        function loadCharts(filters) {
            loadDepartmentChart(filters);
            loadGrowthChart(filters);
        }
        
        // دالة لتحميل مخطط توزيع الإدارات
        function loadDepartmentChart(filters) {
            $('#departmentChartLoading').show();
            $('#departmentChart').hide();
            $('#departmentChartError').hide();
            
            $.ajax({
                url: 'get_department_chart.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    $('#departmentChartLoading').hide();
                    
                    if (data.success) {
                        $('#departmentChart').show();
                        
                        const ctx = document.getElementById('departmentChart').getContext('2d');
                        
                        if (window.departmentChartInstance) {
                            window.departmentChartInstance.destroy();
                        }
                        
                        window.departmentChartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    } else {
                        $('#departmentChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading department chart:', error);
                    $('#departmentChartLoading').hide();
                    $('#departmentChartError').show();
                }
            });
        }
        
        // دالة لتحميل مخطط النمو
        function loadGrowthChart(filters) {
            $('#growthChartLoading').show();
            $('#growthChart').hide();
            $('#growthChartError').hide();
            
            console.log('Loading growth chart with filters:', filters);
            
            $.ajax({
                url: 'get_growth_chart.php',
                type: 'POST',
                data: filters,
                dataType: 'json',
                success: function(data) {
                    console.log('Growth chart response:', data);
                    
                    $('#growthChartLoading').hide();
                    
                    if (data.success && data.chartData) {
                        $('#growthChart').show();
                        
                        const ctx = document.getElementById('growthChart').getContext('2d');
                        
                        // تدمير المخطط السابق إذا كان موجوداً
                        if (window.growthChartInstance) {
                            window.growthChartInstance.destroy();
                        }
                        
                        // إنشاء المخطط الجديد مع البيانات الفعلية
                        window.growthChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: data.chartData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        rtl: true,
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        rtl: true
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'عدد الموظفين'
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'الأشهر'
                                        },
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.1)'
                                        }
                                    }
                                },
                                interaction: {
                                    intersect: false,
                                    mode: 'nearest'
                                },
                                elements: {
                                    point: {
                                        radius: 4,
                                        hoverRadius: 6
                                    }
                                }
                            }
                        });
                        
                        console.log('Growth chart created successfully');
                    } else {
                        console.error('Invalid growth chart data:', data);
                        $('#growthChartError').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading growth chart:', error);
                    console.log('Response:', xhr.responseText);
                    
                    $('#growthChartLoading').hide();
                    $('#growthChartError').show();
                }
            });
        }
        
        // دالة لمسح الفلاتر
        function clearFilters() {
            $('#timePeriod').val('month');
            $('#employeeType').val('all');
            $('#departmentFilter').val('all');
            $('#startDate').val('');
            $('#endDate').val('');
            $('#customDateRange').hide();
            
            loadReportsData();
        }
        
        // دالة لتصدير التقارير
        function exportReports() {
            const filters = {
                timePeriod: $('#timePeriod').val(),
                employeeType: $('#employeeType').val(),
                department: $('#departmentFilter').val()
            };
            
            alert('سيتم تصدير التقارير بناءً على الفلاتر المحددة');
        }
        
        // دالة لطباعة التقارير
        function printReports() {
            window.print();
        }
        
        // دالة لتوليد تقرير محدد
        function generateReport(type) {
            const filters = {
                timePeriod: $('#timePeriod').val(),
                employeeType: $('#employeeType').val(),
                department: $('#departmentFilter').val(),
                reportType: type
            };
            
            const reportWindow = window.open('', '_blank');
            reportWindow.document.write(`
                <html>
                <head>
                    <title>تقرير ${getReportTitle(type)}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .report-title { font-size: 24px; margin-bottom: 10px; }
                        .filters { margin-bottom: 20px; padding: 10px; background: #f8f9fa; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="report-title">تقرير ${getReportTitle(type)}</div>
                        <div>تاريخ التقرير: ${new Date().toLocaleDateString('fr-FR')}</div>
                    </div>
                    <div class="filters">
                        <strong>الفلاتر المطبقة:</strong><br>
                        الفترة: ${$('#timePeriod option:selected').text()} | 
                        نوع الموظفين: ${$('#employeeType option:selected').text()} |
                        الإدارة: ${$('#departmentFilter option:selected').text()}
                    </div>
                    <div id="reportContent">
                        جاري تحميل التقرير...
                    </div>
                </body>
                </html>
            `);
            
            $.ajax({
                url: 'generate_report.php',
                type: 'POST',
                data: filters,
                success: function(data) {
                    reportWindow.document.getElementById('reportContent').innerHTML = data;
                    reportWindow.document.close();
                },
                error: function() {
                    reportWindow.document.getElementById('reportContent').innerHTML = 'حدث خطأ أثناء تحميل التقرير';
                    reportWindow.document.close();
                }
            });
        }
        
        // دالة مساعدة للحصول على عنوان التقرير
        function getReportTitle(type) {
            const titles = {
                'employees': 'الموظفين',
                'hiring': 'التعيينات',
                'turnover': 'الدوران الوظيفي',
                'attendance': 'الحضور'
            };
            return titles[type] || 'غير محدد';
        }
        
        // دالة لتحديث مخطط الإدارات
        function updateDepartmentChart(viewType) {
            const filters = {
                timePeriod: $('#timePeriod').val(),
                employeeType: $('#employeeType').val(),
                department: $('#departmentFilter').val(),
                viewType: viewType
            };
            
            loadDepartmentChart(filters);
        }

        // دالة مساعدة لتنسيق العملة
        function formatCurrency(amount) {
            if (!amount) return '0.000';
            return parseFloat(amount).toLocaleString('ar-TN', {
                minimumFractionDigits: 3,
                maximumFractionDigits: 3
            });
        }

        // دالة مساعدة لتنسيق التاريخ
        function formatShortDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('ar-TN', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }
    </script>
</body>
</html>