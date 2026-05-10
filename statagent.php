<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إحصاءات الأعوان</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php session_start(); ?>
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
            border-top: 4px solid var(--secondary-color);
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
            background: linear-gradient(90deg, var(--secondary-color) 0%, transparent 100%);
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
        
        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .chart-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .chart-btn {
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 5px;
        }
        
        .chart-btn:hover {
            background: #f8f9fa;
            color: var(--secondary-color);
        }
        
        .chart-wrapper {
            position: relative;
            height: 300px;
            width: 100%;
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
        
        .alert-danger {
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, var(--accent-color) 0%, #c0392b 100%);
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
        
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                min-width: 100%;
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
            
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                padding: 15px;
            }
            
            .chart-wrapper {
                height: 250px;
            }
        }
    </style>
</head>

<body>
    <div class="container-main">
        <div class="header-section">
            <h1 class="header-title">إحصاءات الأعوان</h1>
            <p class="header-subtitle">تحليل شامل للبيانات حسب العمر، الرتبة، الإرتباط، الأقدمية والمزيد</p>
        </div>
        
        <?php 
        
        include('menu.php'); 
        include('connection.php');
        ?>

        <!-- Loading Spinner -->
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
            <p class="mt-2">جاري تحميل البيانات والإحصائيات...</p>
        </div>

        <!-- Stats Section -->
        <div class="stats-section" id="statsContainer">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Controls Section -->
        <div class="controls-section">
            <div class="d-flex justify-content-between flex-wrap gap-3">
                <button id="refreshStats" class="btn-modern btn-clear">
                    <i class="fas fa-sync-alt"></i>
                    <span>تحديث الإحصائيات</span>
                </button>
                
                <button id="clearFilters" class="btn-modern btn-clear">
                    <i class="fas fa-trash-alt"></i>
                    <span>مسح الفلاتر</span>
                </button>
                
                <button id="exportCharts" class="btn-modern btn-export">
                    <i class="fas fa-download"></i>
                    <span>تصدير التقارير</span>
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters-section">
            <h5 class="mb-3">فلاتر الإحصائيات</h5>
            <div class="filter-grid">
                <div>
                    <label class="form-label">نوع العون</label>
                    <select id="filterSituation" class="form-control form-control-custom">
                        <option value="">جميع الأنواع</option>
                        <option value="0">مترسم</option>
                        <option value="1">متربص</option>
                        <option value="3">ملحق</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">وحدة الإرتباط</label>
                    <select id="filterDepartment" class="form-control form-control-custom">
                        <option value="">جميع الوحدات</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                </div>
                <div>
                    <label class="form-label">الفئة العمرية</label>
                    <select id="filterAge" class="form-control form-control-custom">
                        <option value="">جميع الأعمار</option>
                        <option value="20-30">20 - 30 سنة</option>
                        <option value="31-40">31 - 40 سنة</option>
                        <option value="41-50">41 - 50 سنة</option>
                        <option value="51-60">51 - 60 سنة</option>
                        <option value="60+">أكثر من 60 سنة</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">الأقدمية</label>
                    <select id="filterSeniority" class="form-control form-control-custom">
                        <option value="">جميع المستويات</option>
                        <option value="0-5">0 - 5 سنوات</option>
                        <option value="6-10">6 - 10 سنوات</option>
                        <option value="11-15">11 - 15 سنوات</option>
                        <option value="16-20">16 - 20 سنوات</option>
                        <option value="20+">أكثر من 20 سنة</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Filtered Results Message -->
        <div class="filtered-results" id="filteredResultsMessage" style="display: none;">
            <i class="fas fa-filter me-2"></i>
            تم العثور على <span id="resultsCount">0</span> نتيجة من أصل <span id="totalCount">0</span>
        </div>

        <!-- Charts Section -->
        <div class="charts-section" id="chartsContainer">
            <!-- Age Distribution Chart -->
            <div class="chart-container fade-in">
                <div class="chart-header">
                    <h3 class="chart-title">التوزيع حسب الفئة العمرية</h3>
                    <div class="chart-actions">
                        <button class="chart-btn" data-chart="ageChart">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>

            <!-- Grade Distribution Chart -->
            <div class="chart-container fade-in">
                <div class="chart-header">
                    <h3 class="chart-title">التوزيع حسب الرتبة</h3>
                    <div class="chart-actions">
                        <button class="chart-btn" data-chart="gradeChart">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            <!-- Department Distribution Chart -->
            <div class="chart-container fade-in">
                <div class="chart-header">
                    <h3 class="chart-title">التوزيع حسب وحدة الإرتباط</h3>
                    <div class="chart-actions">
                        <button class="chart-btn" data-chart="departmentChart">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>

            <!-- Seniority Distribution Chart -->
            <div class="chart-container fade-in">
                <div class="chart-header">
                    <h3 class="chart-title">التوزيع حسب الأقدمية</h3>
                    <div class="chart-actions">
                        <button class="chart-btn" data-chart="seniorityChart">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="seniorityChart"></canvas>
                </div>
            </div>

            <!-- Situation Distribution Chart -->
            <div class="chart-container fade-in">
                <div class="chart-header">
                    <h3 class="chart-title">التوزيع حسب نوع العون</h3>
                    <div class="chart-actions">
                        <button class="chart-btn" data-chart="situationChart">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="situationChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Data Table -->
        <div class="mobile-message">
            <i class="fas fa-mobile-alt me-2"></i>
            لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table id="statsTable" class="custom-table">
                    <thead>
                        <tr>
                            <th>ع/ر</th>
                            <th>الرقم الآلي</th>
                            <th>الإسم و اللقب</th>
                            <th>العمر</th>
                            <th>الأقدمية</th>
                            <th>الرتبة</th>
                            <th>نوع العون</th>
                            <th>وحدة الإرتباط</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let employeesData = [];
        let departmentsData = [];
        let gradesData = [];
        let chartInstances = {};
        let filteredEmployees = [];

        // Initialize charts and data
        document.addEventListener('DOMContentLoaded', function() {
            loadDataFromDatabase();
        });

        // Load data from database
        function loadDataFromDatabase() {
            showLoading(true);
            
            // Fetch data from server
            fetch('get_stats_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        employeesData = data.employees;
                        departmentsData = data.departments;
                        gradesData = data.grades;
                        filteredEmployees = [...employeesData];
                        
                        initializeStats();
                        initializeCharts();
                        populateTable();
                        populateDepartmentFilter();
                        setupEventListeners();
                        updateFilteredMessage();
                        
                        showNotification('تم تحميل البيانات بنجاح', 'success');
                    } else {
                        throw new Error(data.error || 'حدث خطأ غير معروف');
                    }
                    
                    showLoading(false);
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                    showNotification('حدث خطأ في تحميل البيانات: ' + error.message, 'error');
                    showNoDataMessage();
                    showLoading(false);
                });
        }

        // Show/hide loading spinner
        function showLoading(show) {
            document.getElementById('loadingSpinner').style.display = show ? 'block' : 'none';
        }

        // Show message when no data is available
        function showNoDataMessage() {
            document.getElementById('statsContainer').innerHTML = `
                <div class="col-12">
                    <div class="no-data">
                        <i class="fas fa-database fa-3x mb-3"></i>
                        <h4>لا توجد بيانات متاحة</h4>
                        <p>لم يتم العثور على بيانات الأعوان في النظام</p>
                    </div>
                </div>
            `;
            
            document.getElementById('chartsContainer').innerHTML = `
                <div class="col-12">
                    <div class="no-data">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <h4>لا توجد بيانات للإحصائيات</h4>
                        <p>يجب وجود بيانات الأعوان لعرض الإحصائيات</p>
                    </div>
                </div>
            `;
            
            document.querySelector('#statsTable tbody').innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="no-data">
                            <i class="fas fa-table fa-2x mb-2"></i>
                            <p>لا توجد بيانات للعرض</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Initialize statistics cards
        function initializeStats() {
            if (employeesData.length === 0) {
                showNoDataMessage();
                return;
            }

            updateStatsCards(filteredEmployees);
        }

        // Update statistics cards based on filtered data
        function updateStatsCards(data) {
            const totalEmployees = data.length;
            const permanentEmployees = data.filter(emp => emp.situation === "مترسم").length;
            const traineeEmployees = data.filter(emp => emp.situation === "متربص").length;
            const attachedEmployees = data.filter(emp => emp.situation === "ملحق").length;
            
            document.getElementById('statsContainer').innerHTML = `
                <div class="stat-card stat-total">
                    <div class="stat-value">${totalEmployees}</div>
                    <div class="stat-label">إجمالي الأعوان</div>
                </div>
                <div class="stat-card stat-permanent">
                    <div class="stat-value">${permanentEmployees}</div>
                    <div class="stat-label">مترسمين</div>
                </div>
                <div class="stat-card stat-trainee">
                    <div class="stat-value">${traineeEmployees}</div>
                    <div class="stat-label">متربصين</div>
                </div>
                <div class="stat-card stat-attached">
                    <div class="stat-value">${attachedEmployees}</div>
                    <div class="stat-label">ملحقين</div>
                </div>
                <div class="stat-card stat-filtered">
                    <div class="stat-value" id="filteredCount">${totalEmployees}</div>
                    <div class="stat-label">النتائج المصفاة</div>
                </div>
            `;
        }

        // Initialize all charts
        function initializeCharts() {
            if (employeesData.length === 0) return;

            // Destroy existing charts
            Object.values(chartInstances).forEach(chart => {
                if (chart) chart.destroy();
            });
            chartInstances = {};

            createAgeChart();
            createGradeChart();
            createDepartmentChart();
            createSeniorityChart();
            createSituationChart();
        }

        // Update all charts based on filtered data
        function updateCharts(data) {
            if (data.length === 0) return;

            // Update Age Chart
            updateAgeChart(data);
            
            // Update Grade Chart
            updateGradeChart(data);
            
            // Update Department Chart
            updateDepartmentChart(data);
            
            // Update Seniority Chart
            updateSeniorityChart(data);
            
            // Update Situation Chart
            updateSituationChart(data);
        }

        // Create Age Distribution Chart
        function createAgeChart() {
            updateAgeChart(filteredEmployees);
        }

        // Update Age Distribution Chart
        function updateAgeChart(data) {
            const ageRanges = {
                '20-30': 0,
                '31-40': 0,
                '41-50': 0,
                '51-60': 0,
                '60+': 0
            };
            
            data.forEach(emp => {
                if (emp.age <= 30) ageRanges['20-30']++;
                else if (emp.age <= 40) ageRanges['31-40']++;
                else if (emp.age <= 50) ageRanges['41-50']++;
                else if (emp.age <= 60) ageRanges['51-60']++;
                else ageRanges['60+']++;
            });
            
            const ctx = document.getElementById('ageChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstances.ageChart) {
                chartInstances.ageChart.destroy();
            }
            
            chartInstances.ageChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['20-30', '31-40', '41-50', '51-60', '60+'],
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: Object.values(ageRanges),
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(230, 126, 34, 0.7)',
                            'rgba(231, 76, 60, 0.7)'
                        ],
                        borderColor: [
                            'rgb(52, 152, 219)',
                            'rgb(46, 204, 113)',
                            'rgb(241, 196, 15)',
                            'rgb(230, 126, 34)',
                            'rgb(231, 76, 60)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Create Grade Distribution Chart
        function createGradeChart() {
            updateGradeChart(filteredEmployees);
        }

        // Update Grade Distribution Chart
        function updateGradeChart(data) {
            const gradeCounts = {};
            gradesData.forEach(grade => {
                gradeCounts[grade] = data.filter(emp => emp.grade === grade).length;
            });
            
            const ctx = document.getElementById('gradeChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstances.gradeChart) {
                chartInstances.gradeChart.destroy();
            }
            
            chartInstances.gradeChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(gradeCounts),
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: Object.values(gradeCounts),
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(230, 126, 34, 0.7)',
                            'rgba(231, 76, 60, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgb(52, 152, 219)',
                            'rgb(46, 204, 113)',
                            'rgb(241, 196, 15)',
                            'rgb(230, 126, 34)',
                            'rgb(231, 76, 60)',
                            'rgb(155, 89, 182)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'left'
                        }
                    }
                }
            });
        }

        // Create Department Distribution Chart
        function createDepartmentChart() {
            updateDepartmentChart(filteredEmployees);
        }

        // Update Department Distribution Chart
        function updateDepartmentChart(data) {
            const departmentCounts = {};
            departmentsData.forEach(dept => {
                departmentCounts[dept] = data.filter(emp => emp.department === dept).length;
            });
            
            const ctx = document.getElementById('departmentChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstances.departmentChart) {
                chartInstances.departmentChart.destroy();
            }
            
            chartInstances.departmentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(departmentCounts),
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: Object.values(departmentCounts),
                        backgroundColor: [
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(230, 126, 34, 0.7)',
                            'rgba(231, 76, 60, 0.7)',
                            'rgba(155, 89, 182, 0.7)'
                        ],
                        borderColor: [
                            'rgb(52, 152, 219)',
                            'rgb(46, 204, 113)',
                            'rgb(241, 196, 15)',
                            'rgb(230, 126, 34)',
                            'rgb(231, 76, 60)',
                            'rgb(155, 89, 182)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'left'
                        }
                    }
                }
            });
        }

        // Create Seniority Distribution Chart
        function createSeniorityChart() {
            updateSeniorityChart(filteredEmployees);
        }

        // Update Seniority Distribution Chart
        function updateSeniorityChart(data) {
            const seniorityRanges = {
                '0-5': 0,
                '6-10': 0,
                '11-15': 0,
                '16-20': 0,
                '20+': 0
            };
            
            data.forEach(emp => {
                if (emp.seniority <= 5) seniorityRanges['0-5']++;
                else if (emp.seniority <= 10) seniorityRanges['6-10']++;
                else if (emp.seniority <= 15) seniorityRanges['11-15']++;
                else if (emp.seniority <= 20) seniorityRanges['16-20']++;
                else seniorityRanges['20+']++;
            });
            
            const ctx = document.getElementById('seniorityChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstances.seniorityChart) {
                chartInstances.seniorityChart.destroy();
            }
            
            chartInstances.seniorityChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['0-5', '6-10', '11-15', '16-20', '20+'],
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: Object.values(seniorityRanges),
                        backgroundColor: 'rgba(230, 126, 34, 0.7)',
                        borderColor: 'rgb(230, 126, 34)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Create Situation Distribution Chart
        function createSituationChart() {
            updateSituationChart(filteredEmployees);
        }

        // Update Situation Distribution Chart
        function updateSituationChart(data) {
            const situationCounts = {
                'مترسم': data.filter(emp => emp.situation === "مترسم").length,
                'متربص': data.filter(emp => emp.situation === "متربص").length,
                'ملحق': data.filter(emp => emp.situation === "ملحق").length
            };
            
            const ctx = document.getElementById('situationChart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (chartInstances.situationChart) {
                chartInstances.situationChart.destroy();
            }
            
            chartInstances.situationChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: Object.keys(situationCounts),
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: Object.values(situationCounts),
                        backgroundColor: [
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(52, 152, 219, 0.7)'
                        ],
                        borderColor: [
                            'rgb(46, 204, 113)',
                            'rgb(241, 196, 15)',
                            'rgb(52, 152, 219)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'left'
                        }
                    }
                }
            });
        }

        // Populate the data table
        function populateTable() {
            updateTable(filteredEmployees);
        }

        // Update the data table with filtered data
        function updateTable(data) {
            const tableBody = document.querySelector('#statsTable tbody');
            tableBody.innerHTML = '';
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="no-data">
                                <i class="fas fa-table fa-2x mb-2"></i>
                                <p>لا توجد بيانات للعرض</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            data.forEach((emp, index) => {
                const row = document.createElement('tr');
                row.className = 'fade-in';
                row.innerHTML = `
                    <td><span class="badge badge-custom badge-primary">${index + 1}</span></td>
                    <td><span class="employee-id">${emp.mecano}</span></td>
                    <td><strong>${emp.name}</strong></td>
                    <td><span class="age-badge">${emp.age} سنة</span></td>
                    <td><span class="seniority-badge">${emp.seniority} سنة</span></td>
                    <td>${emp.grade}</td>
                    <td><span class="situation-badge badge-custom ${getSituationBadgeClass(emp.situation)}">${emp.situation}</span></td>
                    <td><span class="badge badge-custom badge-primary">${emp.department}</span></td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Populate department filter dropdown
        function populateDepartmentFilter() {
            const departmentSelect = document.getElementById('filterDepartment');
            departmentSelect.innerHTML = '<option value="">جميع الوحدات</option>';
            
            departmentsData.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                departmentSelect.appendChild(option);
            });
        }

        // Helper function to get badge class based on situation
        function getSituationBadgeClass(situation) {
            switch(situation) {
                case 'مترسم': return 'badge-success';
                case 'متربص': return 'badge-warning';
                case 'ملحق': return 'badge-info';
                default: return 'badge-primary';
            }
        }

        // Update filtered results message
        function updateFilteredMessage() {
            const visibleRows = filteredEmployees.length;
            const totalRows = employeesData.length;
            
            if (visibleRows === totalRows) {
                document.getElementById('filteredResultsMessage').style.display = 'none';
            } else {
                document.getElementById('resultsCount').textContent = visibleRows;
                document.getElementById('totalCount').textContent = totalRows;
                document.getElementById('filteredResultsMessage').style.display = 'block';
                
                // إضافة تأثير مؤقت
                document.getElementById('filteredResultsMessage').classList.add('filtering-active');
                setTimeout(() => {
                    document.getElementById('filteredResultsMessage').classList.remove('filtering-active');
                }, 1500);
            }
        }

        // Set up event listeners
        function setupEventListeners() {
            // Refresh stats button
            document.getElementById('refreshStats').addEventListener('click', function() {
                loadDataFromDatabase();
            });
            
            // Clear filters button
            document.getElementById('clearFilters').addEventListener('click', function() {
                document.getElementById('filterSituation').value = '';
                document.getElementById('filterDepartment').value = '';
                document.getElementById('filterAge').value = '';
                document.getElementById('filterSeniority').value = '';
                
                filteredEmployees = [...employeesData];
                applyFilters();
                showNotification('تم مسح جميع الفلاتر', 'success');
            });
            
            // Export charts button
            document.getElementById('exportCharts').addEventListener('click', function() {
                showNotification('جاري تحضير التقارير للتصدير...', 'info');
                
                // Simulate download after a delay
                setTimeout(() => {
                    showNotification('تم تصدير التقارير بنجاح', 'success');
                }, 2000);
            });
            
            // Filter change events
            document.getElementById('filterSituation').addEventListener('change', applyFilters);
            document.getElementById('filterDepartment').addEventListener('change', applyFilters);
            document.getElementById('filterAge').addEventListener('change', applyFilters);
            document.getElementById('filterSeniority').addEventListener('change', applyFilters);
        }

        // Apply filters to the data
        function applyFilters() {
            const situationFilter = document.getElementById('filterSituation').value;
            const departmentFilter = document.getElementById('filterDepartment').value;
            const ageFilter = document.getElementById('filterAge').value;
            const seniorityFilter = document.getElementById('filterSeniority').value;
            
            filteredEmployees = [...employeesData];
            
            // Apply situation filter
            if (situationFilter) {
                const situationMap = {
                    '0': 'مترسم',
                    '1': 'متربص',
                    '3': 'ملحق'
                };
                filteredEmployees = filteredEmployees.filter(emp => emp.situation === situationMap[situationFilter]);
            }
            
            // Apply department filter
            if (departmentFilter) {
                filteredEmployees = filteredEmployees.filter(emp => emp.department === departmentFilter);
            }
            
            // Apply age filter
            if (ageFilter) {
                filteredEmployees = filteredEmployees.filter(emp => {
                    if (ageFilter === '20-30') return emp.age >= 20 && emp.age <= 30;
                    if (ageFilter === '31-40') return emp.age >= 31 && emp.age <= 40;
                    if (ageFilter === '41-50') return emp.age >= 41 && emp.age <= 50;
                    if (ageFilter === '51-60') return emp.age >= 51 && emp.age <= 60;
                    if (ageFilter === '60+') return emp.age > 60;
                    return true;
                });
            }
            
            // Apply seniority filter
            if (seniorityFilter) {
                filteredEmployees = filteredEmployees.filter(emp => {
                    if (seniorityFilter === '0-5') return emp.seniority >= 0 && emp.seniority <= 5;
                    if (seniorityFilter === '6-10') return emp.seniority >= 6 && emp.seniority <= 10;
                    if (seniorityFilter === '11-15') return emp.seniority >= 11 && emp.seniority <= 15;
                    if (seniorityFilter === '16-20') return emp.seniority >= 16 && emp.seniority <= 20;
                    if (seniorityFilter === '20+') return emp.seniority > 20;
                    return true;
                });
            }
            
            // Update everything with filtered data
            updateStatsCards(filteredEmployees);
            updateCharts(filteredEmployees);
            updateTable(filteredEmployees);
            updateFilteredMessage();
            
            // Show filtered results message if applicable
            if (filteredEmployees.length !== employeesData.length) {
                showNotification(`تم تطبيق الفلاتر: ${filteredEmployees.length} من ${employeesData.length} نتيجة`, 'info');
            }
        }

        // Show notification
        function showNotification(message, type) {
            // Remove existing notifications
            document.querySelectorAll('.alert').forEach(alert => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            });

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : type === 'info' ? 'info' : 'danger'} mt-3`;
            notification.style.cssText = 'position: fixed; top: 20px; left: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>${message}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }
    </script>
</body>
</html>