<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="1800">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
            --sanctions-color: #e74c3c;
            --sanctions-dark: #c0392b;
            --detachment-color: #8e44ad;
            --detachment-dark: #6c3483;
            --career-color: #34495e;
            --recruitment-color: #e67e22;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            max-width: 1400px;
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
        }

        .header-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            text-align: center;
            position: relative;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Notifications Grid */
        .notifications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .notification-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .notification-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
        }

        /* Card types */
        .notification-card.accident { border-left-color: var(--danger-color); }
        .notification-card.accident::before { background: var(--danger-color); }
        .notification-card.professional { border-left-color: var(--warning-color); }
        .notification-card.professional::before { background: var(--warning-color); }
        .notification-card.driving { border-left-color: var(--info-color); }
        .notification-card.driving::before { background: var(--info-color); }
        .notification-card.medical { border-left-color: var(--success-color); }
        .notification-card.medical::before { background: var(--success-color); }
        .notification-card.retirement { border-left-color: #9b59b6; }
        .notification-card.retirement::before { background: #9b59b6; }
        .notification-card.promotion { border-left-color: #1abc9c; }
        .notification-card.promotion::before { background: #1abc9c; }
        .notification-card.recruitment { border-left-color: var(--recruitment-color); }
        .notification-card.recruitment::before { background: var(--recruitment-color); }
        .notification-card.sanctions { border-left-color: var(--sanctions-color); }
        .notification-card.sanctions::before { background: var(--sanctions-color); }
        .notification-card.detachment { border-left-color: var(--detachment-color); }
        .notification-card.detachment::before { background: var(--detachment-color); }
        .notification-card.career { border-left-color: var(--career-color); }
        .notification-card.career::before { background: var(--career-color); }

        .notification-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: 15px;
            color: white;
        }

        .notification-card.accident .notification-icon { background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%); }
        .notification-card.professional .notification-icon { background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%); }
        .notification-card.driving .notification-icon { background: linear-gradient(135deg, var(--info-color) 0%, #138d75 100%); }
        .notification-card.medical .notification-icon { background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%); }
        .notification-card.retirement .notification-icon { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
        .notification-card.promotion .notification-icon { background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%); }
        .notification-card.recruitment .notification-icon { background: linear-gradient(135deg, var(--recruitment-color) 0%, #d35400 100%); }
        .notification-card.sanctions .notification-icon { background: linear-gradient(135deg, var(--sanctions-color) 0%, var(--sanctions-dark) 100%); }
        .notification-card.detachment .notification-icon { background: linear-gradient(135deg, var(--detachment-color) 0%, var(--detachment-dark) 100%); }
        .notification-card.career .notification-icon { background: linear-gradient(135deg, var(--career-color) 0%, #2c3e50 100%); }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .notification-count {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            line-height: 1;
        }

        .notification-card.accident .notification-count { color: var(--danger-color); }
        .notification-card.professional .notification-count { color: var(--warning-color); }
        .notification-card.driving .notification-count { color: var(--info-color); }
        .notification-card.medical .notification-count { color: var(--success-color); }
        .notification-card.retirement .notification-count { color: #9b59b6; }
        .notification-card.promotion .notification-count { color: #1abc9c; }
        .notification-card.recruitment .notification-count { color: var(--recruitment-color); }
        .notification-card.sanctions .notification-count { color: var(--sanctions-color); }
        .notification-card.detachment .notification-count { color: var(--detachment-color); }
        .notification-card.career .notification-count { color: var(--career-color); }

        .notification-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.5;
            font-size: 0.9rem;
        }

        .notification-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .notification-card.accident .notification-action { background: var(--danger-color); color: white; }
        .notification-card.professional .notification-action { background: var(--warning-color); color: white; }
        .notification-card.driving .notification-action { background: var(--info-color); color: white; }
        .notification-card.medical .notification-action { background: var(--success-color); color: white; }
        .notification-card.retirement .notification-action { background: #9b59b6; color: white; }
        .notification-card.promotion .notification-action { background: #1abc9c; color: white; }
        .notification-card.recruitment .notification-action { background: var(--recruitment-color); color: white; }
        .notification-card.sanctions .notification-action { background: var(--sanctions-color); color: white; }
        .notification-card.detachment .notification-action { background: var(--detachment-color); color: white; }
        .notification-card.career .notification-action { background: var(--career-color); color: white; }

        .notification-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Employee List Styles */
        .employee-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 10px;
            background: var(--light-bg);
        }

        .employee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            margin-bottom: 8px;
            background: white;
            border-radius: 8px;
            border-left: 3px solid;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .employee-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .retirement .employee-item { border-left-color: #9b59b6; }
        .promotion .employee-item { border-left-color: #1abc9c; }
        .career .employee-item { border-left-color: var(--career-color); }
        .recruitment .employee-item { border-left-color: var(--recruitment-color); }
        .sanctions .employee-item { border-left-color: var(--sanctions-color); }
        .detachment .employee-item { border-left-color: var(--detachment-color); }

        .employee-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
            flex: 1;
        }

        .employee-date {
            font-size: 0.8rem;
            color: #6c757d;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        .employee-promotion {
            font-size: 0.75rem;
            color: white;
            background: var(--success-color);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .promotion-exceptional {
            font-size: 0.75rem;
            color: white;
            background: #9b59b6;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .employee-leave-balance {
            font-size: 0.7rem;
            color: white;
            background: #e74c3c;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .career-change {
            font-size: 0.75rem;
            color: var(--primary-color);
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
            text-align: center;
        }

        .recruitment-date {
            font-size: 0.75rem;
            color: white;
            background: var(--recruitment-color);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .sanction-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .sanction-badge.open {
            background: #f39c12;
            color: white;
        }

        .sanction-badge.pending {
            background: #3498db;
            color: white;
        }

        .sanction-badge.review {
            background: #9b59b6;
            color: white;
        }

        .sanction-discipline {
            font-size: 0.65rem;
            color: #e74c3c;
            font-weight: 600;
        }

        .sanction-jours {
            font-size: 0.65rem;
            color: #e67e22;
            font-weight: 600;
        }

        .detachment-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .detachment-badge.active {
            background: #27ae60;
            color: white;
        }

        .detachment-badge.pending {
            background: #f39c12;
            color: white;
        }

        .detachment-badge.expiring {
            background: #e74c3c;
            color: white;
        }

        .detachment-badge.normal {
            background: #3498db;
            color: white;
        }

        .detachment-source {
            font-size: 0.65rem;
            color: #7f8c8d;
            background: #f8f9fa;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .detachment-days {
            font-size: 0.65rem;
            color: #e67e22;
            font-weight: 600;
        }

        .detachment-days.urgent {
            color: #e74c3c;
            font-weight: 700;
        }

        .urgent-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--danger-color);
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state-description {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header.retirement {
            border-bottom-color: #9b59b6;
        }

        .section-header.promotion {
            border-bottom-color: #1abc9c;
        }

        .section-header.current {
            border-bottom-color: var(--secondary-color);
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        @media (max-width: 768px) {
            .header-title { font-size: 2rem; }
            .header-subtitle { font-size: 1.1rem; }
            .notifications-grid { grid-template-columns: 1fr; }
            .notification-card { padding: 20px; }
            .notification-count { font-size: 2rem; }
            .employee-item { flex-direction: column; align-items: flex-start; gap: 8px; }
            .section-title { font-size: 1.2rem; }
        }

        @media (max-width: 1200px) and (min-width: 769px) {
            .notifications-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .notification-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .employee-list::-webkit-scrollbar {
            width: 6px;
        }

        .employee-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .employee-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .employee-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    <title>نظام الإشعارات والتنبيهات</title>
</head>

<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="main-container">
    <!-- Header Section -->
    <div class="header-section">
        <h1 class="header-title">نظام الإشعارات والتنبيهات</h1>
        <p class="header-subtitle"><?php echo htmlspecialchars($departmentName); ?></p>
    </div>

    <!-- ============================================ -->
    <!-- SECTION 1: NOTIFICATIONS PRINCIPALES (EN HAUT) -->
    <!-- ============================================ -->
    <?php if (!empty($notifications)): ?>
        <div class="notifications-grid">
            <?php foreach ($notifications as $key => $notification): ?>
                <div class="notification-card <?php echo $notification['class']; ?>">
                    <?php if ($notification['count'] > 5 && $key != 'sanctions' && $key != 'detachment'): ?>
                        <span class="urgent-badge">عاجل</span>
                    <?php elseif (($key == 'sanctions' && $notification['count'] > 3) || ($key == 'detachment' && $notification['count'] > 3)): ?>
                        <span class="urgent-badge">مهم</span>
                    <?php endif; ?>
                    
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi <?php echo $notification['icon']; ?>"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title"><?php echo $notification['name']; ?></h3>
                            <div class="notification-count"><?php echo $notification['count']; ?></div>
                        </div>
                    </div>
                    
                    <p class="notification-description">
                        <?php echo $notification['description']; ?>
                    </p>
                    
                    <a href="<?php echo $notification['link']; ?>" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        الانتقال للمراجعة
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="empty-state-title">لا توجد تنبيهات حالياً</h3>
            <p class="empty-state-description">
                جميع الوثائق والإجازات سارية المفعول. تمت إدارة جميع المهام بنجاح.
            </p>
        </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- SECTION 2: ÉVÉNEMENTS À VENIR (التغييرات القادمة) -->
    <!-- ============================================ -->
    <?php if (!empty($retirementDetails) || !empty($promotionDetails)): ?>
        <div class="section-header retirement">
            <div class="section-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <h2 class="section-title">التغييرات القادمة</h2>
        </div>

        <div class="notifications-grid">
            <!-- التقاعد -->
            <?php if (!empty($retirementDetails)): ?>
                <div class="notification-card retirement">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-person-walking"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">التقاعد خلال الأشهر الثلاثة القادمة</h3>
                            <div class="notification-count"><?php echo count($retirementDetails); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($retirementDetails as $employee): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?php echo htmlspecialchars($employee['nom']); ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="employee-date"><?php echo date('Y-m-d', strtotime($employee['retirement_date'])); ?></span>
                                    <?php if ($employee['leave_balance'] > 0): ?>
                                        <span class="employee-leave-balance mt-1"><?php echo $employee['leave_balance']; ?> يوم إجازة</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="departure.php?tab=retraite" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        عرض التفاصيل
                    </a>
                </div>
            <?php endif; ?>

            <!-- الترقيات -->
            <?php if (!empty($promotionDetails)): ?>
                <div class="notification-card promotion">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">الترقيات المتوقّعة خلال الثلاثة أشهر القادمة</h3>
                            <div class="notification-count"><?php echo count($promotionDetails); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($promotionDetails as $employee): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?php echo htmlspecialchars($employee['nom']); ?></span>
                                <?php if (strpos($employee['promotion_type'], 'استثنائية') !== false): ?>
                                    <span class="promotion-exceptional"><?php echo $employee['promotion_type']; ?></span>
                                <?php else: ?>
                                    <span class="employee-promotion"><?php echo $employee['promotion_type']; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="promotion_list.php" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        عرض التفاصيل
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- SECTION 3: ÉVÉNEMENTS ACTUELS (التغييرات الحالية) EN BAS -->
    <!-- ============================================ -->
    <?php if (!empty($careerDetails) || !empty($newRecruits) || !empty($openSanctions) || !empty($detachmentFiles)): ?>
        <div class="section-header current">
            <div class="section-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <h2 class="section-title">التغييرات الحالية</h2>
        </div>

        <div class="notifications-grid">
            <!-- تغييرات المسار الوظيفي -->
            <?php if (!empty($careerDetails)): ?>
                <div class="notification-card career">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">تغييرات المسار الوظيفي</h3>
                            <div class="notification-count"><?php echo count($careerDetails); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($careerDetails as $change): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?php echo htmlspecialchars($change['nom']); ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="career-change"><?php echo $change['ancienrang']; ?> → <?php echo $change['nouveaurang']; ?></span>
                                    <small class="employee-date mt-1"><?php echo date('Y-m-d', strtotime($change['dateeffet'])); ?></small>
                                    <?php if (!empty($change['commission'])): ?>
                                        <small class="text-muted mt-1">اللجنة: <?php echo htmlspecialchars($change['commission']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="career_changes.php" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        عرض التفاصيل
                    </a>
                </div>
            <?php endif; ?>

            <!-- المنتدبون الجدد -->
            <?php if (!empty($newRecruits)): ?>
                <div class="notification-card recruitment">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">المنتدبون الجدد</h3>
                            <div class="notification-count"><?php echo count($newRecruits); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($newRecruits as $recruit): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?php echo htmlspecialchars($recruit['nom']); ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="recruitment-date"><?php echo date('Y-m-d', strtotime($recruit['daterec'])); ?></span>
                                    <?php if (!empty($recruit['fonction'])): ?>
                                        <small class="employee-date mt-1"><?php echo htmlspecialchars($recruit['fonction']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="new_recruits.php" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        عرض التفاصيل
                    </a>
                </div>
            <?php endif; ?>

            <!-- العقوبات غير المغلقة -->
            <?php if (!empty($openSanctions)): ?>
                <div class="notification-card sanctions">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">العقوبات غير المغلقة</h3>
                            <div class="notification-count"><?php echo count($openSanctions); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($openSanctions as $sanction): 
                            $statut_text = '';
                            $statut_class = '';
                            
                            switch($sanction['statut']) {
                                case 'open':
                                    $statut_text = 'مفتوحة';
                                    $statut_class = 'open';
                                    break;
                                case 'pending':
                                    $statut_text = 'قيد الانتظار';
                                    $statut_class = 'pending';
                                    break;
                                case 'review':
                                    $statut_text = 'مراجعة';
                                    $statut_class = 'review';
                                    break;
                                default:
                                    $statut_text = $sanction['statut'];
                                    $statut_class = '';
                            }
                        ?>
                            <div class="employee-item">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="employee-name"><?php echo htmlspecialchars($sanction['nom']); ?></span>
                                        <span class="sanction-badge <?php echo $statut_class; ?>"><?php echo $statut_text; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted"><?php echo mb_substr(htmlspecialchars($sanction['faute']), 0, 30); ?>...</small>
                                        <?php if ($sanction['conseil_discipline']): ?>
                                            <span class="sanction-discipline">
                                                <i class="bi bi-gavel"></i> مجلس تأديب
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="employee-date">
                                            <i class="bi bi-calendar"></i> 
                                            <?php echo date('Y-m-d', strtotime($sanction['datefaute'])); ?>
                                        </small>
                                        <?php if ($sanction['jours_ecoules'] > 0): ?>
                                            <span class="sanction-jours">
                                                <i class="bi bi-hourglass"></i> 
                                                <?php echo $sanction['jours_ecoules']; ?> يوم
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (isset($stats['sanctions']) && $stats['sanctions'] > count($openSanctions)): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                و <?php echo ($stats['sanctions'] - count($openSanctions)); ?> عقوبات أخرى...
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <a href="sanctions.php?statut=open,pending,review" class="notification-action mt-2">
                        <i class="bi bi-arrow-left"></i>
                        عرض كل العقوبات
                    </a>
                </div>
            <?php endif; ?>

            <!-- ملفات الإلحاق غير المغلقة -->
            <?php if (!empty($detachmentFiles)): ?>
                <div class="notification-card detachment">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">ملفات الإلحاق الجارية</h3>
                            <div class="notification-count"><?php echo count($detachmentFiles); ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($detachmentFiles as $detachment): 
                            $statut_text = '';
                            $statut_class = '';
                            $days_class = '';
                            
                            switch($detachment['statut']) {
                                case 1:
                                    $statut_text = 'نشط';
                                    $statut_class = 'active';
                                    break;
                                case 2:
                                    $statut_text = 'قيد الانتظار';
                                    $statut_class = 'pending';
                                    break;
                                default:
                                    $statut_text = 'أخرى';
                                    $statut_class = 'normal';
                            }
                            
                            $jours_restants = isset($detachment['jours_restants']) ? intval($detachment['jours_restants']) : 0;
                            if ($jours_restants <= 30 && $jours_restants > 0) {
                                $days_class = 'urgent';
                            }
                        ?>
                            <div class="employee-item">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="employee-name"><?php echo htmlspecialchars($detachment['nomprenom']); ?></span>
                                        <span class="detachment-badge <?php echo $statut_class; ?>"><?php echo $statut_text; ?></span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">
                                            <i class="bi bi-building"></i> 
                                            <?php echo htmlspecialchars($detachment['affectation'] ?? 'غير محدد'); ?>
                                        </small>
                                        <?php if (!empty($detachment['source'])): ?>
                                            <span class="detachment-source">
                                                <i class="bi bi-diagram-3"></i> 
                                                <?php echo htmlspecialchars($detachment['source']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="employee-date">
                                            <i class="bi bi-calendar"></i> 
                                            بداية: <?php echo date('Y-m-d', strtotime($detachment['datedetachement'])); ?>
                                        </small>
                                        <?php if (!empty($detachment['date_fin']) && $detachment['date_fin'] != '0000-00-00'): ?>
                                            <small class="employee-date">
                                                <i class="bi bi-calendar-check"></i> 
                                                نهاية: <?php echo date('Y-m-d', strtotime($detachment['date_fin'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($jours_restants > 0): ?>
                                        <div class="d-flex justify-content-end mt-1">
                                            <span class="detachment-days <?php echo $days_class; ?>">
                                                <i class="bi bi-hourglass-split"></i> 
                                                <?php echo $jours_restants; ?> يوم متبقي
                                            </span>
                                        </div>
                                    <?php elseif ($jours_restants <= 0 && $jours_restants != 0): ?>
                                        <div class="d-flex justify-content-end mt-1">
                                            <span class="detachment-days urgent">
                                                <i class="bi bi-exclamation-triangle"></i> 
                                                منتهي الصلاحية
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($detachment['observations'])): ?>
                                        <small class="text-muted mt-1">
                                            <i class="bi bi-chat"></i> 
                                            <?php echo mb_substr(htmlspecialchars($detachment['observations']), 0, 30); ?>...
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (isset($stats['detachment']) && $stats['detachment'] > count($detachmentFiles)): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                و <?php echo ($stats['detachment'] - count($detachmentFiles)); ?> ملفات أخرى...
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <a href="departure.php?tab=detachement" class="notification-action mt-2">
                        <i class="bi bi-arrow-left"></i>
                        عرض كل ملفات الإلحاق
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    // Add click animation to notification cards
    document.querySelectorAll('.notification-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                const link = this.querySelector('a');
                if (link) {
                    link.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        link.style.transform = '';
                    }, 150);
                }
            }
        });
    });

    // Auto-refresh notifications every 30 seconds
    setInterval(() => {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                console.log('Page refreshed for new notifications');
            })
            .catch(err => console.log('Auto-refresh error:', err));
    }, 30000);
});
</script>
</body>
</html>