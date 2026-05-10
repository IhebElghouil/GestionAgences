<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموارد البشرية</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.css">
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="JS/xlsx.full.min.js"></script>
    
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
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color) 0%, transparent 100%);
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .card-icon.primary { background: rgba(52, 152, 219, 0.1); color: var(--secondary-color); }
        .card-icon.success { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .card-icon.warning { background: rgba(243, 156, 18, 0.1); color: var(--warning-color); }
        .card-icon.info { background: rgba(23, 162, 184, 0.1); color: var(--info-color); }
        
        .card-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .card-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .card-change {
            font-size: 0.85rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .card-change.positive { color: var(--success-color); }
        .card-change.negative { color: var(--accent-color); }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
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
        
        /* Recent Activity */
        .activity-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }
        
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.2rem;
        }
        
        .activity-icon.success { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .activity-icon.warning { background: rgba(243, 156, 18, 0.1); color: var(--warning-color); }
        .activity-icon.info { background: rgba(23, 162, 184, 0.1); color: var(--info-color); }
        .activity-icon.primary { background: rgba(52, 152, 219, 0.1); color: var(--secondary-color); }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .activity-time {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .action-btn {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 15px;
        }
        
        .action-icon.primary { background: rgba(52, 152, 219, 0.1); color: var(--secondary-color); }
        .action-icon.success { background: rgba(39, 174, 96, 0.1); color: var(--success-color); }
        .action-icon.warning { background: rgba(243, 156, 18, 0.1); color: var(--warning-color); }
        .action-icon.info { background: rgba(23, 162, 184, 0.1); color: var(--info-color); }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .action-desc {
            font-size: 0.85rem;
            color: #6c757d;
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
        @media (max-width: 992px) {
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
        }
        
        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 576px) {
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">HR Dashboard</div>
                <div class="sidebar-subtitle">نظام إدارة الموارد البشرية</div>
            </div>
            
            <div class="sidebar-menu">
                <a href="#" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>لوحة التحكم</span>
                </a>
                <a href="employee_management.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>إدارة الموظفين</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>الإجازات والغياب</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>التقارير والإحصائيات</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-user-clock"></i>
                    <span>الحضور والانصراف</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>الرواتب والمستحقات</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header-section">
                <h1 class="header-title">لوحة تحكم الموارد البشرية</h1>
                <div class="header-subtitle">نظرة شاملة على القوى العاملة والأنشطة</div>
            </div>
            
            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-value" id="totalEmployees">0</div>
                    <div class="card-label">إجمالي الموظفين</div>
                    <div class="card-change positive">
                        <i class="fas fa-sync-alt"></i>
                        <span>بيانات حية من قاعدة البيانات</span>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-value" id="permanentEmployees">0</div>
                    <div class="card-label">موظفين دائمين</div>
                    <div class="card-change positive">
                        <i class="fas fa-sync-alt"></i>
                        <span>بيانات حية من قاعدة البيانات</span>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-icon warning">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="card-value" id="traineeEmployees">0</div>
                    <div class="card-label">متدربين</div>
                    <div class="card-change positive">
                        <i class="fas fa-sync-alt"></i>
                        <span>بيانات حية من قاعدة البيانات</span>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-icon info">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="card-value" id="attachedEmployees">0</div>
                    <div class="card-label">ملحقين</div>
                    <div class="card-change positive">
                        <i class="fas fa-sync-alt"></i>
                        <span>بيانات حية من قاعدة البيانات</span>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">توزيع الموظفين حسب الإدارات</div>
                        <div class="chart-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshCharts()">
                                <i class="fas fa-sync-alt"></i> تحديث
                            </button>
                        </div>
                    </div>
                    <div class="loading-spinner" id="departmentChartLoading">
                        <div class="spinner"></div>
                    </div>
                    <canvas id="departmentChart" height="300" style="display: none;"></canvas>
                </div>
                
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">توزيع الموظفين حسب الوضعية</div>
                    </div>
                    <div class="loading-spinner" id="statusChartLoading">
                        <div class="spinner"></div>
                    </div>
                    <canvas id="statusChart" height="300" style="display: none;"></canvas>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="action-btn" onclick="loadAddEmployeeModal()">
                    <div class="action-icon primary">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-title">إضافة موظف جديد</div>
                    <div class="action-desc">إضافة موظف جديد إلى النظام</div>
                </button>
                
                <button class="action-btn" onclick="exportToExcel()">
                    <div class="action-icon success">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <div class="action-title">تصدير التقارير</div>
                    <div class="action-desc">تصدير بيانات الموظفين إلى Excel</div>
                </button>
                
                <button class="action-btn">
                    <div class="action-icon warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="action-title">إدارة الإجازات</div>
                    <div class="action-desc">الموافقة على طلبات الإجازات</div>
                </button>
                
                <button class="action-btn">
                    <div class="action-icon info">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="action-title">تقارير التحليلات</div>
                    <div class="action-desc">عرض تحليلات مفصلة عن القوى العاملة</div>
                </button>
            </div>
            
            <!-- Recent Activity -->
            <div class="activity-section">
                <div class="chart-header">
                    <div class="chart-title">آخر الأنشطة</div>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadRecentActivity()">
                        <i class="fas fa-sync-alt"></i> تحديث
                    </button>
                </div>
                
                <div class="activity-list" id="recentActivity">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editModalContent">
                <!-- AJAX content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // تهيئة البيانات والإحصائيات من قاعدة البيانات
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            loadChartsData();
            loadRecentActivity();
        });

        // دالة لجلب بيانات لوحة التحكم
        function loadDashboardData() {
            $.ajax({
                url: 'get_dashboard_data.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // تحديث بطاقات الإحصائيات
                        document.getElementById('totalEmployees').textContent = data.stats.totalEmployees;
                        document.getElementById('permanentEmployees').textContent = data.stats.permanentEmployees;
                        document.getElementById('traineeEmployees').textContent = data.stats.traineeEmployees;
                        document.getElementById('attachedEmployees').textContent = data.stats.attachedEmployees;
                    } else {
                        console.error('Error loading dashboard data:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // استخدام بيانات افتراضية في حالة الخطأ
                    document.getElementById('totalEmployees').textContent = '0';
                    document.getElementById('permanentEmployees').textContent = '0';
                    document.getElementById('traineeEmployees').textContent = '0';
                    document.getElementById('attachedEmployees').textContent = '0';
                }
            });
        }

        // دالة لجلب بيانات المخططات
        function loadChartsData() {
            $.ajax({
                url: 'get_charts_data.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // إخفاء مؤشرات التحميل
                        document.getElementById('departmentChartLoading').style.display = 'none';
                        document.getElementById('statusChartLoading').style.display = 'none';
                        
                        // إظهار المخططات
                        document.getElementById('departmentChart').style.display = 'block';
                        document.getElementById('statusChart').style.display = 'block';
                        
                        // إنشاء مخطط توزيع الموظفين حسب الإدارات
                        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
                        new Chart(departmentCtx, {
                            type: 'bar',
                            data: data.departmentData,
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
                        
                        // إنشاء مخطط توزيع الموظفين حسب الوضعية
                        const statusCtx = document.getElementById('statusChart').getContext('2d');
                        new Chart(statusCtx, {
                            type: 'doughnut',
                            data: data.statusData,
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
                        console.error('Error loading charts data:', data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        // دالة لجلب الأنشطة الحديثة
        function loadRecentActivity() {
            $.ajax({
                url: 'get_recent_activity.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const activityList = document.getElementById('recentActivity');
                    activityList.innerHTML = '';
                    
                    if (data.success && data.activities.length > 0) {
                        data.activities.forEach(activity => {
                            const activityItem = document.createElement('div');
                            activityItem.className = 'activity-item';
                            
                            activityItem.innerHTML = `
                                <div class="activity-icon ${activity.type}">
                                    <i class="fas fa-${activity.icon}"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">${activity.title}</div>
                                    <div class="activity-desc">${activity.description}</div>
                                    <div class="activity-time">${activity.time}</div>
                                </div>
                            `;
                            
                            activityList.appendChild(activityItem);
                        });
                    } else {
                        activityList.innerHTML = '<div class="text-center text-muted py-3">لا توجد أنشطة حديثة</div>';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    document.getElementById('recentActivity').innerHTML = '<div class="text-center text-danger py-3">خطأ في تحميل البيانات</div>';
                }
            });
        }

        // دالة لتحديث المخططات
        function refreshCharts() {
            document.getElementById('departmentChartLoading').style.display = 'flex';
            document.getElementById('statusChartLoading').style.display = 'flex';
            document.getElementById('departmentChart').style.display = 'none';
            document.getElementById('statusChart').style.display = 'none';
            
            loadChartsData();
        }

        // دالة لتحميل نافذة إضافة موظف
        function loadAddEmployeeModal() {
            $('#editModalContent').load('Ajout_Emplyee.php', function() {
                $('#editModal').modal('show');
            });
        }

        // دالة لتصدير البيانات إلى Excel
        function exportToExcel() {
            // استخدام الدالة الموجودة في الكود الأصلي
            exportTableToExcel('myTable', 'ListePersonels.xlsx');
        }
    </script>
</body>
</html>