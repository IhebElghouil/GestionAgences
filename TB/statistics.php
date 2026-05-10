<!DOCTYPE html>
<html lang="ar" dir="rtl">
<?PHP
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إحصائيات حوادث الشغل والرخص المرضية</title>
    
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SheetJS for Excel export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --medical-color: #9b59b6;
            --initial-accident: #2ecc71;
            --prolongation: #f39c12;
            --relapse: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #ecf0f1;
        }
        
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }
        
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a2530 100%);
            color: white;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
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
        
        .main-content {
            margin-right: 260px;
            padding: 20px;
            min-height: 100vh;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
        
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: none;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: bold;
        }
        
        .stat-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .medical-stat-card {
            border-left: 4px solid var(--medical-color);
        }
        
        .initial-stat-card {
            border-left: 4px solid var(--initial-accident);
        }
        
        .prolongation-stat-card {
            border-left: 4px solid var(--prolongation);
        }
        
        .relapse-stat-card {
            border-left: 4px solid var(--relapse);
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .medical-stat-value {
            color: var(--medical-color);
        }
        
        .initial-stat-value {
            color: var(--initial-accident);
        }
        
        .prolongation-stat-value {
            color: var(--prolongation);
        }
        
        .relapse-stat-value {
            color: var(--relapse);
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .department-badge {
            font-size: 0.8rem;
            margin-left: 5px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--secondary-color);
        }
        
        .medical-nav-pills .nav-link.active {
            background-color: var(--medical-color);
        }
        
        .tab-content {
            padding: 20px 0;
        }
        
        .comparison-card {
            border-top: 4px solid var(--secondary-color);
        }
        
        .medical-comparison-card {
            border-top: 4px solid var(--medical-color);
        }
        
        .nav-tabs .nav-link {
            color: var(--primary-color);
        }
        
        .nav-tabs .nav-link.active {
            font-weight: bold;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .medical-tab {
            border-bottom: 3px solid var(--medical-color);
        }
        
        .export-btn {
            margin-left: 10px;
        }
        
        .medical-bg {
            background-color: rgba(155, 89, 182, 0.1);
        }
        
        .accident-type-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .badge-initial {
            background-color: var(--initial-accident);
        }
        
        .badge-prolongation {
            background-color: var(--prolongation);
        }
        
        .badge-relapse {
            background-color: var(--relapse);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-right: 0;
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
                padding: 10px 15px;
            }
            
            .menu-item:hover, .menu-item.active {
                border-right-color: transparent;
                border-bottom-color: var(--secondary-color);
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
            </div>
            
            <div class="sidebar-menu">
                <a href="stat.php" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>التقارير والإحصائيات</span>
                </a>
                <a href="statistics.php" class="menu-item active">
                    <i class="fas fa-chart-bar"></i>
                    <span>إحصائيات السلامة</span>
                </a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="header-section">
                <h1 class="header-title">التقارير والإحصائيات</h1>
                <div class="header-subtitle">تحليلات شاملة وبيانات إحصائية عن القوى العاملة</div>
            </div>

            <div class="container-fluid">
                <?php  
                $choixdate = isset($_GET['choixdate']) ? $_GET['choixdate'] : date("Y");
                require('connection.php');
                
                // Get work accident stats (type=6) - All accidents
                $totalAccidents = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6"));
                $totalEmployeesAccidents = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(DISTINCT mecano) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6"));
                $totalDaysAccidents = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6"));
                $avgDaysAccidents = mysqli_fetch_row(mysqli_query($connection, "SELECT AVG(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6"));
                
                // Get initial accident stats (type=6 and type2=11)
                $totalInitialAccidents = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=11"));
                $totalEmployeesInitial = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(DISTINCT mecano) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=11"));
                $totalDaysInitial = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=11"));
                $avgDaysInitial = mysqli_fetch_row(mysqli_query($connection, "SELECT AVG(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=11"));
                
                // Get prolongation accident stats (type=6 and type2=9)
                $totalProlongation = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=9"));
                $totalEmployeesProlongation = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(DISTINCT mecano) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=9"));
                $totalDaysProlongation = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=9"));
                $avgDaysProlongation = mysqli_fetch_row(mysqli_query($connection, "SELECT AVG(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=9"));
                
                // Get relapse accident stats (type=6 and type2=10)
                $totalRelapse = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=10"));
                $totalEmployeesRelapse = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(DISTINCT mecano) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=10"));
                $totalDaysRelapse = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=10"));
                $avgDaysRelapse = mysqli_fetch_row(mysqli_query($connection, "SELECT AVG(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=10"));
                
                // Get medical leave stats (type=5)
                $totalMedical = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5"));
                $totalEmployeesMedical = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(DISTINCT mecano) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5"));
                $totalDaysMedical = mysqli_fetch_row(mysqli_query($connection, "SELECT SUM(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5"));
                $avgDaysMedical = mysqli_fetch_row(mysqli_query($connection, "SELECT AVG(DATEDIFF(datefin, datedebut)+1) FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5"));
                
                // Initialize monthly stats arrays
                $months = array(1 => "جانفي", 2 => "فيفري", 3 => "مارس", 4 => "أفريل", 5 => "ماي", 6 => "جوان", 7 => "جويلية", 8 => "أوت", 9 => "سبتمبر", 10 => "أكتوبر", 11 => "نوفمبر", 12 => "ديسمبر");
                
                $monthlyAccidentStats = array();
                $monthlyMedicalStats = array();
                
                foreach($months as $num => $name) {
                    $monthlyAccidentStats[$num] = array(
                        'accidents' => 0, 'initial' => 0, 'prolongation' => 0, 'relapse' => 0,
                        'employees' => 0, 'days' => 0, 'name' => $name
                    );
                    $monthlyMedicalStats[$num] = array(
                        'medical' => 0, 'employees' => 0, 'days' => 0, 'name' => $name
                    );
                }
                
                // Fill monthly accident stats
                $monthlyQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(*) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($monthlyQuery)) { $monthlyAccidentStats[$row['month']]['accidents'] = $row['count']; }
                
                $initialQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(*) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=11 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($initialQuery)) { $monthlyAccidentStats[$row['month']]['initial'] = $row['count']; }
                
                $prolongationQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(*) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=9 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($prolongationQuery)) { $monthlyAccidentStats[$row['month']]['prolongation'] = $row['count']; }
                
                $relapseQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(*) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 AND type2=10 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($relapseQuery)) { $monthlyAccidentStats[$row['month']]['relapse'] = $row['count']; }
                
                $employeesQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(DISTINCT mecano) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($employeesQuery)) { $monthlyAccidentStats[$row['month']]['employees'] = $row['count']; }
                
                $daysQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, SUM(DATEDIFF(datefin, datedebut)+1) as days FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=6 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($daysQuery)) { $monthlyAccidentStats[$row['month']]['days'] = $row['days']; }
                
                // Fill monthly medical stats
                $medicalMonthlyQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(*) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($medicalMonthlyQuery)) { $monthlyMedicalStats[$row['month']]['medical'] = $row['count']; }
                
                $medicalEmployeesQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, COUNT(DISTINCT mecano) as count FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($medicalEmployeesQuery)) { $monthlyMedicalStats[$row['month']]['employees'] = $row['count']; }
                
                $medicalDaysQuery = mysqli_query($connection, "SELECT MONTH(datedebut) as month, SUM(DATEDIFF(datefin, datedebut)+1) as days FROM autreconge WHERE YEAR(datedebut) = $choixdate AND type=5 GROUP BY MONTH(datedebut)");
                while($row = mysqli_fetch_assoc($medicalDaysQuery)) { $monthlyMedicalStats[$row['month']]['days'] = $row['days']; }
                ?>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h2 class="text-center mb-4">تحليل إحصائي - سنة <?php echo $choixdate; ?></h2>
                        
                        <form class="mt-3 mb-4 text-center">
                            <select id="year_selector" class="form-select year-selector d-inline-block" style="width: auto;">
                                <?php
                                $currentYear = date('Y');
                                $maxYear = $currentYear + 2;
                                for ($year = 2011; $year <= $maxYear; $year++) {
                                    $selected = ($choixdate == $year) ? 'selected' : '';
                                    echo "<option value=\"$year\" $selected>$year</option>";
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="statsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="accidents-tab" data-bs-toggle="tab" data-bs-target="#accidents" type="button" role="tab">
                            <i class="fas fa-user-injured me-1"></i> حوادث الشغل
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                            <i class="fas fa-procedures me-1"></i> الرخص المرضية
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="statsTabsContent">
                    <!-- ========== WORK ACCIDENTS TAB ========== -->
                    <div class="tab-pane fade show active" id="accidents" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value"><?php echo $totalAccidents[0]; ?></div>
                                        <div class="stat-label">إجمالي حوادث الشغل</div>
                                        <i class="fas fa-user-injured fa-2x mt-2 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value"><?php echo $totalEmployeesAccidents[0]; ?></div>
                                        <div class="stat-label">عدد المتعرضين للحوادث</div>
                                        <i class="fas fa-users fa-2x mt-2 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value"><?php echo $totalDaysAccidents[0] ?? 0; ?></div>
                                        <div class="stat-label">إجمالي أيام التغيب</div>
                                        <i class="fas fa-calendar-alt fa-2x mt-2 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value"><?php echo round($avgDaysAccidents[0] ?? 0, 1); ?></div>
                                        <div class="stat-label">متوسط أيام التغيب لكل حادث</div>
                                        <i class="fas fa-clock fa-2x mt-2 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card initial-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value initial-stat-value"><?php echo $totalInitialAccidents[0]; ?></div>
                                        <div class="stat-label">حوادث أولية</div>
                                        <i class="fas fa-plus-circle fa-2x mt-2" style="color: var(--initial-accident);"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card prolongation-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value prolongation-stat-value"><?php echo $totalProlongation[0]; ?></div>
                                        <div class="stat-label">تمديدات</div>
                                        <i class="fas fa-redo fa-2x mt-2" style="color: var(--prolongation);"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card relapse-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value relapse-stat-value"><?php echo $totalRelapse[0]; ?></div>
                                        <div class="stat-label">انتكاسات</div>
                                        <i class="fas fa-undo fa-2x mt-2" style="color: var(--relapse);"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#accidentsMonthlyChartTab">حسب الأشهر</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#accidentsDepartmentChartTab">حسب الوحدات</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#accidentsTypeChartTab">حسب نوع الحادث</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#accidentsComparisonTab">مقارنة السنوات</a></li>
                                </ul>
                                <button id="exportAccidentsBtn" class="btn btn-sm btn-success export-btn"><i class="fas fa-file-excel me-1"></i> تصدير إلى Excel</button>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="accidentsMonthlyChartTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">توزيع حوادث الشغل خلال السنة</h5>
                                                <div class="chart-container"><canvas id="accidentsByMonthChart"></canvas></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">أيام التغيب حسب الأشهر</h5>
                                                <div class="chart-container"><canvas id="accidentsDaysByMonthChart"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="accidentsDepartmentChartTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">توزيع الحوادث حسب الوحدات</h5>
                                                <div class="chart-container"><canvas id="accidentsByDepartmentChart"></canvas></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">أيام التغيب حسب الوحدات</h5>
                                                <div class="chart-container"><canvas id="accidentsDaysByDepartmentChart"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="accidentsTypeChartTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">توزيع الحوادث حسب النوع</h5>
                                                <div class="chart-container"><canvas id="accidentsByTypeChart"></canvas></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">أيام التغيب حسب نوع الحادث</h5>
                                                <div class="chart-container"><canvas id="accidentsDaysByTypeChart"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="accidentsComparisonTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card comparison-card h-100">
                                                    <div class="card-body">
                                                        <h5 class="text-center mb-3">مقارنة عدد الحوادث مع السنوات السابقة</h5>
                                                        <div class="chart-container"><canvas id="accidentsYearComparisonChart"></canvas></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card comparison-card h-100">
                                                    <div class="card-body">
                                                        <h5 class="text-center mb-3">أكثر الوحدات تعرضاً للحوادث</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover" id="topDepartmentsAccidentsTable">
                                                                <thead><tr><th>الوحدة</th><th>عدد الحوادث</th><th>حادث أوّلي</th><th>تمديدات</th><th>انتكاسات</th><th>النسبة</th></tr></thead>
                                                                <tbody>
                                                                    <?php
                                                                    $deptQuery = mysqli_query($connection, "SELECT d.depar, COUNT(a.id) as accident_count, SUM(CASE WHEN a.type2 = 11 THEN 1 ELSE 0 END) as initial_count, SUM(CASE WHEN a.type2 = 9 THEN 1 ELSE 0 END) as prolongation_count, SUM(CASE WHEN a.type2 = 10 THEN 1 ELSE 0 END) as relapse_count FROM autreconge a JOIN stuf s ON a.mecano = s.mecano JOIN dep d ON s.dep = d.id WHERE YEAR(a.datedebut) = $choixdate AND a.type=6 GROUP BY d.depar ORDER BY accident_count DESC LIMIT 5");
                                                                    while($dept = mysqli_fetch_assoc($deptQuery)) {
                                                                        $percentage = round(($dept['accident_count'] / $totalAccidents[0]) * 100, 1);
                                                                        echo '<tr><td>'.$dept['depar'].'</td><td><strong>'.$dept['accident_count'].'</strong></td><td><span class="badge badge-initial text-white">'.$dept['initial_count'].'</span></td><td><span class="badge badge-prolongation text-white">'.$dept['prolongation_count'].'</span></td><td><span class="badge badge-relapse text-white">'.$dept['relapse_count'].'</span></td><td><div class="progress"><div class="progress-bar bg-danger" style="width: '.$percentage.'%">'.$percentage.'%</div></div></td></tr>';
                                                                    }
                                                                    ?>
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
                        </div>

                        <!-- Monthly Details Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">تفاصيل الحوادث حسب الأشهر</h5>
                                <button id="exportAccidentsMonthlyBtn" class="btn btn-sm btn-success"><i class="fas fa-file-excel me-1"></i> تصدير إلى Excel</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="monthlyAccidentsTable">
                                        <thead><tr><th>الشهر</th><th>عدد الحوادث</th><th>حادث أوّلي</th><th>تمديدات</th><th>انتكاسات</th><th>عدد المتعرضين</th><th>أيام التغيب</th><th>متوسط أيام التغيب</th><th>النسبة</th></tr></thead>
                                        <tbody>
                                            <?php foreach($monthlyAccidentStats as $month) {
                                                $avgDays = $month['accidents'] > 0 ? round($month['days'] / $month['accidents'], 1) : 0;
                                                $percentage = $totalAccidents[0] > 0 ? round(($month['accidents'] / $totalAccidents[0]) * 100, 1) : 0;
                                                echo '<tr><td>'.$month['name'].'</td><td>'.$month['accidents'].'</td><td><span class="badge badge-initial text-white">'.$month['initial'].'</span></td><td><span class="badge badge-prolongation text-white">'.$month['prolongation'].'</span></td><td><span class="badge badge-relapse text-white">'.$month['relapse'].'</span></td><td>'.$month['employees'].'</td><td>'.$month['days'].'</td><td>'.$avgDays.'</td><td><div class="progress"><div class="progress-bar bg-info" style="width: '.$percentage.'%">'.$percentage.'%</div></div></td></tr>';
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Accident Type Details Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">تفاصيل الحوادث حسب النوع</h5>
                                <button id="exportAccidentsTypeBtn" class="btn btn-sm btn-success"><i class="fas fa-file-excel me-1"></i> تصدير إلى Excel</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="accidentsTypeTable">
                                        <thead><tr><th>نوع الحادث</th><th>عدد الحوادث</th><th>عدد المتعرضين</th><th>أيام التغيب</th><th>متوسط أيام التغيب</th><th>النسبة</th></tr></thead>
                                        <tbody>
                                            <?php
                                            $accidentTypes = array(
                                                'initial' => array('name' => 'حوادث أولية', 'count' => $totalInitialAccidents[0], 'employees' => $totalEmployeesInitial[0], 'days' => $totalDaysInitial[0] ?? 0, 'avg' => round($avgDaysInitial[0] ?? 0, 1), 'badge' => 'badge-initial'),
                                                'prolongation' => array('name' => 'تمديدات', 'count' => $totalProlongation[0], 'employees' => $totalEmployeesProlongation[0], 'days' => $totalDaysProlongation[0] ?? 0, 'avg' => round($avgDaysProlongation[0] ?? 0, 1), 'badge' => 'badge-prolongation'),
                                                'relapse' => array('name' => 'انتكاسات', 'count' => $totalRelapse[0], 'employees' => $totalEmployeesRelapse[0], 'days' => $totalDaysRelapse[0] ?? 0, 'avg' => round($avgDaysRelapse[0] ?? 0, 1), 'badge' => 'badge-relapse')
                                            );
                                            foreach($accidentTypes as $type) {
                                                $percentage = $totalAccidents[0] > 0 ? round(($type['count'] / $totalAccidents[0]) * 100, 1) : 0;
                                                echo '<tr><td><span class="badge '.$type['badge'].' text-white">'.$type['name'].'</span></td><td>'.$type['count'].'</td><td>'.$type['employees'].'</td><td>'.$type['days'].'</td><td>'.$type['avg'].'</td><td><div class="progress"><div class="progress-bar '.$type['badge'].'" style="width: '.$percentage.'%">'.$percentage.'%</div></div></td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== MEDICAL LEAVES TAB ========== -->
                    <div class="tab-pane fade" id="medical" role="tabpanel">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card medical-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value medical-stat-value"><?php echo $totalMedical[0]; ?></div>
                                        <div class="stat-label">إجمالي الرخص المرضية</div>
                                        <i class="fas fa-procedures fa-2x mt-2" style="color: var(--medical-color);"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card medical-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value medical-stat-value"><?php echo $totalEmployeesMedical[0]; ?></div>
                                        <div class="stat-label">عدد الموظفين المرضى</div>
                                        <i class="fas fa-users fa-2x mt-2" style="color: var(--medical-color);"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card medical-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value medical-stat-value"><?php echo $totalDaysMedical[0] ?? 0; ?></div>
                                        <div class="stat-label">إجمالي أيام التغيب</div>
                                        <i class="fas fa-calendar-alt fa-2x mt-2" style="color: var(--medical-color);"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card medical-stat-card h-100">
                                    <div class="card-body text-center">
                                        <div class="stat-value medical-stat-value"><?php echo round($avgDaysMedical[0] ?? 0, 1); ?></div>
                                        <div class="stat-label">متوسط أيام التغيب لكل رخصة</div>
                                        <i class="fas fa-clock fa-2x mt-2" style="color: var(--medical-color);"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Charts -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--medical-color);">
                                <ul class="nav nav-pills card-header-pills">
                                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#medicalMonthlyChartTab">حسب الأشهر</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#medicalDepartmentChartTab">حسب الوحدات</a></li>
                                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#medicalComparisonTab">مقارنة السنوات</a></li>
                                </ul>
                                <button id="exportMedicalBtn" class="btn btn-sm btn-success"><i class="fas fa-file-excel me-1"></i> تصدير إلى Excel</button>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="medicalMonthlyChartTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">توزيع الرخص المرضية خلال السنة</h5>
                                                <div class="chart-container"><canvas id="medicalByMonthChart"></canvas></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">أيام التغيب حسب الأشهر</h5>
                                                <div class="chart-container"><canvas id="medicalDaysByMonthChart"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="medicalDepartmentChartTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">توزيع الرخص المرضية حسب الوحدات</h5>
                                                <div class="chart-container"><canvas id="medicalByDepartmentChart"></canvas></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="text-center mb-3">أيام التغيب حسب الوحدات</h5>
                                                <div class="chart-container"><canvas id="medicalDaysByDepartmentChart"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="medicalComparisonTab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card medical-comparison-card h-100">
                                                    <div class="card-body">
                                                        <h5 class="text-center mb-3">مقارنة عدد الرخص المرضية مع السنوات السابقة</h5>
                                                        <div class="chart-container"><canvas id="medicalYearComparisonChart"></canvas></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card medical-comparison-card h-100">
                                                    <div class="card-body">
                                                        <h5 class="text-center mb-3">أكثر الوحدات تغيباً بسبب المرض</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-hover" id="topDepartmentsMedicalTable">
                                                                <thead><tr><th>الوحدة</th><th>عدد الرخص</th><th>عدد الموظفين</th><th>أيام التغيب</th><th>متوسط الأيام</th><th>النسبة</th></tr></thead>
                                                                <tbody>
                                                                    <?php
                                                                    $deptMedicalQuery = mysqli_query($connection, "SELECT d.depar, COUNT(a.id) as medical_count, COUNT(DISTINCT a.mecano) as employees, SUM(DATEDIFF(a.datefin, a.datedebut)+1) as days FROM autreconge a JOIN stuf s ON a.mecano = s.mecano JOIN dep d ON s.dep = d.id WHERE YEAR(a.datedebut) = $choixdate AND a.type=5 GROUP BY d.depar ORDER BY medical_count DESC LIMIT 5");
                                                                    while($dept = mysqli_fetch_assoc($deptMedicalQuery)) {
                                                                        $percentage = $totalMedical[0] > 0 ? round(($dept['medical_count'] / $totalMedical[0]) * 100, 1) : 0;
                                                                        $avgDays = $dept['medical_count'] > 0 ? round($dept['days'] / $dept['medical_count'], 1) : 0;
                                                                        echo '<tr><td>'.$dept['depar'].'</td><td><strong>'.$dept['medical_count'].'</strong></td><td>'.$dept['employees'].'</td><td>'.$dept['days'].'</td><td>'.$avgDays.'</td><td><div class="progress"><div class="progress-bar" style="background-color: var(--medical-color); width: '.$percentage.'%">'.$percentage.'%</div></div></td></tr>';
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            �
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Monthly Details Table -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: var(--medical-color);">
                                <h5 class="mb-0">تفاصيل الرخص المرضية حسب الأشهر</h5>
                                <button id="exportMedicalMonthlyBtn" class="btn btn-sm btn-success"><i class="fas fa-file-excel me-1"></i> تصدير إلى Excel</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="monthlyMedicalTable">
                                        <thead><tr><th>الشهر</th><th>عدد الرخص</th><th>عدد الموظفين</th><th>أيام التغيب</th><th>متوسط أيام التغيب</th><th>النسبة من إجمالي الرخص</th></tr></thead>
                                        <tbody>
                                            <?php foreach($monthlyMedicalStats as $month) {
                                                $avgDays = $month['medical'] > 0 ? round($month['days'] / $month['medical'], 1) : 0;
                                                $percentage = $totalMedical[0] > 0 ? round(($month['medical'] / $totalMedical[0]) * 100, 1) : 0;
                                                echo '<tr><td>'.$month['name'].'</td><td>'.$month['medical'].'</td><td>'.$month['employees'].'</td><td>'.$month['days'].'</td><td>'.$avgDays.'</td><td><div class="progress"><div class="progress-bar" style="background-color: var(--medical-color); width: '.$percentage.'%">'.$percentage.'%</div></div></td></tr>';
                                            } ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#year_selector').change(function() {
            window.location = 'statistics.php?choixdate=' + $(this).val();
        });
        
        function exportToExcel(tableID, fileName) {
            var table = document.getElementById(tableID);
            if (table) {
                var wb = XLSX.utils.book_new();
                var ws = XLSX.utils.table_to_sheet(table);
                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                XLSX.writeFile(wb, fileName + '.xlsx');
            }
        }
        
        // Export functions
        $('#exportAccidentsBtn').click(function() {
            var wb = XLSX.utils.book_new();
            var summaryData = [["إجمالي حوادث الشغل", <?php echo $totalAccidents[0]; ?>], ["عدد المتعرضين", <?php echo $totalEmployeesAccidents[0]; ?>], ["إجمالي أيام التغيب", <?php echo $totalDaysAccidents[0] ?? 0; ?>], ["متوسط أيام التغيب", <?php echo round($avgDaysAccidents[0] ?? 0, 1); ?>], ["حوادث أولية", <?php echo $totalInitialAccidents[0]; ?>], ["تمديدات", <?php echo $totalProlongation[0]; ?>], ["انتكاسات", <?php echo $totalRelapse[0]; ?>]];
            var wsSummary = XLSX.utils.aoa_to_sheet([["إحصائيات حوادث الشغل", ""], ...summaryData]);
            XLSX.utils.book_append_sheet(wb, wsSummary, "ملخص");
            var monthlyTable = document.getElementById('monthlyAccidentsTable');
            if (monthlyTable) XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(monthlyTable), "حسب الأشهر");
            var typeTable = document.getElementById('accidentsTypeTable');
            if (typeTable) XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(typeTable), "حسب النوع");
            var deptTable = document.getElementById('topDepartmentsAccidentsTable');
            if (deptTable) XLSX.utils.book_append_sheet(wb, XLSX.utils.table_to_sheet(deptTable), "حسب الوحدات");
            XLSX.writeFile(wb, 'إحصائيات حوادث الشغل - سنة <?php echo $choixdate; ?>.xlsx');
        });
        
        $('#exportAccidentsMonthlyBtn').click(function() { exportToExcel('monthlyAccidentsTable', 'تفاصيل حوادث الشغل الشهرية - سنة <?php echo $choixdate; ?>'); });
        $('#exportAccidentsTypeBtn').click(function() { exportToExcel('accidentsTypeTable', 'تفاصيل حوادث الشغل حسب النوع - سنة <?php echo $choixdate; ?>'); });
        $('#exportMedicalBtn').click(function() { exportToExcel('monthlyMedicalTable', 'إحصائيات الرخص المرضية - سنة <?php echo $choixdate; ?>'); });
        $('#exportMedicalMonthlyBtn').click(function() { exportToExcel('monthlyMedicalTable', 'تفاصيل الرخص المرضية الشهرية - سنة <?php echo $choixdate; ?>'); });
        
        // Chart data
        const months = ['جانفي', 'فيفري', 'مارس', 'أفريل', 'ماي', 'جوان', 'جويلية', 'أوت', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        
        const monthlyAccidentsData = [<?php foreach($monthlyAccidentStats as $m) echo $m['accidents'].', '; ?>];
        const monthlyAccidentDaysData = [<?php foreach($monthlyAccidentStats as $m) echo ($m['days'] ?? 0).', '; ?>];
        const monthlyMedicalData = [<?php foreach($monthlyMedicalStats as $m) echo $m['medical'].', '; ?>];
        const monthlyMedicalDaysData = [<?php foreach($monthlyMedicalStats as $m) echo ($m['days'] ?? 0).', '; ?>];
        
        <?php
        $deptChartQuery = mysqli_query($connection, "SELECT d.depar, COUNT(a.id) as accident_count, SUM(DATEDIFF(a.datefin, a.datedebut)+1) as days FROM autreconge a JOIN stuf s ON a.mecano = s.mecano JOIN dep d ON s.dep = d.id WHERE YEAR(a.datedebut) = $choixdate AND a.type=6 GROUP BY d.depar ORDER BY accident_count DESC");
        $deptLabels = []; $deptAccidents = []; $deptDays = [];
        while($row = mysqli_fetch_assoc($deptChartQuery)) { $deptLabels[] = "'".addslashes($row['depar'])."'"; $deptAccidents[] = $row['accident_count']; $deptDays[] = $row['days']; }
        
        $medicalDeptQuery = mysqli_query($connection, "SELECT d.depar, COUNT(a.id) as medical_count, SUM(DATEDIFF(a.datefin, a.datedebut)+1) as days FROM autreconge a JOIN stuf s ON a.mecano = s.mecano JOIN dep d ON s.dep = d.id WHERE YEAR(a.datedebut) = $choixdate AND a.type=5 GROUP BY d.depar ORDER BY medical_count DESC");
        $medicalDeptLabels = []; $medicalDeptCounts = []; $medicalDeptDays = [];
        while($row = mysqli_fetch_assoc($medicalDeptQuery)) { $medicalDeptLabels[] = "'".addslashes($row['depar'])."'"; $medicalDeptCounts[] = $row['medical_count']; $medicalDeptDays[] = $row['days']; }
        
        $yearCompQuery = mysqli_query($connection, "SELECT YEAR(datedebut) as year FROM autreconge WHERE type=6 GROUP BY YEAR(datedebut) ORDER BY YEAR(datedebut) DESC LIMIT 5");
        $yearLabels = []; $yearInitial = []; $yearProlongation = []; $yearRelapse = [];
        while($year = mysqli_fetch_assoc($yearCompQuery)) {
            $yearLabels[] = $year['year'];
            $init = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = ".$year['year']." AND type=6 AND type2=11"));
            $prol = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = ".$year['year']." AND type=6 AND type2=9"));
            $rel = mysqli_fetch_row(mysqli_query($connection, "SELECT COUNT(*) FROM autreconge WHERE YEAR(datedebut) = ".$year['year']." AND type=6 AND type2=10"));
            $yearInitial[] = $init[0]; $yearProlongation[] = $prol[0]; $yearRelapse[] = $rel[0];
        }
        
        $medicalYearQuery = mysqli_query($connection, "SELECT YEAR(datedebut) as year, COUNT(*) as count FROM autreconge WHERE type=5 GROUP BY YEAR(datedebut) ORDER BY YEAR(datedebut) DESC LIMIT 5");
        $medicalYearLabels = []; $medicalYearCounts = [];
        while($year = mysqli_fetch_assoc($medicalYearQuery)) { $medicalYearLabels[] = $year['year']; $medicalYearCounts[] = $year['count']; }
        ?>
        
        const accidentsDepartmentData = { labels: [<?php echo implode(', ', $deptLabels); ?>], accidents: [<?php echo implode(', ', $deptAccidents); ?>], days: [<?php echo implode(', ', $deptDays); ?>] };
        const medicalDepartmentData = { labels: [<?php echo implode(', ', $medicalDeptLabels); ?>], medical: [<?php echo implode(', ', $medicalDeptCounts); ?>], days: [<?php echo implode(', ', $medicalDeptDays); ?>] };
        const accidentsComparisonData = { labels: [<?php echo "'".implode("', '", $yearLabels)."'"; ?>], initial: [<?php echo implode(', ', $yearInitial); ?>], prolongation: [<?php echo implode(', ', $yearProlongation); ?>], relapse: [<?php echo implode(', ', $yearRelapse); ?>] };
        const medicalComparisonData = { labels: [<?php echo "'".implode("', '", $medicalYearLabels)."'"; ?>], counts: [<?php echo implode(', ', $medicalYearCounts); ?>] };
        const accidentTypeData = { labels: ['حوادث أولية', 'تمديدات', 'انتكاسات'], counts: [<?php echo $totalInitialAccidents[0]; ?>, <?php echo $totalProlongation[0]; ?>, <?php echo $totalRelapse[0]; ?>], days: [<?php echo $totalDaysInitial[0] ?? 0; ?>, <?php echo $totalDaysProlongation[0] ?? 0; ?>, <?php echo $totalDaysRelapse[0] ?? 0; ?>] };
        
        function createCharts() {
            if(document.getElementById('accidentsByMonthChart')) new Chart(document.getElementById('accidentsByMonthChart'), { type: 'bar', data: { labels: months, datasets: [{ label: 'عدد حوادث الشغل', data: monthlyAccidentsData, backgroundColor: 'rgba(52, 152, 219, 0.7)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
            if(document.getElementById('accidentsDaysByMonthChart')) new Chart(document.getElementById('accidentsDaysByMonthChart'), { type: 'line', data: { labels: months, datasets: [{ label: 'أيام التغيب', data: monthlyAccidentDaysData, backgroundColor: 'rgba(231, 76, 60, 0.2)', borderColor: 'rgba(231, 76, 60, 1)', tension: 0.3, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
            if(document.getElementById('accidentsByDepartmentChart') && accidentsDepartmentData.accidents.length) new Chart(document.getElementById('accidentsByDepartmentChart'), { type: 'doughnut', data: { labels: accidentsDepartmentData.labels, datasets: [{ data: accidentsDepartmentData.accidents, backgroundColor: ['rgba(52,152,219,0.7)','rgba(155,89,182,0.7)','rgba(26,188,156,0.7)','rgba(241,196,15,0.7)','rgba(230,126,34,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } });
            if(document.getElementById('accidentsDaysByDepartmentChart') && accidentsDepartmentData.days.length) new Chart(document.getElementById('accidentsDaysByDepartmentChart'), { type: 'polarArea', data: { labels: accidentsDepartmentData.labels, datasets: [{ data: accidentsDepartmentData.days, backgroundColor: ['rgba(52,152,219,0.7)','rgba(155,89,182,0.7)','rgba(26,188,156,0.7)','rgba(241,196,15,0.7)','rgba(230,126,34,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } });
            if(document.getElementById('accidentsByTypeChart')) new Chart(document.getElementById('accidentsByTypeChart'), { type: 'pie', data: { labels: accidentTypeData.labels, datasets: [{ data: accidentTypeData.counts, backgroundColor: ['rgba(46,204,113,0.7)','rgba(243,156,18,0.7)','rgba(231,76,60,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } });
            if(document.getElementById('accidentsDaysByTypeChart')) new Chart(document.getElementById('accidentsDaysByTypeChart'), { type: 'bar', data: { labels: accidentTypeData.labels, datasets: [{ label: 'أيام التغيب', data: accidentTypeData.days, backgroundColor: ['rgba(46,204,113,0.7)','rgba(243,156,18,0.7)','rgba(231,76,60,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
            if(document.getElementById('accidentsYearComparisonChart') && accidentsComparisonData.labels.length) new Chart(document.getElementById('accidentsYearComparisonChart'), { type: 'bar', data: { labels: accidentsComparisonData.labels, datasets: [{ label: 'حوادث أولية', data: accidentsComparisonData.initial, backgroundColor: 'rgba(46,204,113,0.7)' }, { label: 'تمديدات', data: accidentsComparisonData.prolongation, backgroundColor: 'rgba(243,156,18,0.7)' }, { label: 'انتكاسات', data: accidentsComparisonData.relapse, backgroundColor: 'rgba(231,76,60,0.7)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, stacked: true }, x: { stacked: true } }, plugins: { legend: { position: 'bottom' } } } });
            if(document.getElementById('medicalByMonthChart')) new Chart(document.getElementById('medicalByMonthChart'), { type: 'bar', data: { labels: months, datasets: [{ label: 'عدد الرخص المرضية', data: monthlyMedicalData, backgroundColor: 'rgba(155, 89, 182, 0.7)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
            if(document.getElementById('medicalDaysByMonthChart')) new Chart(document.getElementById('medicalDaysByMonthChart'), { type: 'line', data: { labels: months, datasets: [{ label: 'أيام التغيب', data: monthlyMedicalDaysData, backgroundColor: 'rgba(155, 89, 182, 0.2)', borderColor: 'rgba(155, 89, 182, 1)', tension: 0.3, fill: true }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
            if(document.getElementById('medicalByDepartmentChart') && medicalDepartmentData.medical.length) new Chart(document.getElementById('medicalByDepartmentChart'), { type: 'doughnut', data: { labels: medicalDepartmentData.labels, datasets: [{ data: medicalDepartmentData.medical, backgroundColor: ['rgba(155,89,182,0.7)','rgba(52,152,219,0.7)','rgba(26,188,156,0.7)','rgba(241,196,15,0.7)','rgba(230,126,34,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } });
            if(document.getElementById('medicalDaysByDepartmentChart') && medicalDepartmentData.days.length) new Chart(document.getElementById('medicalDaysByDepartmentChart'), { type: 'polarArea', data: { labels: medicalDepartmentData.labels, datasets: [{ data: medicalDepartmentData.days, backgroundColor: ['rgba(155,89,182,0.7)','rgba(52,152,219,0.7)','rgba(26,188,156,0.7)','rgba(241,196,15,0.7)','rgba(230,126,34,0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'left' } } } });
            if(document.getElementById('medicalYearComparisonChart') && medicalComparisonData.labels.length) new Chart(document.getElementById('medicalYearComparisonChart'), { type: 'bar', data: { labels: medicalComparisonData.labels, datasets: [{ label: 'عدد الرخص المرضية', data: medicalComparisonData.counts, backgroundColor: 'rgba(155, 89, 182, 0.7)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } } });
        }
        
        createCharts();
    });
    </script>
</body>
</html>