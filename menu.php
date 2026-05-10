<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الموارد البشرية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            transition: all 0.3s ease;
        }

        body.menu-collapsed {
            padding-left: 80px;
        }

        /* Toggle Button */
        .menu-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--gradient-primary);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-toggle:hover {
            transform: scale(1.1);
            box-shadow: var(--hover-shadow);
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
            transition: all 0.3s ease;
        }

        body.menu-collapsed .header-section {
            margin-right: 60px;
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
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .header-title {
            font-size: 2rem;
        }

        .header-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .header-subtitle {
            font-size: 1rem;
        }

        .logout-btn {
            position: absolute;
            left: 25px;
            top: 25px;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .admin-btn {
            position: absolute;
            left: 25px;
            top: 80px;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .menu-container {
            margin-right: 60px;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }

        .menu-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
            border: none;
            position: relative;
            height: 160px;
            display: flex;
            flex-direction: column;
        }

        body.menu-collapsed .menu-card {
            height: 140px;
        }

        .menu-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-success);
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--hover-shadow);
        }

        .menu-card a {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 20px;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .menu-card a {
            padding: 15px;
        }

        .menu-card:hover a {
            color: var(--secondary-color);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            background: var(--gradient-success);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .card-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .menu-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
            background: var(--gradient-primary);
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
            text-align: center;
            margin: 0;
            line-height: 1.4;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .card-title {
            font-size: 0.85rem;
        }

        /* Category Colors */
        .card-personnel { border-top: 4px solid #3498db; }
        .card-personnel .card-icon { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }

        .card-documents { border-top: 4px solid #9b59b6; }
        .card-documents .card-icon { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }

        .card-leaves { border-top: 4px solid #27ae60; }
        .card-leaves .card-icon { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }

        .card-medical { border-top: 4px solid #e74c3c; }
        .card-medical .card-icon { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }

        .card-admin { border-top: 4px solid #f39c12; }
        .card-admin .card-icon { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }

        .card-reports { border-top: 4px solid #17a2b8; }
        .card-reports .card-icon { background: linear-gradient(135deg, #17a2b8 0%, #138d75 100%); }

        /* Mini Menu Sidebar */
        .mini-sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transition: all 0.3s ease;
            padding: 80px 20px 20px;
            overflow-y: auto;
        }

        .mini-sidebar.active {
            right: 0;
        }

        /* Category styling for collapsible sections */
        .mini-menu-category {
            font-weight: 700;
            color: var(--primary-color);
            margin: 20px 0 10px;
            padding: 10px 10px;
            border-right: 3px solid var(--secondary-color);
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(to left, transparent, var(--light-bg));
            border-radius: 0 5px 5px 0;
            transition: all 0.3s ease;
        }

        .mini-menu-category:hover {
            background: linear-gradient(to left, transparent, #e9ecef);
            padding-right: 15px;
        }

        .mini-menu-category i {
            transition: transform 0.3s ease;
            color: var(--secondary-color);
        }

        .mini-menu-category.collapsed i {
            transform: rotate(-90deg);
        }

        .category-items {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .category-items.collapsed {
            display: none;
        }

        .mini-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--primary-color);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .mini-menu-item:hover {
            background: var(--light-bg);
            transform: translateX(-5px);
            color: var(--secondary-color);
        }

        .mini-menu-item i {
            width: 20px;
            text-align: center;
        }

        .mini-sidebar-footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 2px solid var(--light-bg);
        }

        .mini-logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            width: 100%;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 10px;
        }

        .mini-logout-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            color: white;
        }

        .mini-admin-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: var(--light-bg);
            color: var(--primary-color);
            border: none;
            border-radius: 10px;
            width: 100%;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .mini-admin-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateX(-5px);
        }

        /* Quick Access Bar */
        .quick-access-bar {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            background: white;
            border-radius: 15px 0 0 15px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px 5px;
            z-index: 998;
            display: none;
            flex-direction: column;
            gap: 10px;
        }

        body.menu-collapsed .quick-access-bar {
            display: flex;
        }

        .quick-access-item {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .quick-access-item:hover {
            transform: scale(1.1);
        }

        .quick-access-item:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            right: 50px;
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        /* Badge for new features */
        .badge-new {
            position: absolute;
            top: 10px;
            left: 10px;
            background: var(--gradient-danger);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Search and filter section */
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            max-width: 1400px;
            margin: 0 auto 30px;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .search-section {
            margin-right: 60px;
        }

        .search-input {
            border-radius: 25px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Quick stats */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            max-width: 1400px;
            margin: 0 auto 30px;
            transition: all 0.3s ease;
        }

        body.menu-collapsed .stats-section {
            margin-right: 60px;
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

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .menu-container {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            
            .menu-card {
                height: 150px;
            }
            
            .card-title {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }
            
            .header-subtitle {
                font-size: 1rem;
            }
            
            .menu-container {
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
                gap: 15px;
                padding: 15px;
            }
            
            .menu-card {
                height: 140px;
            }
            
            .menu-card a {
                padding: 15px;
            }
            
            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
            
            .card-title {
                font-size: 0.85rem;
            }
            
            .logout-btn, .admin-btn {
                position: relative;
                left: auto;
                top: auto;
                margin-bottom: 15px;
                justify-content: center;
            }

            body.menu-collapsed {
                padding-left: 20px;
            }

            body.menu-collapsed .menu-container,
            body.menu-collapsed .search-section,
            body.menu-collapsed .stats-section,
            body.menu-collapsed .header-section {
                margin-right: 20px;
            }

            .quick-access-bar {
                display: none !important;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .menu-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            
            .menu-card {
                height: 130px;
            }
            
            .menu-card a {
                padding: 12px;
            }
            
            .card-title {
                font-size: 0.8rem;
            }

            .menu-toggle {
                top: 10px;
                right: 10px;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }

        /* Loading animation */
        .menu-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Staggered animation for cards */
        .menu-card:nth-child(1) { animation-delay: 0.1s; }
        .menu-card:nth-child(2) { animation-delay: 0.2s; }
        .menu-card:nth-child(3) { animation-delay: 0.3s; }
        .menu-card:nth-child(4) { animation-delay: 0.4s; }
        .menu-card:nth-child(5) { animation-delay: 0.5s; }
        .menu-card:nth-child(6) { animation-delay: 0.6s; }
        .menu-card:nth-child(7) { animation-delay: 0.7s; }
        .menu-card:nth-child(8) { animation-delay: 0.8s; }
        .menu-card:nth-child(9) { animation-delay: 0.9s; }
        .menu-card:nth-child(10) { animation-delay: 1.0s; }
        .menu-card:nth-child(11) { animation-delay: 1.1s; }
        .menu-card:nth-child(12) { animation-delay: 1.2s; }
    </style>
</head>
<body>

    <!-- Menu Toggle Button -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars" id="menuIcon"></i>
    </button>

    <!-- Mini Sidebar with Collapsible Categories -->
    <div class="mini-sidebar" id="miniSidebar">
        <!-- Category 1: الأعوان والموظفين -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>الأعوان والموظفين</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="index.php?action=dashboard" class="mini-menu-item">
                <i class="fas fa-dashboard"></i>
                <span>الإشعارات</span>
            </a>
            <a href="index.php?action=employees" class="mini-menu-item">
                <i class="fas fa-users"></i>
                <span>قائمة الأعوان</span>
            </a>
        </div>
        
        <!-- Category 2: الوثائق -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>الوثائق</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="index.php?action=badges&type=1" class="mini-menu-item">
                <i class="fas fa-id-card"></i>
                <span>رخص السياقة</span>
            </a>
            <a href="index.php?action=badges&type=0" class="mini-menu-item">
                <i class="fas fa-address-card"></i>
                <span>بطاقات الولاية</span>
            </a>
        </div>

        <!-- Category 3: الإجازات والرخص (Unifiées via MVC) -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>الإجازات والرخص</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
            <a href="Addconge/index.php" class="mini-menu-item">
                <i class="fas fa-plus-circle"></i>
                <span style="color: green;">إضافة إجازة سنويّة</span>
            </a>
            <?php endif; ?>
            <a href="index.php?action=remaining_leaves" class="mini-menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>الراحات المتبقية</span>
            </a>
			<?php
			$year = isset($_GET['choixdate']) ? $_GET['choixdate'] : date('Y');
			?>
            <a href="index.php?action=annual_leaves&year=<?= $year ?>" class="mini-menu-item">
                <i class="fas fa-umbrella-beach"></i>
                <span>الإجازات السنويّة</span>
            </a>
            <!-- Liens unifiés vers le routeur MVC pour les congés -->
            <a href="index.php?typeSuivie=5" class="mini-menu-item">
                <i class="fas fa-heartbeat"></i>
                <span>الرخص المرضيّة</span>
            </a>
            <a href="index.php?action=accidents" class="mini-menu-item">
                <i class="fas fa-ambulance"></i>
                <span>حوادث الشغل</span>
            </a>
            <a href="index.php?typeSuivie=0" class="mini-menu-item">
                <i class="fas fa-gift"></i>
                <span>الرخص الإستثنائية</span>
            </a>
            <a href="index.php?typeSuivie=2" class="mini-menu-item">
                <i class="fas fa-clock"></i>
                <span>الراحات التعويضيّة</span>
            </a>
            <a href="dossierconge.php" class="mini-menu-item">
                <i class="fas fa-folder-open"></i>
                <span>ملف العطل السنويّة</span>
            </a>
        </div>
        
        <!-- Category 4: التكوين و المهامّ -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>التكوين و المهامّ</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="index.php?typeSuivie=1" class="mini-menu-item">
                <i class="fas fa-graduation-cap"></i>
                <span>التكوين</span>
            </a>
            <a href="index.php?typeSuivie=3" class="mini-menu-item">
                <i class="fas fa-tasks"></i>
                <span>المهام</span>
            </a>
        </div>
        
        <!-- Category 5: الشؤون الطبية -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>الشؤون الطبية</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="index.php?action=medical_certificates" class="mini-menu-item">
                <i class="fas fa-user-md"></i>
                <span>طبيب الشغل</span>
            </a>
        </div>

        <!-- Category 6: التقارير والإحصائيات -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>التقارير والإحصائيات</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="tb/stat.php" class="mini-menu-item" target="_blank">
                <i class="fas fa-chart-bar"></i>
                <span>إحصائيّات</span>
            </a>
            <a href="dashboard_rh.php" class="mini-menu-item" target="_blank">
                <i class="fas fa-chart-bar"></i>
                <span>إحصائيّات عامّة</span>
            </a>
            <a href="index.php?action=departure'" class="mini-menu-item">
                <i class="fas fa-door-open"></i>
                <span>المغادرة</span>
            </a>
        </div>

        <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
        <!-- Category 7: الإدارة (Admin only) -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>الإدارة</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="carriere.php" class="mini-menu-item">
                <i class="fas fa-search"></i>
                <span>البحث في الأعوان</span>
            </a>
            <a href="Tickets1.php" class="mini-menu-item">
                <i class="fas fa-receipt"></i>
                <span>متابعة الوصولات</span>
            </a>
            <a href="Tickets.php" class="mini-menu-item">
                <i class="fas fa-receipt"></i>
                <span>متابعة الحضور</span>
            </a>
            <a href="../pointageAll/" target="_blank" class="mini-menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>رفع/طباعة الحضور</span>
            </a>
            <a href="tous_agents_conges.php?annee=<?php echo date('Y'); ?>" target="_blank" class="mini-menu-item">
                <i class="fas fa-calendar-check"></i>
                <span>طباعة العطل السّنويّة</span>
            </a>
            <a href="../virement/" target="_blank" class="mini-menu-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>تحويلات بنكيّة</span>
            </a>
            <a href="RIB.php" target="_blank" class="mini-menu-item">
                <i class="fas fa-wallet"></i>
                <span>أرصدة بنكيّة</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Category 8: التأديب -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>التأديب</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
            <a href="questionnaire.php" class="mini-menu-item">
                <i class="fas fa-file-alt"></i>
                <span>تحرير الإستجوابات</span>
            </a>
            <?php endif; ?>
            <a href="sanctions.php" class="mini-menu-item">
                <i class="fas fa-gavel"></i>
                <span>متابعة الإستجوابات</span>
            </a>
        </div>
        
        <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
        <!-- Category 9: التّدرّج و التّرقيات -->
        <div class="mini-menu-category" onclick="toggleCategory(this)">
            <span>التّدرّج و التّرقيات</span>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="category-items">
            <a href="../avancement/" target="_blank" class="mini-menu-item">
                <i class="fas fa-arrow-up"></i>
                <span>التّدرّج/التّرقيات</span>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="mini-sidebar-footer">
            <!-- Logout Button -->
            <a href="logout.php" class="mini-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>تسجيل الخروج</span>
            </a>
            
            <!-- Admin Button (Visible only for admin users) -->
            <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
            <a href="loginhistory.php" class="mini-admin-btn">
                <i class="fas fa-history"></i>
                <span>متابعة الولوج إلى التطبيق</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Access Bar -->
    <div class="quick-access-bar">
        <a href="c_agents.php" class="quick-access-item" style="background: #3498db;" data-tooltip="قائمة الأعوان">
            <i class="fas fa-users"></i>
        </a>
        <a href="c_congeannuelle.php" class="quick-access-item" style="background: #27ae60;" data-tooltip="الإجازات">
            <i class="fas fa-umbrella-beach"></i>
        </a>
        <!-- Lien unifié vers les congés maladie -->
        <a href="index.php?typeSuivie=5" class="quick-access-item" style="background: #e74c3c;" data-tooltip="الرخص المرضيّة">
            <i class="fas fa-heartbeat"></i>
        </a>
        <a href="statistics.php" class="quick-access-item" style="background: #17a2b8;" data-tooltip="الإحصائيات">
            <i class="fas fa-chart-bar"></i>
        </a>
        <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
        <a href="carriere.php" class="quick-access-item" style="background: #f39c12;" data-tooltip="البحث">
            <i class="fas fa-search"></i>
        </a>
        <?php endif; ?>
    </div>

    <script>
        // Menu Toggle Functionality
        const menuToggle = document.getElementById('menuToggle');
        const menuIcon = document.getElementById('menuIcon');
        const miniSidebar = document.getElementById('miniSidebar');
        const body = document.body;

        menuToggle.addEventListener('click', function() {
            miniSidebar.classList.toggle('active');
            body.classList.toggle('menu-collapsed');
            
            // Change icon based on menu state
            if (miniSidebar.classList.contains('active')) {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
            } else {
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            }
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = miniSidebar.contains(event.target);
            const isClickInsideToggle = menuToggle.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickInsideToggle && miniSidebar.classList.contains('active')) {
                miniSidebar.classList.remove('active');
                body.classList.remove('menu-collapsed');
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            }
        });

        // Function to toggle category items
        function toggleCategory(categoryElement) {
            categoryElement.classList.toggle('collapsed');
            const categoryItems = categoryElement.nextElementSibling;
            
            if (categoryItems && categoryItems.classList.contains('category-items')) {
                categoryItems.classList.toggle('collapsed');
                const chevron = categoryElement.querySelector('i');
                if (chevron) {
                    if (categoryItems.classList.contains('collapsed')) {
                        chevron.style.transform = 'rotate(-90deg)';
                    } else {
                        chevron.style.transform = 'rotate(0deg)';
                    }
                }
                saveCategoryState(categoryElement, !categoryItems.classList.contains('collapsed'));
            }
        }

        // Function to save category state to localStorage
        function saveCategoryState(categoryElement, isExpanded) {
            const categoryText = categoryElement.querySelector('span').textContent;
            const states = JSON.parse(localStorage.getItem('categoryStates') || '{}');
            states[categoryText] = isExpanded;
            localStorage.setItem('categoryStates', JSON.stringify(states));
        }

        // Function to load category states from localStorage
        function loadCategoryStates() {
            const states = JSON.parse(localStorage.getItem('categoryStates') || '{}');
            const categories = document.querySelectorAll('.mini-menu-category');
            
            categories.forEach(category => {
                const categoryText = category.querySelector('span').textContent;
                const categoryItems = category.nextElementSibling;
                
                if (categoryItems && categoryItems.classList.contains('category-items')) {
                    if (states.hasOwnProperty(categoryText) && !states[categoryText]) {
                        category.classList.add('collapsed');
                        categoryItems.classList.add('collapsed');
                        const chevron = category.querySelector('i');
                        if (chevron) {
                            chevron.style.transform = 'rotate(-90deg)';
                        }
                    } else {
                        category.classList.remove('collapsed');
                        categoryItems.classList.remove('collapsed');
                        const chevron = category.querySelector('i');
                        if (chevron) {
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                }
            });
        }

        // Load saved states when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadCategoryStates();
        });
    </script>
</body>
</html>